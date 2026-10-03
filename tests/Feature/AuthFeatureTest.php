<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthFeatureTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login');
        $response->assertSee('Username');
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_user_can_authenticate_and_access_dashboard(): void
    {
        $user = User::where('username', 'bidan')->first();
        if (!$user) {
            $user = User::factory()->create([
                'username' => 'bidan_test',
                'role' => 'bidan',
            ]);
        }

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function test_non_admin_cannot_access_user_management(): void
    {
        $user = User::where('role', 'bidan')->first();
        if (!$user) {
            $user = User::factory()->create(['role' => 'bidan']);
        }

        $response = $this->actingAs($user)->get('/pengguna');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_user_management(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::factory()->create(['role' => 'admin']);
        }

        $response = $this->actingAs($admin)->get('/pengguna');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna');
    }
}
