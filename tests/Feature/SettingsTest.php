<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_requires_login(): void
    {
        $this->get('/settings')->assertRedirect('/login');
    }

    public function test_settings_offers_auto_light_and_dark_theme_without_header_toggle(): void
    {
        $this->actingAs(User::factory()->create())->get('/settings')
            ->assertOk()
            ->assertSee('Pengaturan Aplikasi')
            ->assertSee('value="auto"', false)
            ->assertSee('value="light"', false)
            ->assertSee('value="dark"', false)
            ->assertDontSee('onclick="toggleTheme()"', false)
            ->assertDontSee('onclick="cycleThemeMode()"', false);
    }
}
