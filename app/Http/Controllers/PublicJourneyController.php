<?php

namespace App\Http\Controllers;

use App\Models\Hewan;
use Illuminate\Http\Request;

class PublicJourneyController extends Controller
{
    public function index()
    {
        return view('public.journey-search');
    }

    public function show(string $kode)
    {
        $hewan = Hewan::where('kode_registrasi', strtoupper(trim($kode)))
            ->with([
                'checklistKehadiran',
                'checklistKandang',
                'checklistSembelih',
                'checklistSapi',
                'checklistSeset',
                'checklistPengambilan',
            ])
            ->first();

        if (!$hewan) {
            return view('public.journey-search', ['error' => 'Kode registrasi tidak ditemukan.']);
        }

        return view('public.journey', compact('hewan'));
    }
}
