<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Banner extends Model
{
    use HasUuids;

    protected $fillable = [
        'image_url',
        'offer_text',
    ];
}
