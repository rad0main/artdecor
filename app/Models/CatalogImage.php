<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CatalogImage extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'title',
        'category_id',
        'width',
        'height',
        'file_size',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'width' => 'integer',
        'height' => 'integer',
        'file_size' => 'integer',
        'sort_order' => 'integer',
    ];

    // ─── Spatie Media Library ───────────────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('catalog')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(300)
                    ->height(300)
                    ->format('webp')
                    ->nonQueued();

                $this->addMediaConversion('preview')
                    ->width(800)
                    ->height(600)
                    ->format('webp')
                    ->nonQueued();
            });
    }

    // ─── Relationships ──────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(CatalogCategory::class);
    }

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(CatalogColor::class, 'catalog_image_color', 'image_id', 'color_id');
    }

    // ─── Scopes ─────────────────────────────────────────────────

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['category'] ?? null, function (Builder $q, $v) {
            $q->where('category_id', $v);
        })->when($filters['color'] ?? null, function (Builder $q, $v) {
            $q->whereHas('colors', fn (Builder $sq) => $sq->where('catalog_colors.id', $v));
        })->when($filters['search'] ?? null, function (Builder $q, $v) {
            $q->where('title', 'ilike', "%{$v}%");
        });
    }

    // ─── Accessors ──────────────────────────────────────────────

    public function getThumbUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('catalog', 'thumb');
    }

    public function getPreviewUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('catalog', 'preview');
    }

    public function getOriginalUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('catalog');
    }
}
