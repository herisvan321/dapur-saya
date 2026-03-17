<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class ViewLog extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'viewable_type',
        'viewable_id',
    ];

    /**
     * Get the parent viewable model (Recipe or Category).
     */
    public function viewable()
    {
        return $this->morphTo();
    }
}
