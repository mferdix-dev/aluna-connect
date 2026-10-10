<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_comment_to_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post("/posts/{$post->code}/comments", [
            'content' => 'Ini komentar pertama saya!',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'Ini komentar pertama saya!',
        ]);
    }

    public function test_comment_author_can_delete_their_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'Komentar untuk dihapus',
        ]);

        $response = $this->actingAs($user)->delete("/comments/{$comment->id}");

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_user_cannot_delete_other_users_comment(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user1->id,
            'content' => 'Komentar user 1',
        ]);

        $response = $this->actingAs($user2)->delete("/comments/{$comment->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }

    public function test_admin_can_delete_any_comment(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'Komentar spam',
        ]);

        $response = $this->actingAs($admin)->delete("/comments/{$comment->id}");

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
