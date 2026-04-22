<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Collection;

class MediaResolver
{

    public function resolve(Collection $mediaIds): Collection
    {
        return Media::whereIn('id', $mediaIds)->get()->keyBy('id');
    }
}
