<?php

namespace Vhnh\Bookmark;

trait Bookmarkable
{
    public static function bootBookmarkable()
    {
        static::deleting(function ($model) {
            $model->bookmarks()->delete();
        });
    }

    public function bookmark($bookmarker = null)
    {
        $bookmarker = $bookmarker ?: app('auth')->user();

        $this->bookmarks()->create([
            'bookmarker_id' => $bookmarker->id,
        ]);

        return $this;
    }

    public function unmark()
    {
        $this->bookmarks()->fromAuth()->delete();

        return $this;
    }

    public function isBookmarked()
    {
        return $this->bookmarks()->fromAuth()->exists();
    }

    public function bookmarks()
    {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }
}
