<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_any_public_profile(): void
    {
        $viewer = User::factory()->create();
        $target = User::factory()->create([
            'name' => 'Budi Santoso',
            'username' => 'budisantoso',
            'city' => 'Surabaya',
        ]);

        $response = $this->actingAs($viewer)->get('/u/budisantoso');

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Surabaya');
    }

    public function test_user_can_view_edit_profile_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Profil Saya');
    }

    public function test_user_can_update_their_profile_info(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'city' => 'Kota Lama',
        ]);

        $response = $this->actingAs($user)->put('/profile/edit', [
            'name' => 'Nama Baru',
            'city' => 'Bandung, Indonesia',
            'hobby' => 'Design & Fotografi',
        ]);

        $response->assertRedirect('/u/' . $user->username);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru',
            'city' => 'Bandung, Indonesia',
            'hobby' => 'Design & Fotografi',
        ]);
    }
}
