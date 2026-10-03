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
        $response = $this->actingAs($this->user)->get('/ibu-hamil-export');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
    }

    public function test_ibu_hamil_template_downloads_csv(): void
    {
        $response = $this->actingAs($this->user)->get('/ibu-hamil-template');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
    }
}
