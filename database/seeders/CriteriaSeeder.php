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
            ['parameter' => 'Normal (≥ 11)', 'score' => 1, 'label' => 'Normal', 'category' => 'Normal', 'min_value' => 11.0, 'max_value' => 99.0],
            ['parameter' => 'Ringan (10 - 10,9)', 'score' => 2, 'label' => 'Anemia Ringan', 'category' => 'Rendah', 'min_value' => 10.0, 'max_value' => 10.9],
            ['parameter' => 'Sedang (8 - 9,9)', 'score' => 3, 'label' => 'Anemia Sedang', 'category' => 'Sedang', 'min_value' => 8.0, 'max_value' => 9.9],
            ['parameter' => 'Berat (< 8)', 'score' => 4, 'label' => 'Anemia Berat', 'category' => 'Tinggi', 'min_value' => 0.0, 'max_value' => 7.99],
        ];

        foreach ($k1Scales as $scale) {
            CriterionScale::updateOrCreate(
                ['criterion_id' => $k1->id, 'parameter' => $scale['parameter']],
                $scale
            );
        }
        CriterionScale::where('criterion_id', $k1->id)->whereNotIn('parameter', array_column($k1Scales, 'parameter'))->delete();

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
            ['parameter' => '18.5 - 24.9', 'score' => 1, 'label' => 'Normal', 'category' => 'Normal', 'min_value' => 18.5, 'max_value' => 24.9],
            ['parameter' => '25.0 - 29.9', 'score' => 2, 'label' => 'Berat Badan Berlebih', 'category' => 'Rendah', 'min_value' => 25.0, 'max_value' => 29.9],
            ['parameter' => '≥ 30', 'score' => 3, 'label' => 'Obesitas', 'category' => 'Sedang', 'min_value' => 30.0, 'max_value' => 99.0],
            ['parameter' => '< 18.5', 'score' => 4, 'label' => 'Kurus', 'category' => 'Tinggi', 'min_value' => 0.0, 'max_value' => 18.49],
        ];

        foreach ($k2Scales as $scale) {
            CriterionScale::updateOrCreate(
                ['criterion_id' => $k2->id, 'parameter' => $scale['parameter']],
                $scale
            );
        }
        CriterionScale::where('criterion_id', $k2->id)->whereNotIn('parameter', array_column($k2Scales, 'parameter'))->delete();

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
            ['parameter' => '≥ 23.5', 'score' => 1, 'label' => 'Normal', 'category' => 'Normal', 'min_value' => 23.5, 'max_value' => 99.0],
            ['parameter' => '< 23.5', 'score' => 4, 'label' => 'KEK (Kekurangan Energi Kronis)', 'category' => 'Tinggi', 'min_value' => 0.0, 'max_value' => 23.49],
        ];

        foreach ($k3Scales as $scale) {
            CriterionScale::updateOrCreate(
                ['criterion_id' => $k3->id, 'parameter' => $scale['parameter']],
                $scale
            );
        }
        CriterionScale::where('criterion_id', $k3->id)->whereNotIn('parameter', array_column($k3Scales, 'parameter'))->delete();

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
        CriterionScale::where('criterion_id', $k4->id)->whereNotIn('parameter', array_column($k4Scales, 'parameter'))->delete();

        // Default settings
        Setting::set('threshold_high', '0.80');
        Setting::set('threshold_medium', '0.60');
        Setting::set('nama_puskesmas', 'Puskesmas Kecamatan Taman Sari');
        Setting::set('desa_list', json_encode(['Parakancanggah', 'Cendana', 'Krandegan', 'Semarang', 'Sokayasa', 'Sokanandi']));
    }
}
