<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Promethys\Revive\Concerns\Recyclable;

#[Fillable(['content_id', 'key', 'value'])]
class ContentField extends Model
{
    use HasFactory, SoftDeletes, Recyclable;
    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
