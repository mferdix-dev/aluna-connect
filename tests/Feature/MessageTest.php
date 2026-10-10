<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_messages_inbox(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Message::create([
            'sender_id' => $user2->id,
            'receiver_id' => $user1->id,
            'content' => 'Halo dari user 2!',
        ]);

        $response = $this->actingAs($user1)->get('/messages');

        $response->assertOk()->assertSee('id="message-panel"', false);
        $response->assertSee($user2->name);
    }

    public function test_user_can_view_chat_thread_with_another_user(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Message::create([
            'sender_id' => $user2->id,
            'receiver_id' => $user1->id,
            'content' => 'Pesan pengujian chat',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user1)->get('/messages/' . $user2->username);

        $response->assertStatus(200);
        $response->assertSee('Pesan pengujian chat');
        $this->assertDatabaseHas('messages', [
            'sender_id' => $user2->id,
            'receiver_id' => $user1->id,
            'is_read' => true,
        ]);
    }

    public function test_chat_popup_is_available_on_other_authenticated_pages(): void
    {
        $user = User::factory()->create();
        $partner = User::factory()->create();

        foreach (['/', '/settings', '/u/'.$partner->username, '/notifications'] as $url) {
            $this->actingAs($user)->get($url)
                ->assertOk()
                ->assertSee('id="chat-widget"', false)
                ->assertSee('onclick="openChat()"', false);
        }

        $this->actingAs($user)->get('/messages/'.$partner->username.'?panel=1')
            ->assertOk()
            ->assertSee('id="message-panel"', false)
            ->assertSee('href="'.route('messages.index').'?panel=1"', false)
            ->assertDontSee('id="chat-widget"', false);
    }

    public function test_guests_cannot_open_chat_panel(): void
    {
        $partner = User::factory()->create();
        $this->get('/messages?panel=1')->assertRedirect('/login');
        $this->get('/messages/'.$partner->username.'?panel=1')->assertRedirect('/login');
        $this->get('/login')->assertOk()->assertDontSee('id="chat-widget"', false);
        $this->get('/register')->assertOk()->assertDontSee('id="chat-widget"', false);
    }

    public function test_user_can_send_a_message(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user1)->post('/messages/' . $user2->username, [
            'content' => 'Hai user 2, ini pesan baru!',
        ]);

        $response->assertRedirect('/messages/' . $user2->username);
        $this->assertDatabaseHas('messages', [
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
            'content' => 'Hai user 2, ini pesan baru!',
        ]);

        // Recipient receives notification
        $this->assertDatabaseHas('social_notifications', [
            'user_id' => $user2->id,
            'actor_id' => $user1->id,
            'type' => 'message',
        ]);
    }

    public function test_user_can_poll_new_messages_via_json(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $msg = Message::create([
            'sender_id' => $user2->id,
            'receiver_id' => $user1->id,
            'content' => 'Pesan polling real-time',
        ]);

        $response = $this->actingAs($user1)->getJson('/messages/' . $user2->username . '/poll?after_id=' . ($msg->id - 1));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonFragment([
            'content' => 'Pesan polling real-time',
        ]);
    }
}
