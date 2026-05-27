<?php

namespace App\Http\Controllers;

use App\Models\Hewan;
use App\Models\ChecklistPengambilan;
use Illuminate\Http\Request;

class ChecklistPengambilanController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $hewan = Hewan::with('checklistPengambilan')
            ->when($q, fn($query) => $query->where(function ($x) use ($q) {
                $x->where('nomor_urut', 'like', "%{$q}%")
                  ->orWhere('nama_hewan', 'like', "%{$q}%")
                  ->orWhere('nama_pekurban', 'like', "%{$q}%");
            }))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('checklist.pengambilan.index', compact('hewan', 'q'));
    }

    public function show(Hewan $hewan)
    {
        $checklist = $hewan->checklistPengambilan;
        return view('checklist.pengambilan.show', compact('hewan', 'checklist'));
    }

    public function update(Request $request, Hewan $hewan)
    {
        $request->validate([
            'nomor_wa_pemesan' => 'nullable|string|max:20',
            'data_pengambilan' => 'nullable|string',
            'paraf_pengambil'  => 'nullable|string|max:100',
        ]);

        $checklist = $hewan->checklistPengambilan ?? new ChecklistPengambilan(['hewan_id' => $hewan->id]);

        $checklist->nomor_wa_pemesan = $request->nomor_wa_pemesan;
        $checklist->data_pengambilan = $request->data_pengambilan;
        $checklist->paraf_pengambil  = $request->paraf_pengambil;

        if ($request->filled('paraf_pengambil') && !$checklist->diambil_at) {
            $checklist->diambil_at = now();
        }

        $checklist->save();

        return redirect()->route('checklist.pengambilan.show', $hewan)->with('success', 'Checklist pengambilan berhasil disimpan.');
    }
}
