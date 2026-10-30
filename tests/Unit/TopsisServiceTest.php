<?php

namespace Tests\Unit;

use App\Models\AhpCalculation;
use App\Models\IbuHamil;
use App\Models\TopsisResult;
use App\Services\TopsisService;
use Tests\TestCase;

class TopsisServiceTest extends TestCase
{
    public function test_topsis_calculation_executes_successfully(): void
    {
        $calc = AhpCalculation::where('is_active', true)->first();
        $this->assertNotNull($calc);

        $topsis = new TopsisService();
        $res = $topsis->calculate($calc);

        $this->assertTrue($res['hasData']);
        $this->assertNotEmpty($res['results']);
        $this->assertEquals(1, $res['results'][0]['rank']);
        $this->assertGreaterThan(0, $res['results'][0]['preference_score']);
    }

    public function test_recommendation_for_age_under_20_is_anc_more_frequent(): void
    {
        $calc = AhpCalculation::where('is_active', true)->first();
        $this->assertNotNull($calc);

        $dummy = new IbuHamil([
            'nama' => 'Ibu Remaja',
            'tanggal_lahir' => now()->subYears(18),
            'kadar_hb' => 12.0,
            'imt' => 21.0,
            'lila' => 24.0,
        ]);

        $topsis = new TopsisService();
        $res = $topsis->calculate($calc, new \Illuminate\Database\Eloquent\Collection([$dummy]));

        $this->assertStringContainsString('Kunjungan ANC lebih sering', $res['results'][0]['recommendation']);
    }

    public function test_recommendation_for_age_over_35_is_blood_pressure_and_sugar_screening(): void
    {
        $calc = AhpCalculation::where('is_active', true)->first();
        $this->assertNotNull($calc);

        $dummy = new IbuHamil([
            'nama' => 'Ibu Risiko Usia Lanjut',
            'tanggal_lahir' => now()->subYears(37),
            'kadar_hb' => 12.0,
            'imt' => 21.0,
            'lila' => 24.0,
        ]);

        $topsis = new TopsisService();
        $res = $topsis->calculate($calc, new \Illuminate\Database\Eloquent\Collection([$dummy]));

        $this->assertStringContainsString('Skrining tekanan darah dan gula darah', $res['results'][0]['recommendation']);
    }
}
