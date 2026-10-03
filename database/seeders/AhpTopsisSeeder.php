<?php

namespace Database\Seeders;

use App\Models\AhpCalculation;
use App\Models\AhpComparison;
use App\Models\AhpWeight;
use App\Models\Criterion;
use App\Models\User;
use App\Services\TopsisService;
use Illuminate\Database\Seeder;

class AhpTopsisSeeder extends Seeder
{
    public function run(): void
    {
        $k1 = Criterion::where('code', 'K1')->first();
        $k2 = Criterion::where('code', 'K2')->first();
        $k3 = Criterion::where('code', 'K3')->first();
        $k4 = Criterion::where('code', 'K4')->first();

        if (!$k1 || !$k2 || !$k3 || !$k4) {
            return;
        }

        $admin = User::first();

        // 1. Create Active AHP Calculation matching PDF Page 7 & 10
        $calc = AhpCalculation::create([
            'lambda_max' => 4.014,
            'ci' => 0.004,
            'cr' => 0.005,
            'is_consistent' => true,
            'is_active' => true,
            'created_by' => $admin ? $admin->id : null,
        ]);

        // Weights
        AhpWeight::create([
            'ahp_calculation_id' => $calc->id,
            'criterion_id' => $k1->id,
            'eigen_vector' => 0.606,
            'weight_percentage' => 60.6,
        ]);
        AhpWeight::create([
            'ahp_calculation_id' => $calc->id,
            'criterion_id' => $k2->id,
            'eigen_vector' => 0.171,
            'weight_percentage' => 17.1,
        ]);
        AhpWeight::create([
            'ahp_calculation_id' => $calc->id,
            'criterion_id' => $k3->id,
            'eigen_vector' => 0.171,
            'weight_percentage' => 17.1,
        ]);
        AhpWeight::create([
            'ahp_calculation_id' => $calc->id,
            'criterion_id' => $k4->id,
            'eigen_vector' => 0.056,
            'weight_percentage' => 5.6,
        ]);

        // Comparisons
        $pairs = [
            [$k1->id, $k2->id, 3.0],
            [$k1->id, $k3->id, 5.0],
            [$k1->id, $k4->id, 2.0],
            [$k2->id, $k3->id, 2.0],
            [$k2->id, $k4->id, 0.5],
            [$k3->id, $k4->id, 0.3333],
        ];

        foreach ($pairs as $p) {
            AhpComparison::create([
                'ahp_calculation_id' => $calc->id,
                'criterion1_id' => $p[0],
                'criterion2_id' => $p[1],
                'value' => $p[2],
            ]);
        }

        // Run initial TOPSIS calculation
        $topsisService = new TopsisService();
        $topsisResult = $topsisService->calculate($calc);
        $topsisService->saveResults($topsisResult);
    }
}
