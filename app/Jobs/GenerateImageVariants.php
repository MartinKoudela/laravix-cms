<?php

namespace App\Jobs;

use App\Enums\ImageVariant;
use App\Models\Media;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class GenerateImageVariants implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Media $media,
    ) {}

    public function handle(): void
    {
        if (! str_starts_with($this->media->mime_type, 'image/')) {
            return;
        }

        $original = Storage::disk($this->media->disk)->get($this->media->path);
        $manager = ImageManager::gd();
        $variants = [];

        foreach (ImageVariant::cases() as $variant) {
            if ($variant === ImageVariant::FULL) {
                continue;
            }

            $image = $manager->read($original);

            if ($variant === ImageVariant::THUMBNAIL) {
                $image->cover($variant->width(), $variant->height());
            } else {
                $image->scale($variant->width(), $variant->height());
            }

            $path = 'variants/'.$variant->value.'/'.basename($this->media->path);
            Storage::disk($this->media->disk)->put($path, $image->toJpeg());

            $variants[$variant->value] = $path;
        }

        $this->media->update(['variants' => $variants]);
    }
}
