![VHNH](https://avatars3.githubusercontent.com/u/66573047?s=200)

# vhnh/bookmark

The Vhnh Bookmark package allows bookmarking eloquent models within your [Laravel](https://github.com/laravel/laravel) application.

## Setup

Frist we'll add the `Vhnh\Bookmark\Bookmarker` trait to our user model. The trait allows retrieving bookmarks and cascades the `Vhnh\Bookmark\Bookmark`s if the user will be deleted.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Vhnh\Bookmark\Bookmarker;

class User extends Model
{
    use Bookmarker;

    // ...
}
```

Next we'll add the `Vhnh\Bookmark\Bookmarkable` trait to any models that can be bookmarked. This trait cascades a `Vhnh\Bookmark\Bookmark` if a bookmarkable instance will be deleted and adds the functionality to create and remove bookmarks.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Vhnh\Bookmark\Bookmarkable;

class Post extends Model
{
    use Bookmarkable;

    // ...
}
```

## Creating and removing bookmarks

Creating and removing bookmarks is as simple as calling the a method on our bookmarkable models.

```php
<?php

namespace App\Http\Controllers;

use App\Post;

class BookmarkPostsController
{
    public function store(Post $post)
    {
        $post->bookmark();

        // ...
    }

    public function destroy(Post $post)
    {
        $post->unmark();

        // ...
    }
}
```

However, since it is more common to toggle a bookmark we can simplify our controller.

```php
<?php

namespace App\Http\Controllers;

use App\Post;

class BookmarkPostsController
{
    public function __invoke(Post $post)
    {
        if($post->isBookmarked()) {
            return $post->bookmark();
        }

        return $post->unmark();
    }
}
```

## Retrieving bookmarks

To retrieve a users bookmarks you may call the `Vhnh\Bookmark\Bookmarker::bookmarks()` relationship that is provided by the `Vhnh\Bookmark\Bookmarker` trait. The `Vhnh\Bookmark\Bookmark` model will eager load the related bookmarkable instance.

```php
$user->bookmarks
```

We can also be more expecit by fetching the the bookmarks:
```php
$user->bookmarks()->without('bookmarkable')->paginate(15);


$user->bookmarks()->with('bookmarkable.title')->latest()->limit(5)->get();

// etc.
```

## License
The Vhnh Bookmark package is open-sourced software licensed under the [MIT](http://opensource.org/licenses/MIT) license.