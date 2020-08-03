<?php

namespace Vhnh\Bookmark;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $with = [
        'bookmarkable'
    ];

    protected $fillable = [
        'bookmarker_id',
    ];

    public function scopeFromAuth($query)
    {
        return $query->where('bookmarker_id', app('auth')->user()->id);
    }

    public function bookmarkable()
    {
        return $this->morphTo();
    }
}
