<?php

namespace Tests\Feature;

use App\Models\IbuHamil;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class IbuHamilFeatureTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::first() ?? User::factory()->create(['role' => 'bidan']);
    }

    public function test_ibu_hamil_index_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get('/ibu-hamil');
        $response->assertStatus(200);
        $response->assertSee('Data Ibu Hamil');
    }

    public function test_ibu_hamil_create_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get('/ibu-hamil/create');
        $response->assertStatus(200);
        $response->assertSee('Tambah Ibu Hamil');
    }

    public function test_ibu_hamil_detail_page_is_accessible(): void
    {
        $ibu = IbuHamil::first();
        if (!$ibu) {
            $ibu = IbuHamil::create([
                'nama' => 'Test Ibu',
                'tanggal_lahir' => '1995-01-01',
                'hpht' => '2025-01-01',
                'hpl' => '2025-10-08',
                'usia_kehamilan_minggu' => 12,
                'status_kehamilan' => 'Trimester I',
                'status_anemia' => 'Normal',
                'imt' => 22.0,
                'lila' => 24.0,
            ]);
        }

        $response = $this->actingAs($this->user)->get('/ibu-hamil/' . $ibu->id);
        $response->assertStatus(200);
        $response->assertSee($ibu->nama);
        $response->assertSee('Informasi Pribadi');
    }

    public function test_ibu_hamil_export_downloads_csv(): void
    {
        if (IbuHamil::count() === 0) {
            IbuHamil::create([
                'kode_ibu_hamil' => 'P-2026-01',
                'nama' => 'Test Ibu',
                'tanggal_lahir' => '1995-01-01',
                'hpht' => '2025-01-01',
                'hpl' => '2025-10-08',
                'usia_kehamilan_minggu' => 12,
                'status_kehamilan' => 'Trimester I',
                'status_anemia' => 'Normal',
                'imt' => 22.0,
                'lila' => 24.0,
            ]);
        }

        $response = $this->actingAs($this->user)->get('/ibu-hamil-export');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
        $content = $response->streamedContent();
        $this->assertStringNotContainsString('Tinggi Badan', $content);
        $this->assertStringNotContainsString('Kadar Hb', $content);
        $this->assertStringContainsString('Status Anemia', $content);
        $this->assertStringContainsString('P-2026-01', $content);
    }

    public function test_ibu_hamil_template_downloads_csv(): void
    {
        $response = $this->actingAs($this->user)->get('/ibu-hamil-template');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
        $content = $response->streamedContent();
        $this->assertStringNotContainsString('Tinggi Badan', $content);
        $this->assertStringNotContainsString('Kadar Hb', $content);
        $this->assertStringContainsString('Status Anemia', $content);
        $this->assertStringContainsString('P-2026-01', $content);
    }

    public function test_ibu_hamil_can_be_created_via_livewire(): void
    {
        \Livewire\Livewire::actingAs($this->user)
            ->test(\App\Livewire\IbuHamil\Create::class)
            ->set('nama', 'Ibu Test Baru')
            ->set('tanggal_lahir', '1996-05-15')
            ->set('hpht', '2025-01-01')
            ->set('hpl', '2025-10-08')
            ->set('usia_kehamilan_minggu', 10)
            ->set('status_kehamilan', 'Trimester I')
            ->set('status_anemia', 'Sedang (8 - 9,9)')
            ->set('imt', 23.5)
            ->set('lila', 24.5)
            ->set('berat_badan_sebelum_hamil', 52.0)
            ->call('save')
            ->assertRedirect(route('ibu-hamil.index'));

        $this->assertDatabaseHas('ibu_hamil', [
            'nama' => 'Ibu Test Baru',
            'status_anemia' => 'Sedang (8 - 9,9)',
            'imt' => 23.5,
            'lila' => 24.5,
            'berat_badan_sebelum_hamil' => 52.0,
        ]);
    }

    public function test_import_user_csv_format(): void
    {
        $csvContent = "Kode Ibu Hamil;Nama Lengkap;Tanggal Lahir;Nomor Telepon;Desa/Kelurahan;HPHT;Status Anemia;Kadar Hb;IMT;LILA;Berat Badan Sebelum Hamil (kg)\n" .
                      "P-2026-01;Ibu Hamil 1;09/06/1992;0812345678910;Krandegan;04/04/2025;Normal (≥ 11);8.5;16.66;20;55\n" .
                      "P-2026-02;Ibu Hamil 2;15/03/2001;0812345678911;Cendana;04/04/2025;Normal (≥ 11);8.8;18.31;20;41\n";

        $tmpFile = tempnam(sys_get_temp_dir(), 'test_csv_');
        file_put_contents($tmpFile, $csvContent);

        $excelService = new \App\Services\ExcelService();
        $result = $excelService->importIbuHamil($tmpFile);
        unlink($tmpFile);

        $this->assertTrue($result['success']);
        $this->assertEquals(2, $result['count']);
        $this->assertDatabaseHas('ibu_hamil', [
            'kode_ibu_hamil' => 'P-2026-01',
            'nama' => 'Ibu Hamil 1',
            'tanggal_lahir' => '1992-06-09',
            'hpht' => '2025-04-04',
            'kadar_hb' => 8.5,
            'imt' => 16.66,
            'lila' => 20.0,
            'berat_badan_sebelum_hamil' => 55.0,
        ]);
    }
}
