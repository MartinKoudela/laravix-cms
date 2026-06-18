<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TaxonomyResource;
use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaxonomyController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $site = $request->attributes->get('site');

        $query = Taxonomy::query()
            ->where('site_id', $site->id)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('name');

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        return TaxonomyResource::collection($query->get());
    }
}
