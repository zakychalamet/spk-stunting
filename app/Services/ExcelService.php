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
            'IMT',
            'LILA',
            'Berat Badan Sebelum Hamil (kg)',
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
                    $row->imt,
                    $row->lila,
                    $row->berat_badan_sebelum_hamil,
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
            'IMT',
            'LILA',
            'Berat Badan Sebelum Hamil (kg)',
        ];

        return response()->stream(function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns, ';');

            // Example row
            fputcsv($file, [
                'P-2026-01',
                'Contoh Nama Pasien',
                '1995-05-15',
                '081234567890',
                'Parakancanggah',
                '2026-01-10',
                '2026-10-17',
                '12',
                'Trimester I',
                'Anemia Ringan',
                '21.5',
                '23.5',
                '50.0',
            ], ';');

            fclose($file);
        }, 200, $headers);
    }

    /**
     * Import Ibu Hamil from uploaded file
     */
    /**
     * Parse date string supporting DD/MM/YYYY, DD-MM-YYYY, YYYY-MM-DD, etc.
     */
    public function parseDate(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }
        $value = trim($value);

        // Format DD/MM/YYYY or DD-MM-YYYY
        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $value, $m)) {
            $day = (int) $m[1];
            $month = (int) $m[2];
            $year = (int) $m[3];
            if (checkdate($month, $day, $year)) {
                return sprintf('%04d-%02d-%02d', $year, $month, $day);
            }
        }

        // Format YYYY-MM-DD or YYYY/MM/DD
        if (preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $value, $m)) {
            $year = (int) $m[1];
            $month = (int) $m[2];
            $day = (int) $m[3];
            if (checkdate($month, $day, $year)) {
                return sprintf('%04d-%02d-%02d', $year, $month, $day);
            }
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
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

        // Sanitize header encoding to valid UTF-8
        $header = array_map(function ($col) {
            return mb_convert_encoding((string) $col, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
        }, $header);

        // Map column headers dynamically
        $map = [];
        foreach ($header as $idx => $col) {
            $colLower = strtolower(trim($col));
            $colClean = preg_replace('/[^a-z0-9]/', '', $colLower);

            if (str_contains($colClean, 'hpht')) {
                $map['hpht'] = $idx;
            } elseif (str_contains($colClean, 'hpl')) {
                $map['hpl'] = $idx;
            } elseif (str_contains($colClean, 'usiakehamilan') || str_contains($colClean, 'minggukehamilan')) {
                $map['usia_kehamilan_minggu'] = $idx;
            } elseif (str_contains($colClean, 'statuskehamilan') || str_contains($colClean, 'trimester')) {
                $map['status_kehamilan'] = $idx;
            } elseif (str_contains($colClean, 'kode') || $colClean === 'id' || str_contains($colClean, 'kodeibu')) {
                $map['kode'] = $idx;
            } elseif (str_contains($colClean, 'nama')) {
                $map['nama'] = $idx;
            } elseif (str_contains($colClean, 'tanggallahir') || str_contains($colClean, 'tgllahir') || str_contains($colClean, 'lahir')) {
                $map['tanggal_lahir'] = $idx;
            } elseif (str_contains($colClean, 'telepon') || str_contains($colClean, 'telp') || str_contains($colClean, 'kontak') || str_contains($colClean, 'wa') || $colClean === 'hp' || str_contains($colClean, 'nohp')) {
                $map['nomor_telepon'] = $idx;
            } elseif (str_contains($colClean, 'desa') || str_contains($colClean, 'kelurahan') || str_contains($colClean, 'alamat')) {
                $map['desa_kelurahan'] = $idx;
            } elseif (str_contains($colClean, 'anemia')) {
                $map['status_anemia'] = $idx;
            } elseif (str_contains($colClean, 'kadarhb') || $colClean === 'hb' || str_contains($colClean, 'hemoglobin')) {
                $map['kadar_hb'] = $idx;
            } elseif (str_contains($colClean, 'imt') || str_contains($colClean, 'bmi')) {
                $map['imt'] = $idx;
            } elseif (str_contains($colClean, 'lila')) {
                $map['lila'] = $idx;
            } elseif (str_contains($colClean, 'berat') || str_contains($colClean, 'bb')) {
                $map['berat_badan_sebelum_hamil'] = $idx;
            } elseif (str_contains($colClean, 'tinggi') || str_contains($colClean, 'tb')) {
                $map['tinggi_badan'] = $idx;
            }
        }

        // Fallback default index positions if no headers matched
        if (!isset($map['nama'])) {
            $map['kode'] = 0;
            $map['nama'] = 1;
            $map['tanggal_lahir'] = 2;
            $map['nomor_telepon'] = 3;
            $map['desa_kelurahan'] = 4;
            $map['hpht'] = 5;
            $map['status_anemia'] = 6;
            $map['kadar_hb'] = 7;
            $map['imt'] = 8;
            $map['lila'] = 9;
            $map['berat_badan_sebelum_hamil'] = 10;
        }

        // Strict header validation: must have mandatory columns
        if (!isset($map['nama']) || (!isset($map['tanggal_lahir']) && !isset($map['hpht']))) {
            fclose($file);
            return [
                'success' => false,
                'message' => 'Format kolom file tidak sesuai dengan template. Pastikan file memuat kolom: Nama Lengkap, Tanggal Lahir, HPHT, Status Anemia, IMT, dan LILA.',
            ];
        }

        $countSuccess = 0;
        $errors = [];
        $rowNumber = 1;

        while (($row = fgetcsv($file, 0, $delimiter)) !== false) {
            $rowNumber++;
            if (empty(array_filter($row))) {
                continue; // Skip blank lines
            }

            // Convert row encoding to valid UTF-8
            $row = array_map(function ($val) {
                return mb_convert_encoding((string) $val, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
            }, $row);

            $nama = trim($row[$map['nama'] ?? 1] ?? '');
            if (empty($nama)) {
                $errors[] = "Baris $rowNumber: Nama lengkap wajib diisi.";
                continue;
            }

            try {
                $kode = isset($map['kode']) && !empty(trim($row[$map['kode']] ?? '')) ? trim($row[$map['kode']]) : null;
                $tglLahirRaw = isset($map['tanggal_lahir']) ? trim($row[$map['tanggal_lahir']] ?? '') : '';
                $hphtRaw = isset($map['hpht']) ? trim($row[$map['hpht']] ?? '') : '';

                $tglLahir = $this->parseDate($tglLahirRaw);
                $hpht = $this->parseDate($hphtRaw);

                if (!$tglLahir || !$hpht) {
                    $errors[] = "Baris $rowNumber: Format tanggal lahir atau HPHT tidak valid ('$tglLahirRaw', '$hphtRaw').";
                    continue;
                }

                // HPL: from column or calculate HPHT + 280 days
                $hplRaw = isset($map['hpl']) ? trim($row[$map['hpl']] ?? '') : '';
                $hpl = $this->parseDate($hplRaw) ?: Carbon::parse($hpht)->addDays(280)->format('Y-m-d');

                // Usia Kehamilan (Minggu)
                if (isset($map['usia_kehamilan_minggu']) && is_numeric(trim($row[$map['usia_kehamilan_minggu']] ?? ''))) {
                    $usiaMinggu = (int) trim($row[$map['usia_kehamilan_minggu']]);
                } else {
                    $usiaMinggu = max(1, (int) Carbon::parse($hpht)->diffInWeeks(now()));
                }

                // Status Kehamilan
                if (isset($map['status_kehamilan']) && !empty(trim($row[$map['status_kehamilan']] ?? ''))) {
                    $statusKehamilan = trim($row[$map['status_kehamilan']]);
                } else {
                    if ($usiaMinggu <= 12) {
                        $statusKehamilan = 'Trimester I';
                    } elseif ($usiaMinggu <= 27) {
                        $statusKehamilan = 'Trimester II';
                    } else {
                        $statusKehamilan = 'Trimester III';
                    }
                }

                // Status Anemia
                $statusAnemia = isset($map['status_anemia']) ? trim($row[$map['status_anemia']] ?? '') : 'Normal (≥ 11)';
                if (empty($statusAnemia)) {
                    $statusAnemia = 'Normal (≥ 11)';
                }

                // Kadar Hb
                $kadarHb = isset($map['kadar_hb']) && !empty($row[$map['kadar_hb']]) 
                    ? (float) str_replace(',', '.', trim($row[$map['kadar_hb']])) 
                    : null;

                // IMT
                $imt = isset($map['imt']) && !empty($row[$map['imt']]) 
                    ? (float) str_replace(',', '.', trim($row[$map['imt']])) 
                    : 0;

                // LILA
                $lila = isset($map['lila']) && !empty($row[$map['lila']]) 
                    ? (float) str_replace(',', '.', trim($row[$map['lila']])) 
                    : 0;

                // Berat Badan Sebelum Hamil
                $bb = isset($map['berat_badan_sebelum_hamil']) && !empty($row[$map['berat_badan_sebelum_hamil']]) 
                    ? (float) str_replace(',', '.', trim($row[$map['berat_badan_sebelum_hamil']])) 
                    : null;

                $data = [
                    'nama' => $nama,
                    'tanggal_lahir' => $tglLahir,
                    'nomor_telepon' => isset($map['nomor_telepon']) ? trim($row[$map['nomor_telepon']] ?? '') : '',
                    'desa_kelurahan' => isset($map['desa_kelurahan']) ? trim($row[$map['desa_kelurahan']] ?? '') : '',
                    'hpht' => $hpht,
                    'hpl' => $hpl,
                    'usia_kehamilan_minggu' => $usiaMinggu,
                    'status_kehamilan' => $statusKehamilan,
                    'status_anemia' => $statusAnemia,
                    'kadar_hb' => $kadarHb,
                    'imt' => $imt,
                    'lila' => $lila,
                    'berat_badan_sebelum_hamil' => $bb,
                ];

                if (isset($map['tinggi_badan']) && !empty($row[$map['tinggi_badan']])) {
                    $data['tinggi_badan'] = (float) str_replace(',', '.', trim($row[$map['tinggi_badan']]));
                }

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
