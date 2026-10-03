<?php

namespace Tests\Feature;

use App\Models\AhpCalculation;
use App\Models\Criterion;
use App\Models\TopsisResult;
use App\Models\User;
use App\Services\AhpService;
use App\Services\TopsisService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AhpTopsisFeatureTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::first() ?? User::factory()->create(['role' => 'bidan']);
    }

    public function test_criteria_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get('/kriteria');
        $response->assertStatus(200);
        $response->assertSee('Skala Penilaian Kriteria');
    }

    public function test_ahp_comparison_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get('/perhitungan-bobot');
        $response->assertStatus(200);
        $response->assertSee('Perhitungan Analytical Hierarchy Process');
    }

    public function test_ahp_detail_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get('/perhitungan-bobot/detail');
        $response->assertStatus(200);
        $response->assertSee('Detail Perhitungan AHP');
    }

    public function test_topsis_calculation_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get('/perhitungan-topsis');
        $response->assertStatus(200);
        $response->assertSee('Perhitungan TOPSIS');
    }

    public function test_topsis_detail_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get('/perhitungan-topsis/detail');
        $response->assertStatus(200);
        $response->assertSee('Detail Perhitungan TOPSIS');
    }

    public function test_ranking_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get('/hasil-perangkingan');
        $response->assertStatus(200);
        $response->assertSee('Hasil Perangkingan Prioritas');
    }

    public function test_ranking_show_page_is_accessible(): void
    {
        $result = TopsisResult::first();
        if ($result) {
            $response = $this->actingAs($this->user)->get('/hasil-perangkingan/' . $result->id);
            $response->assertStatus(200);
            $response->assertSee('Skor TOPSIS');
            $response->assertSee('Nilai Kriteria');
        }
    }
}
