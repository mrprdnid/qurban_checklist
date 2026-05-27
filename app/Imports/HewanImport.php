<?php

namespace App\Imports;

use App\Models\Hewan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class HewanImport implements ToCollection, WithHeadingRow
{
    public array $results = ['inserted' => 0, 'updated' => 0, 'errors' => []];

    public function collection(Collection $rows)
    {
        foreach ($rows as $i => $row) {
            $nomor = trim($row['nomor_urut'] ?? '');
            $jenis = strtolower(trim($row['jenis'] ?? ''));
            $nama_pekurban = trim($row['nama_pekurban'] ?? '');

            $rowNum = $i + 2;

            if ($nomor === '' || $nama_pekurban === '') {
                $this->results['errors'][] = "Baris {$rowNum}: nomor_urut dan nama_pekurban wajib diisi.";
                continue;
            }

            if (!in_array($jenis, ['domba', 'sapi'])) {
                $this->results['errors'][] = "Baris {$rowNum}: jenis harus 'domba' atau 'sapi'.";
                continue;
            }

            $existing = Hewan::where('nomor_urut', $nomor)->first();

            $data = [
                'jenis'         => $jenis,
                'nama_hewan'    => trim($row['nama_hewan'] ?? '') ?: null,
                'nama_pekurban' => $nama_pekurban,
                'nomor_wa'      => trim($row['nomor_wa'] ?? '') ?: null,
                'keterangan'    => trim($row['keterangan'] ?? '') ?: null,
            ];

            if ($existing) {
                $existing->update($data);
                $this->results['updated']++;
            } else {
                Hewan::create(array_merge(['nomor_urut' => $nomor], $data));
                $this->results['inserted']++;
            }
        }
    }
}
