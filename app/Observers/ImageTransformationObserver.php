<?php

namespace App\Observers;

use App\Jobs\GenerateImageVariants;
use App\Models\Media;

class ImageTransformationObserver
{
    public function created(Media $media): void
    {
        GenerateImageVariants::dispatch($media);
    }
}
