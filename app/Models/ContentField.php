<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['content_id', 'key', 'value'])]
class ContentField extends Model
{
    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
