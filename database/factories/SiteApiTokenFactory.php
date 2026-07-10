<?php

namespace Database\Factories;

use App\Models\Site;
use App\Models\SiteApiToken;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteApiToken>
 */
class SiteApiTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plaintext = SiteApiToken::newPlaintextToken();

        return [
            'site_id' => Site::factory(),
            'name' => fake()->words(2, true),
            'token' => SiteApiToken::hashToken($plaintext),
            'prefix' => substr($plaintext, 0, 12),
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDay(),
        ]);
    }
}
