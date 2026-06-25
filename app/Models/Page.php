<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\PageBuilderService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'meta_description',
        'content',
        'layout',
        'is_published',
        'is_homepage',
    ];

    protected $casts = [
        'content' => 'array',
        'is_published' => 'boolean',
        'is_homepage' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (self $page) {
            if (empty($page->slug) && $page->title) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    public function renderContent(): string
    {
        $builder = app(PageBuilderService::class);
        return $builder->render($this->content ?? []);
    }
}
