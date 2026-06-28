<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'message',
        'source',
        'article_ids',
        'facade_top_color',
        'facade_bottom_color',
        'status',
        'manager_comment',
    ];

    protected $casts = [
        'article_ids' => 'array',
    ];

    public const SOURCES = ['catalog', 'primerka', 'callback', 'question', 'order'];
    public const STATUSES = ['new', 'contacted', 'closed'];
}
