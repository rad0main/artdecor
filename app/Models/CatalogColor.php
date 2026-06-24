<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CatalogColor extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'hex',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(CatalogImage::class, 'catalog_image_color');
    }
}
