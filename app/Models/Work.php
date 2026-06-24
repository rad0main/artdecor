<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Work extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('works')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(400)
                    ->height(300)
                    ->format('webp')
                    ->nonQueued();

                $this->addMediaConversion('preview')
                    ->width(1200)
                    ->height(900)
                    ->format('webp')
                    ->nonQueued();
            });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(WorkCategory::class);
    }

    public function getThumbUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('works', 'thumb');
    }

    public function getPreviewUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('works', 'preview');
    }
}
