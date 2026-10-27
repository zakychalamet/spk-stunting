<?php

namespace Tests\Feature;

use App\Livewire\Setting\Index;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class SettingFeatureTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::first() ?? User::factory()->create(['role' => 'admin']);
    }

    public function test_setting_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get('/pengaturan');
        $response->assertStatus(200);
        $response->assertSee('Identitas Layanan Kesehatan');
        $response->assertSee('Ambang Batas Kategori Prioritas TOPSIS');
        $response->assertSee('Simpan Perubahan');
    }

    public function test_save_setting_triggers_notification(): void
    {
        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('namaPuskesmas', 'Puskesmas Binaan Test')
            ->set('thresholdHigh', 0.85)
            ->set('thresholdMedium', 0.65)
            ->set('desaListText', 'Desa Satu, Desa Dua')
            ->call('save')
            ->assertSet('notification.type', 'success')
            ->assertSet('notification.title', 'Pengaturan Berhasil Disimpan!')
            ->assertSee('Pengaturan Berhasil Disimpan!')
            ->assertSee('Perubahan identitas layanan kesehatan dan ambang batas prioritas TOPSIS telah berhasil disimpan.')
            ->call('dismissNotification')
            ->assertSet('notification', null);

        $this->assertEquals('Puskesmas Binaan Test', Setting::get('nama_puskesmas'));
        $this->assertEquals('0.85', Setting::get('threshold_high'));
        $this->assertEquals('0.65', Setting::get('threshold_medium'));
    }

    public function test_user_can_update_profile_and_biodata(): void
    {
        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('name', 'Nama Admin Baru')
            ->set('phone', '081299998888')
            ->call('saveProfile')
            ->assertSet('notification.type', 'success')
            ->assertSet('notification.title', 'Biodata Berhasil Disimpan!');

        $this->user->refresh();
        $this->assertEquals('Nama Admin Baru', $this->user->name);
        $this->assertEquals('081299998888', $this->user->phone);
    }

    public function test_user_can_change_password_on_password_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('setting.password'));
        $response->assertStatus(200);
        $response->assertSee('Ubah Kata Sandi', false);

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\Setting\Password::class)
            ->set('currentPassword', 'password')
            ->set('newPassword', 'passwordbaru123')
            ->set('newPasswordConfirmation', 'passwordbaru123')
            ->call('updatePassword')
            ->assertSet('notification.type', 'success');

        $this->user->refresh();
        $this->assertTrue(Hash::check('passwordbaru123', $this->user->password));
    }

    public function test_non_admin_cannot_see_or_save_system_settings(): void
    {
        $bidan = User::where('role', 'bidan')->first() ?? User::factory()->create(['role' => 'bidan']);

        $response = $this->actingAs($bidan)->get('/pengaturan');
        $response->assertStatus(200);
        $response->assertSee('Biodata &amp; Foto Profil', false);
        $response->assertDontSee('Identitas Layanan Kesehatan', false);
        $response->assertDontSee('Ambang Batas Kategori Prioritas TOPSIS', false);

        Livewire::actingAs($bidan)
            ->test(Index::class)
            ->call('saveSystem')
            ->assertStatus(403);
    }
}
