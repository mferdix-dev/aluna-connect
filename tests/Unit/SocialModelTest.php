<?php

namespace Tests\Unit;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_admin_check(): void
    {
        $admin = new User(['role' => 'admin']);
        $user = new User(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    public function test_user_avatar_url_fallback(): void
    {
        $user = new User([
            'name' => 'Ahmad Dani',
            'avatar' => null,
        ]);

        $this->assertStringContainsString('ui-avatars.com', $user->avatar_url);
        $this->assertStringContainsString('Ahmad+Dani', $user->avatar_url);
    }

    public function test_post_generates_code_automatically(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'user_id' => $user->id,
            'content' => 'Hello World',
        ]);

        $this->assertNotEmpty($post->code);
        $this->assertSame(20, strlen($post->code));
    }

    public function test_post_is_liked_by_user(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $post = Post::factory()->create();

        Like::create([
            'post_id' => $post->id,
            'user_id' => $user1->id,
        ]);

        $this->assertTrue($post->isLikedBy($user1));
        $this->assertFalse($post->isLikedBy($user2));
    }
}
