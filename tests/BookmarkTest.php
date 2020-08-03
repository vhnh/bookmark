<?php

namespace Vhnh\Bookmark\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Vhnh\Bookmark\Bookmark;
use Vhnh\Bookmark\Bookmarkable;
use Vhnh\Bookmark\Bookmarker;

class BookmarkTest extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    protected function getPackageProviders($app)
    {
        return ['Vhnh\Bookmark\ServiceProvider'];
    }

    protected function signIn($user = null)
    {
        $user = $user ?: User::create();
        $this->actingAs($user);
        return $user->fresh();
    }

    /** @test */
    public function a_user_can_bookmark_a_bookmarkable()
    {
        $this->signIn();
        $post = Post::create()->bookmark();

        $this->assertTrue($post->isBookmarked());
        
        $this->assertDatabaseHas('bookmarks', [
            'bookmarker_id' => 1,
            'bookmarkable_id' => 1,
            'bookmarkable_type' => 'Vhnh\Bookmark\Tests\Post'
        ]);
    }

    /** @test */
    public function a_user_can_unmark_its_bookmarks()
    {
        $this->signIn();
        $post = Post::create()->bookmark();
            
        $this->assertTrue($post->isBookmarked());

        $post->unmark();

        $this->assertFalse($post->isBookmarked());

        $this->assertDatabaseMissing('bookmarks', [
            'bookmarker_id' => 1,
            'bookmarkable_id' => 1,
            'bookmarkable_type' => 'Vhnh\Bookmark\Tests\Post'
        ]);
    }

    /** @test */
    public function it_retrieves_a_users_bookmarks()
    {
        $user = User::create();
        $post = Post::create();

        $bookmark = Bookmark::forceCreate([
            'bookmarker_id' => $user->id,
            'bookmarkable_id' => $post->id,
            'bookmarkable_type' => Post::class,
        ])->fresh();

        $this->assertCount(1, $user->bookmarks);
        $this->assertEquals($bookmark, $user->bookmarks()->first());
    }

    /** @test */
    public function it_cascades_if_the_bookmarkable_was_deleted()
    {
        $this->signIn();
        $post = Post::create()->bookmark();
        
        $this->assertDatabaseCount('bookmarks', 1);

        $post->delete();

        $this->assertDatabaseCount('bookmarks', 0);
    }

    /** @test */
    public function it_cascades_if_the_user_was_deleted()
    {
        $user = $this->signIn();
        Post::create()->bookmark();

        $this->assertDatabaseCount('bookmarks', 1);

        $user->delete();

        $this->assertDatabaseCount('bookmarks', 0);
    }
}

class User extends AuthUser
{
    use Bookmarker;

    protected $guarded = [];
}

class Post extends Model
{
    use Bookmarkable;

    protected $guarded = [];
}
