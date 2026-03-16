<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Recipe extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'image_url',
        'is_exclusive',
        'is_trending',
        'description',
        'ingredients',
        'instructions',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'ingredients'  => 'array',
            'instructions' => 'array',
            'is_exclusive' => 'boolean',
            'is_trending'  => 'boolean',
        ];
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
