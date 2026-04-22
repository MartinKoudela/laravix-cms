<?php

namespace App\Services;

use App\Models\Site;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SiteResolver
{
    public function resolve(string $host): Site
    {
        $site = Site::where('domain', $host)->first();

        if (! $site) {
            throw new NotFoundHttpException;
        }

        return $site;
    }
}
