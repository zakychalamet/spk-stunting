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
}
