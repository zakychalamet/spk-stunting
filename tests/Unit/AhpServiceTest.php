<?php

namespace Tests\Unit;

use App\Services\AhpService;
use PHPUnit\Framework\TestCase;

class AhpServiceTest extends TestCase
{
    public function test_ahp_calculation_matches_expected_mathematics(): void
    {
        $ahp = new AhpService();

        // 4 criteria matrix from PDF Page 7
        // Rows: Anemia, IMT, LILA, Usia
        $matrix = [
            1 => [1 => 1.0, 2 => 3.0, 3 => 5.0, 4 => 2.0],
            2 => [1 => 1/3, 2 => 1.0, 3 => 2.0, 4 => 0.5],
            3 => [1 => 1/5, 2 => 0.5, 3 => 1.0, 4 => 1/3],
            4 => [1 => 0.5, 2 => 2.0, 3 => 3.0, 4 => 1.0],
        ];

        $criteria = [1, 2, 3, 4];
        $result = $ahp->calculate($matrix, $criteria);

        $this->assertEquals(4, $result['n']);
        $this->assertGreaterThan(0.40, $result['weights'][1]); // Anemia is highest priority
        $this->assertLessThanOrEqual(0.10, $result['cr']); // Consistent
        $this->assertTrue($result['isConsistent']);
    }
}
