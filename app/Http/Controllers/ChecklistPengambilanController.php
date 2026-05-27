<?php

namespace App\Http\Controllers;

use App\Models\Hewan;
use App\Models\ChecklistPengambilan;
use Illuminate\Http\Request;

class ChecklistPengambilanController extends Controller
{
    public function index(Request $request)
    {
        $q      = $request->query('q');
        $status = $request->query('status');
        $hewan = Hewan::with('checklistPengambilan')
            ->when($q, fn($query) => $query->where(function ($x) use ($q) {
                $x->where('nomor_urut', 'like', "%{$q}%")
                  ->orWhere('nama_hewan', 'like', "%{$q}%")
                  ->orWhere('nama_pekurban', 'like', "%{$q}%");
            }))
            ->leftJoin('checklist_pengambilan', 'checklist_pengambilan.hewan_id', '=', 'hewan.id')
            ->select('hewan.*')
            ->when($status === 'selesai',  fn($q) => $q->whereRaw('checklist_pengambilan.kesesuaian_bagian = 1 AND checklist_pengambilan.sudah_diambil = 1'))
            ->when($status === 'belum',    fn($q) => $q->where(fn($x) => $x->whereNull('checklist_pengambilan.id')->orWhereRaw('(checklist_pengambilan.kesesuaian_bagian + checklist_pengambilan.sudah_diambil) = 0')))
            ->when($status === 'progress', fn($q) => $q->whereNotNull('checklist_pengambilan.id')->whereRaw('(checklist_pengambilan.kesesuaian_bagian + checklist_pengambilan.sudah_diambil) > 0')->whereRaw('NOT (checklist_pengambilan.kesesuaian_bagian = 1 AND checklist_pengambilan.sudah_diambil = 1)'))
            ->orderByRaw('CASE WHEN checklist_pengambilan.kesesuaian_bagian = 1 AND checklist_pengambilan.sudah_diambil = 1 THEN 1 ELSE 0 END ASC')
            ->orderBy('hewan.id', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('checklist.pengambilan.index', compact('hewan', 'q', 'status'));
    }

    public function show(Hewan $hewan)
    {
        $checklist = $hewan->checklistPengambilan;
        return view('checklist.pengambilan.show', compact('hewan', 'checklist'));
    }

    public function update(Request $request, Hewan $hewan)
    {
        $checklist = $hewan->checklistPengambilan ?? new ChecklistPengambilan(['hewan_id' => $hewan->id]);

        foreach (['kesesuaian_bagian', 'sudah_diambil'] as $field) {
            $newValue = $request->boolean($field);
            if ($newValue && !$checklist->$field) {
                $checklist->{$field . '_at'} = now();
            } elseif (!$newValue) {
                $checklist->{$field . '_at'} = null;
            }
            $checklist->$field = $newValue;
        }

        $checklist->save();

        return redirect()->route('checklist.pengambilan.show', $hewan)->with('success', 'Checklist pengambilan berhasil disimpan.');
    }
}
