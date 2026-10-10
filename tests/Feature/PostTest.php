<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_feed(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'content' => 'Postingan pengujian feed',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Postingan pengujian feed');
    }

    public function test_authenticated_user_can_create_a_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/posts', [
            'content' => 'Halo dunia, ini status baru!',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'content' => 'Halo dunia, ini status baru!',
        ]);
    }

    public function test_authenticated_user_can_create_post_with_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('test_post.jpg');

        $response = $this->actingAs($user)->post('/posts', [
            'content' => 'Post dengan lampiran gambar',
            'media' => [$file],
        ]);

        $response->assertRedirect('/');
        $post = Post::with('media')->where('user_id', $user->id)->firstOrFail();
        $this->assertCount(1, $post->media);
        Storage::disk('public')->assertExists($post->media->first()->path);
    }

    public function test_user_can_publish_multiple_images_and_video_and_remove_them_with_post(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/posts', [
            'media' => [
                UploadedFile::fake()->image('one.jpg'),
                UploadedFile::fake()->image('two.png'),
                UploadedFile::fake()->create('clip.mp4', 512, 'video/mp4'),
            ],
        ]);

        $response->assertRedirect('/');
        $post = Post::with('media')->where('user_id', $user->id)->firstOrFail();
        $this->assertCount(3, $post->media);
        $this->assertSame(['image', 'image', 'video'], $post->media->pluck('type')->all());
        $paths = $post->media->pluck('path')->all();
        Storage::disk('public')->assertExists($paths);
        $this->actingAs($user)->get('/posts/'.$post->code)->assertOk()->assertSee('controls', false);

        $this->actingAs($user)->delete('/posts/'.$post->id)->assertRedirect('/');
        $this->assertDatabaseCount('post_media', 0);
        Storage::disk('public')->assertMissing($paths);
    }

    public function test_upload_rejects_invalid_file_and_more_than_ten_attachments(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->actingAs($user)->post('/posts', [
            'media' => [UploadedFile::fake()->create('script.php', 10, 'text/plain')],
        ])->assertSessionHasErrors('media.0');
        $this->actingAs($user)->post('/posts', [
            'media' => array_fill(0, 11, UploadedFile::fake()->image('picture.jpg')),
        ])->assertSessionHasErrors('media');
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_old_post_image_still_renders(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['image' => 'old-photo.jpg']);
        $this->actingAs($user)->get('/posts/'.$post->code)->assertOk()->assertSee('old-photo.jpg');
    }

    public function test_user_can_view_single_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'content' => 'Detail postingan tunggal',
        ]);

        $response = $this->actingAs($user)->get('/posts/' . $post->code);

        $response->assertStatus(200);
        $response->assertSee('Detail postingan tunggal');
    }

    public function test_author_can_delete_their_own_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete('/posts/' . $post->id);

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_user_cannot_delete_other_users_post(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user1->id,
        ]);

        $response = $this->actingAs($user2)->delete('/posts/' . $post->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_admin_can_delete_any_post(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($admin)->delete('/posts/' . $post->id);

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_user_can_search_posts(): void
    {
        $user = User::factory()->create();
        Post::factory()->create(['content' => 'Belajar Laravel Framework 13']);
        Post::factory()->create(['content' => 'Resep masakan nusantara']);

        $response = $this->actingAs($user)->get('/?q=Laravel');

        $response->assertStatus(200);
        $response->assertSee('Belajar Laravel Framework 13');
        $response->assertDontSee('Resep masakan nusantara');
    }
}
