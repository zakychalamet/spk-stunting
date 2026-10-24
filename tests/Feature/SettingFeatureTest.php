<?php

namespace Tests\Feature;

use App\Livewire\Setting\Index;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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
        $response->assertSee('Pengaturan Sistem');
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
}
