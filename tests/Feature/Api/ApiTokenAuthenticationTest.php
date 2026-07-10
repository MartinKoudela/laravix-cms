<?php

use App\Enums\SiteMode;
use App\Models\Site;
use App\Models\SiteApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

function headlessSite(string $domain = 'headless.test'): Site
{
    return Site::factory()->create([
        'domain' => $domain,
        'mode' => SiteMode::HEADLESS,
    ]);
}

function apiGet(object $test, string $domain, ?string $token = null)
{
    $headers = ['X-Site-Domain' => $domain];

    if ($token !== null) {
        $headers['Authorization'] = 'Bearer '.$token;
    }

    return $test->getJson('/api/v1/settings', $headers);
}

test('request without token is rejected', function () {
    headlessSite();

    apiGet($this, 'headless.test')
        ->assertUnauthorized()
        ->assertJson(['message' => 'Missing API token.'])
        ->assertHeader('WWW-Authenticate', 'Bearer');
});

test('request with invalid token is rejected', function () {
    headlessSite();

    apiGet($this, 'headless.test', 'lvx_definitely-not-a-valid-token')
        ->assertUnauthorized()
        ->assertJson(['message' => 'Invalid API token.']);
});

test('request with expired token is rejected', function () {
    $site = headlessSite();
    $result = SiteApiToken::generateFor($site, 'Expired', now()->subDay());

    apiGet($this, 'headless.test', $result['plaintext'])
        ->assertUnauthorized()
        ->assertJson(['message' => 'API token has expired.']);
});

test('request with valid token succeeds', function () {
    $site = headlessSite();
    $result = SiteApiToken::generateFor($site, 'Frontend');

    apiGet($this, 'headless.test', $result['plaintext'])
        ->assertSuccessful();
});

test('token of another site is rejected', function () {
    headlessSite();
    $otherSite = headlessSite('other.test');
    $result = SiteApiToken::generateFor($otherSite, 'Other frontend');

    apiGet($this, 'headless.test', $result['plaintext'])
        ->assertUnauthorized()
        ->assertJson(['message' => 'Invalid API token.']);
});

test('theme site is rejected before token check', function () {
    Site::factory()->create([
        'domain' => 'theme.test',
        'mode' => SiteMode::THEME,
    ]);

    apiGet($this, 'theme.test')
        ->assertForbidden()
        ->assertJson(['message' => 'API is not enabled for this site.']);
});

test('valid request updates last_used_at at most once per minute', function () {
    $site = headlessSite();
    $result = SiteApiToken::generateFor($site, 'Frontend');
    $token = $result['token'];

    $start = now()->startOfMinute();
    $this->travelTo($start);

    apiGet($this, 'headless.test', $result['plaintext'])->assertSuccessful();
    expect($token->refresh()->last_used_at->timestamp)->toBe($start->timestamp);

    $this->travelTo($start->copy()->addSeconds(30));
    apiGet($this, 'headless.test', $result['plaintext'])->assertSuccessful();
    expect($token->refresh()->last_used_at->timestamp)->toBe($start->timestamp);

    $later = $start->copy()->addMinutes(2);
    $this->travelTo($later);
    apiGet($this, 'headless.test', $result['plaintext'])->assertSuccessful();
    expect($token->refresh()->last_used_at->timestamp)->toBe($later->timestamp);
});

test('rate limiter is keyed by token when authenticated', function () {
    $site = headlessSite();
    $result = SiteApiToken::generateFor($site, 'Frontend');

    $request = Request::create('/api/v1/settings');
    $request->attributes->set('apiToken', $result['token']);

    $limit = RateLimiter::limiter('api')($request);

    expect($limit->key)->toBe('api-token:'.$result['token']->id);
});

test('rate limiter falls back to ip without token', function () {
    $request = Request::create('/api/v1/settings');

    $limit = RateLimiter::limiter('api')($request);

    expect($limit->key)->toBe($request->ip());
});
