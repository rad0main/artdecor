<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    protected $fillable = [
        'session_id',
        'image_id',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(CatalogImage::class);
    }
}
