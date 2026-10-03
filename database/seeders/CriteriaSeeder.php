<?php

namespace Database\Seeders;

use App\Models\Criterion;
use App\Models\CriterionScale;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. K1: Anemia
        $k1 = Criterion::updateOrCreate(
            ['code' => 'K1'],
            [
                'name' => 'Anemia',
                'type' => 'benefit',
                'description' => 'Kadar Hemoglobin (Hb) ibu hamil untuk mendeteksi risiko anemia dalam kehamilan.',
            ]
        );

        $k1Scales = [
            ['parameter' => '≥ 11', 'score' => 1, 'label' => 'Normal', 'category' => 'Normal', 'min_value' => 11.0, 'max_value' => 99.0],
            ['parameter' => '10 - 10.9', 'score' => 2, 'label' => 'Anemia Ringan', 'category' => 'Rendah', 'min_value' => 10.0, 'max_value' => 10.9],
            ['parameter' => '7 - 9.9', 'score' => 3, 'label' => 'Anemia Sedang', 'category' => 'Sedang', 'min_value' => 7.0, 'max_value' => 9.9],
            ['parameter' => '< 7', 'score' => 4, 'label' => 'Anemia Berat', 'category' => 'Tinggi', 'min_value' => 0.0, 'max_value' => 6.99],
        ];

        foreach ($k1Scales as $scale) {
            CriterionScale::updateOrCreate(
                ['criterion_id' => $k1->id, 'parameter' => $scale['parameter']],
                $scale
            );
        }

        // 2. K2: IMT
        $k2 = Criterion::updateOrCreate(
            ['code' => 'K2'],
            [
                'name' => 'IMT',
                'type' => 'benefit',
                'description' => 'Indeks Massa Tubuh pra-kehamilan atau awal kehamilan.',
            ]
        );

        $k2Scales = [
            ['parameter' => '18.5 - 24.9', 'score' => 1, 'label' => 'IMT Normal', 'category' => 'Normal', 'min_value' => 18.5, 'max_value' => 24.9],
            ['parameter' => '25.0 - 29.9', 'score' => 2, 'label' => 'Berat Badan Berlebih', 'category' => 'Rendah', 'min_value' => 25.0, 'max_value' => 29.9],
            ['parameter' => '17.0 - 18.4', 'score' => 3, 'label' => 'Kurus', 'category' => 'Sedang', 'min_value' => 17.0, 'max_value' => 18.4],
            ['parameter' => '< 17.0 atau ≥ 30.0', 'score' => 4, 'label' => 'Sangat Kurus / Obesitas', 'category' => 'Tinggi', 'min_value' => null, 'max_value' => null],
        ];

        foreach ($k2Scales as $scale) {
            CriterionScale::updateOrCreate(
                ['criterion_id' => $k2->id, 'parameter' => $scale['parameter']],
                $scale
            );
        }

        // 3. K3: LILA
        $k3 = Criterion::updateOrCreate(
            ['code' => 'K3'],
            [
                'name' => 'LILA',
                'type' => 'benefit',
                'description' => 'Lingkar Lengan Atas untuk mendeteksi Kekurangan Energi Kronis (KEK).',
            ]
        );

        $k3Scales = [
            ['parameter' => '≥ 23', 'score' => 1, 'label' => 'Normal', 'category' => 'Normal', 'min_value' => 23.0, 'max_value' => 99.0],
            ['parameter' => '< 23', 'score' => 4, 'label' => 'KEK (Kekurangan Energi Kronis)', 'category' => 'Tinggi', 'min_value' => 0.0, 'max_value' => 22.99],
        ];

        foreach ($k3Scales as $scale) {
            CriterionScale::updateOrCreate(
                ['criterion_id' => $k3->id, 'parameter' => $scale['parameter']],
                $scale
            );
        }

        // 4. K4: Usia
        $k4 = Criterion::updateOrCreate(
            ['code' => 'K4'],
            [
                'name' => 'Usia',
                'type' => 'benefit',
                'description' => 'Usia ibu hamil berisiko tinggi (4T: Terlalu muda < 20 atau Terlalu tua > 35 tahun).',
            ]
        );

        $k4Scales = [
            ['parameter' => '20 - 35', 'score' => 1, 'label' => 'Usia Reproduksi Ideal', 'category' => 'Normal', 'min_value' => 20.0, 'max_value' => 35.0],
            ['parameter' => '< 20 atau > 35', 'score' => 4, 'label' => 'Usia Risiko Tinggi', 'category' => 'Tinggi', 'min_value' => null, 'max_value' => null],
        ];

        foreach ($k4Scales as $scale) {
            CriterionScale::updateOrCreate(
                ['criterion_id' => $k4->id, 'parameter' => $scale['parameter']],
                $scale
            );
        }

        // Default settings
        Setting::set('threshold_high', '0.80');
        Setting::set('threshold_medium', '0.60');
        Setting::set('nama_puskesmas', 'Puskesmas Kecamatan Taman Sari');
        Setting::set('desa_list', json_encode(['Taman Sari', 'Maphar', 'Tangki', 'Mangga Besar', 'Keagungan', 'Glodok', 'Pinangsia', 'Krukut']));
    }
}
