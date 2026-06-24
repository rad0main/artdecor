<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkCategory extends Model
{
    protected $fillable = [
        'slug',
        'name',
    ];

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }
}
