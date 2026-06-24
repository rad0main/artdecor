<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CatalogCategory extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(CatalogImage::class, 'category_id');
    }
}
