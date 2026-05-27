<?php

namespace App\Http\Controllers;

use App\Models\Hewan;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HewanImportController extends Controller
{
    public function index()
    {
        return view('hewan.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $path = $request->file('file')->getRealPath();
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, false);

        if (empty($rows)) {
            return back()->with('error', 'File kosong atau tidak dapat dibaca.');
        }

        // Baris pertama = header, normalisasi lowercase + trim
        $headers = array_map(fn($h) => strtolower(trim((string) $h)), $rows[0]);

        $inserted = 0;
        $updated  = 0;
        $errors   = [];

        foreach (array_slice($rows, 1) as $i => $row) {
            $data = array_combine($headers, $row);
            $rowNum = $i + 2;

            $nomor         = trim((string) ($data['nomor_urut'] ?? ''));
            $jenis         = strtolower(trim((string) ($data['jenis'] ?? '')));
            $nama_pekurban = trim((string) ($data['nama_pekurban'] ?? ''));

            if ($nomor === '' && $nama_pekurban === '') {
                continue; // baris kosong, lewati tanpa error
            }

            if ($nomor === '' || $nama_pekurban === '') {
                $errors[] = "Baris {$rowNum}: nomor_urut dan nama_pekurban wajib diisi.";
                continue;
            }

            if (!in_array($jenis, ['domba', 'sapi'])) {
                $errors[] = "Baris {$rowNum}: jenis harus 'domba' atau 'sapi' (nilai: '{$jenis}').";
                continue;
            }

            $payload = [
                'jenis'         => $jenis,
                'nama_hewan'    => trim((string) ($data['nama_hewan'] ?? '')) ?: null,
                'nama_pekurban' => $nama_pekurban,
                'nomor_wa'      => trim((string) ($data['nomor_wa'] ?? '')) ?: null,
                'keterangan'    => trim((string) ($data['keterangan'] ?? '')) ?: null,
            ];

            $existing = Hewan::where('nomor_urut', $nomor)->first();

            if ($existing) {
                $existing->update($payload);
                $updated++;
            } else {
                Hewan::create(array_merge(['nomor_urut' => $nomor], $payload));
                $inserted++;
            }
        }

        $msg = "Import selesai: {$inserted} data baru, {$updated} data diperbarui.";

        return back()
            ->with('success', $msg)
            ->with('import_errors', $errors);
    }

    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Hewan');

        $headers = ['nomor_urut', 'jenis', 'nama_hewan', 'nama_pekurban', 'nomor_wa', 'keterangan'];
        foreach ($headers as $col => $header) {
            $cell = chr(65 + $col) . '1';
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getColumnDimensionByColumn($col + 1)->setAutoSize(true);
        }

        $sheet->getStyle('A1:F1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('198754');
        $sheet->getStyle('A1:F1')->getFont()->getColor()->setRGB('FFFFFF');

        $examples = [
            ['D001', 'domba', 'Si Putih', 'Budi Santoso',  '08123456789', ''],
            ['D002', 'domba', '',          'Siti Rahma',    '',            'Titip tetangga'],
            ['S001', 'sapi',  'Macan',     'Ahmad Fauzi',   '08987654321', ''],
        ];
        foreach ($examples as $row => $data) {
            foreach ($data as $col => $val) {
                $sheet->setCellValue(chr(65 + $col) . ($row + 2), $val);
            }
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'template_data_hewan.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
