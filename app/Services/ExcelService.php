<?php

namespace App\Services;

use App\Models\IbuHamil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelService
{
    /**
     * Export Ibu Hamil list to CSV (Excel compatible with UTF-8 BOM)
     */
    public function exportIbuHamil(?Collection $items = null): StreamedResponse
    {
        if (!$items) {
            $items = IbuHamil::orderBy('id', 'asc')->get();
        }

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Data_Ibu_Hamil_' . date('Ymd_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = [
            'Kode Ibu Hamil',
            'Nama Lengkap',
            'Tanggal Lahir',
            'Nomor Telepon',
            'Desa/Kelurahan',
            'HPHT',
            'HPL',
            'Usia Kehamilan (Minggu)',
            'Status Kehamilan',
            'Status Anemia',
            'Kadar Hb',
            'IMT',
            'LILA',
            'Berat Badan Sebelum Hamil (kg)',
            'Tinggi Badan (cm)',
        ];

        return response()->stream(function () use ($columns, $items) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM for Microsoft Excel
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns, ';');

            foreach ($items as $row) {
                fputcsv($file, [
                    $row->kode_ibu_hamil,
                    $row->nama,
                    $row->tanggal_lahir ? $row->tanggal_lahir->format('Y-m-d') : '',
                    $row->nomor_telepon,
                    $row->desa_kelurahan,
                    $row->hpht ? $row->hpht->format('Y-m-d') : '',
                    $row->hpl ? $row->hpl->format('Y-m-d') : '',
                    $row->usia_kehamilan_minggu,
                    $row->status_kehamilan,
                    $row->status_anemia,
                    $row->kadar_hb,
                    $row->imt,
                    $row->lila,
                    $row->berat_badan_sebelum_hamil,
                    $row->tinggi_badan,
                ], ';');
            }

            fclose($file);
        }, 200, $headers);
    }

    /**
     * Export CSV Template for Import
     */
    public function exportTemplate(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Template_Import_Ibu_Hamil.csv"',
        ];

        $columns = [
            'Kode Ibu Hamil',
            'Nama Lengkap',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Nomor Telepon',
            'Desa/Kelurahan',
            'HPHT (YYYY-MM-DD)',
            'HPL (YYYY-MM-DD)',
            'Usia Kehamilan (Minggu)',
            'Status Kehamilan',
            'Status Anemia',
            'Kadar Hb',
            'IMT',
            'LILA',
            'Berat Badan Sebelum Hamil (kg)',
            'Tinggi Badan (cm)',
        ];

        return response()->stream(function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns, ';');

            // Example row
            fputcsv($file, [
                'P-2025-001',
                'Contoh Nama Pasien',
                '1995-05-15',
                '081234567890',
                'Taman Sari',
                '2025-01-10',
                '2025-10-17',
                '12',
                'Trimester I',
                'Anemia Ringan',
                '10.2',
                '21.5',
                '23.5',
                '50.0',
                '155.0',
            ], ';');

            fclose($file);
        }, 200, $headers);
    }

    /**
     * Import Ibu Hamil from uploaded file
     */
    public function importIbuHamil(string $realPath): array
    {
        $file = fopen($realPath, 'r');
        if (!$file) {
            return ['success' => false, 'message' => 'Gagal membuka file.'];
        }

        // Detect delimiter: check first line
        $firstLine = fgets($file);
        $delimiter = str_contains($firstLine, ';') ? ';' : ',';
        rewind($file);

        // Discard BOM if present
        $bom = fread($file, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($file);
        }

        $header = fgetcsv($file, 0, $delimiter);
        if (!$header) {
            fclose($file);
            return ['success' => false, 'message' => 'File kosong atau format salah.'];
        }

        $countSuccess = 0;
        $errors = [];
        $rowNumber = 1;

        while (($row = fgetcsv($file, 0, $delimiter)) !== false) {
            $rowNumber++;
            if (empty(array_filter($row))) {
                continue; // Skip blank lines
            }

            $nama = trim($row[1] ?? '');
            if (empty($nama)) {
                $errors[] = "Baris $rowNumber: Nama lengkap wajib diisi.";
                continue;
            }

            try {
                $kode = !empty(trim($row[0] ?? '')) ? trim($row[0]) : null;
                $tglLahir = !empty(trim($row[2] ?? '')) ? Carbon::parse(trim($row[2]))->format('Y-m-d') : null;
                $hpht = !empty(trim($row[5] ?? '')) ? Carbon::parse(trim($row[5]))->format('Y-m-d') : null;
                $hpl = !empty(trim($row[6] ?? '')) ? Carbon::parse(trim($row[6]))->format('Y-m-d') : ($hpht ? Carbon::parse($hpht)->addDays(280)->format('Y-m-d') : null);

                if (!$tglLahir || !$hpht) {
                    $errors[] = "Baris $rowNumber: Format tanggal lahir atau HPHT tidak valid.";
                    continue;
                }

                $data = [
                    'nama' => $nama,
                    'tanggal_lahir' => $tglLahir,
                    'nomor_telepon' => trim($row[3] ?? ''),
                    'desa_kelurahan' => trim($row[4] ?? ''),
                    'hpht' => $hpht,
                    'hpl' => $hpl,
                    'usia_kehamilan_minggu' => (int) ($row[7] ?? 0),
                    'status_kehamilan' => trim($row[8] ?? 'Trimester I'),
                    'status_anemia' => trim($row[9] ?? 'Normal'),
                    'kadar_hb' => !empty($row[10]) ? (float) str_replace(',', '.', $row[10]) : null,
                    'imt' => !empty($row[11]) ? (float) str_replace(',', '.', $row[11]) : 0,
                    'lila' => !empty($row[12]) ? (float) str_replace(',', '.', $row[12]) : 0,
                    'berat_badan_sebelum_hamil' => !empty($row[13]) ? (float) str_replace(',', '.', $row[13]) : null,
                    'tinggi_badan' => !empty($row[14]) ? (float) str_replace(',', '.', $row[14]) : null,
                ];

                if ($kode) {
                    IbuHamil::updateOrCreate(['kode_ibu_hamil' => $kode], $data);
                } else {
                    IbuHamil::create($data);
                }

                $countSuccess++;
            } catch (\Throwable $e) {
                $errors[] = "Baris $rowNumber: Error - " . $e->getMessage();
            }
        }

        fclose($file);

        return [
            'success' => true,
            'count' => $countSuccess,
            'errors' => $errors,
        ];
    }
}
