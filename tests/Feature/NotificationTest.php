<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\SocialNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_liking_post_creates_notification_for_author(): void
    {
        $author = User::factory()->create();
        $liker = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $author->id]);

        $this->actingAs($liker)->postJson("/posts/{$post->code}/like");

        $this->assertDatabaseHas('social_notifications', [
            'user_id' => $author->id,
            'actor_id' => $liker->id,
            'type' => 'like',
        ]);
    }

    public function test_commenting_post_creates_notification_for_author(): void
    {
        $author = User::factory()->create();
        $commenter = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $author->id]);

        $this->actingAs($commenter)->post("/posts/{$post->code}/comments", [
            'content' => 'Komentar pengujian notifikasi',
        ]);

        $this->assertDatabaseHas('social_notifications', [
            'user_id' => $author->id,
            'actor_id' => $commenter->id,
            'type' => 'comment',
        ]);
    }

    public function test_user_can_view_notifications_page(): void
    {
        $user = User::factory()->create();
        $actor = User::factory()->create();

        SocialNotification::create([
            'user_id' => $user->id,
            'actor_id' => $actor->id,
            'type' => 'like',
            'title' => 'Suka Baru',
            'message' => 'Seseorang menyukai post Anda',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->get('/notifications');

        $response->assertStatus(200);
        $response->assertSee('Seseorang menyukai post Anda');

        // Marked as read
        $this->assertDatabaseHas('social_notifications', [
            'user_id' => $user->id,
            'is_read' => true,
        ]);
    }

    public function test_user_can_fetch_unread_counts_api(): void
    {
        $user = User::factory()->create();
        $actor = User::factory()->create();

        SocialNotification::create([
            'user_id' => $user->id,
            'actor_id' => $actor->id,
            'type' => 'like',
            'title' => 'Suka Baru',
            'message' => 'Pemberitahuan like',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->getJson('/api/notifications/unread-count');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'notifications_count' => 1,
        ]);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $user = User::factory()->create();
        $actor = User::factory()->create();

        SocialNotification::create([
            'user_id' => $user->id,
            'actor_id' => $actor->id,
            'type' => 'like',
            'title' => 'Suka Baru',
            'message' => 'Pemberitahuan like',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->post('/notifications/mark-all-read');

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('social_notifications', [
            'user_id' => $user->id,
            'is_read' => true,
        ]);
    }
}
