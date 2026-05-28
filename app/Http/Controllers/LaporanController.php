<?php

namespace App\Http\Controllers;

use App\Models\Hewan;

class LaporanController extends Controller
{
    public function sembelih()
    {
        $domba = Hewan::with('checklistSembelih')
            ->where('jenis', 'domba')
            ->orderBy('nama_hewan')
            ->orderBy('nomor_urut')
            ->get();

        $groups = $domba
            ->groupBy(fn($h) => $h->nama_hewan ?: '— Tanpa Nama —')
            ->map(function ($items, $namaHewan) {
                $total    = $items->count();
                $selesai  = $items->filter(fn($h) =>
                    $h->checklistSembelih?->video_sembelih &&
                    $h->checklistSembelih?->foto_sembelih  &&
                    $h->checklistSembelih?->otw_seset
                )->count();
                $belum    = $items->filter(fn($h) =>
                    !$h->checklistSembelih ||
                    (!$h->checklistSembelih->video_sembelih &&
                     !$h->checklistSembelih->foto_sembelih  &&
                     !$h->checklistSembelih->otw_seset)
                )->count();
                $progress = $total - $selesai - $belum;

                return compact('namaHewan', 'total', 'selesai', 'progress', 'belum', 'items');
            });

        $totalAll    = $domba->count();
        $totalSelesai = $domba->filter(fn($h) =>
            $h->checklistSembelih?->video_sembelih &&
            $h->checklistSembelih?->foto_sembelih  &&
            $h->checklistSembelih?->otw_seset
        )->count();

        return view('laporan.sembelih', compact('groups', 'totalAll', 'totalSelesai'));
    }
}
