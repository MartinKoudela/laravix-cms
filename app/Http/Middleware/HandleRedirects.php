<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Redirect;
use Symfony\Component\HttpFoundation\Response;

class HandleRedirects
{

    public function handle(Request $request, Closure $next): Response
    {
        $redirect = Redirect::where('old_url', $request->path())->first();

        if ($redirect) {
            $url = $redirect->content->is_homepage ? '/' : '/' . $redirect->content->slug;

            return redirect($url, $redirect->status_code->value);
        }

        return $next($request);
    }
}
