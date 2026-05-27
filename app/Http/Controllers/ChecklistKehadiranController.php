<?php

namespace App\Http\Controllers;

use App\Models\Hewan;
use App\Models\ChecklistKehadiran;
use Illuminate\Http\Request;

class ChecklistKehadiranController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $hewan = Hewan::with('checklistKehadiran')
            ->when($q, fn($query) => $query->where(function ($x) use ($q) {
                $x->where('nomor_urut', 'like', "%{$q}%")
                  ->orWhere('nama_hewan', 'like', "%{$q}%")
                  ->orWhere('nama_pekurban', 'like', "%{$q}%");
            }))
            ->leftJoin('checklist_kehadiran', 'checklist_kehadiran.hewan_id', '=', 'hewan.id')
            ->select('hewan.*')
            ->orderByRaw('CASE WHEN checklist_kehadiran.absensi = 1 AND checklist_kehadiran.penyerahan_tagging = 1 THEN 1 ELSE 0 END ASC')
            ->orderBy('hewan.id', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('checklist.kehadiran.index', compact('hewan', 'q'));
    }

    public function show(Hewan $hewan)
    {
        $checklist = $hewan->checklistKehadiran;
        return view('checklist.kehadiran.show', compact('hewan', 'checklist'));
    }

    public function update(Request $request, Hewan $hewan)
    {
        $checklist = $hewan->checklistKehadiran ?? new ChecklistKehadiran(['hewan_id' => $hewan->id]);

        foreach (['absensi', 'penyerahan_tagging'] as $field) {
            $newValue = $request->boolean($field);
            if ($newValue && !$checklist->$field) {
                $checklist->{$field . '_at'} = now();
            } elseif (!$newValue) {
                $checklist->{$field . '_at'} = null;
            }
            $checklist->$field = $newValue;
        }

        $checklist->save();

        return redirect()->route('checklist.kehadiran.show', $hewan)->with('success', 'Registrasi kehadiran berhasil disimpan.');
    }
}
