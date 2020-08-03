<?php

namespace Vhnh\Bookmark;

trait Bookmarker
{
    public static function bootBookmarker()
    {
        static::deleting(function ($model) {
            $model->bookmarks()->delete();
        });
    }
    
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'bookmarker_id');
    }
}
