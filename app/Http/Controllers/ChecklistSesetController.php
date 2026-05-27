<?php

namespace App\Http\Controllers;

use App\Models\Hewan;
use App\Models\ChecklistSeset;
use Illuminate\Http\Request;

class ChecklistSesetController extends Controller
{
    public function index(Request $request)
    {
        $q      = $request->query('q');
        $status = $request->query('status');
        $hewan = Hewan::with('checklistSeset')
            ->where('jenis', 'domba')
            ->when($q, fn($query) => $query->where(function ($x) use ($q) {
                $x->where('nomor_urut', 'like', "%{$q}%")
                  ->orWhere('nama_hewan', 'like', "%{$q}%")
                  ->orWhere('nama_pekurban', 'like', "%{$q}%");
            }))
            ->leftJoin('checklist_seset', 'checklist_seset.hewan_id', '=', 'hewan.id')
            ->select('hewan.*')
            ->when($status === 'selesai',  fn($q) => $q->whereRaw('checklist_seset.bagian_pekurban = 1 AND checklist_seset.kesesuaian_bagian = 1 AND checklist_seset.otw_pengambilan = 1'))
            ->when($status === 'belum',    fn($q) => $q->where(fn($x) => $x->whereNull('checklist_seset.id')->orWhereRaw('(checklist_seset.bagian_pekurban + checklist_seset.kesesuaian_bagian + checklist_seset.otw_pengambilan) = 0')))
            ->when($status === 'progress', fn($q) => $q->whereNotNull('checklist_seset.id')->whereRaw('(checklist_seset.bagian_pekurban + checklist_seset.kesesuaian_bagian + checklist_seset.otw_pengambilan) > 0')->whereRaw('NOT (checklist_seset.bagian_pekurban = 1 AND checklist_seset.kesesuaian_bagian = 1 AND checklist_seset.otw_pengambilan = 1)'))
            ->orderByRaw('CASE WHEN checklist_seset.bagian_pekurban = 1 AND checklist_seset.kesesuaian_bagian = 1 AND checklist_seset.otw_pengambilan = 1 THEN 1 ELSE 0 END ASC')
            ->orderBy('hewan.id', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('checklist.seset.index', compact('hewan', 'q', 'status'));
    }

    public function show(Hewan $hewan)
    {
        $checklist = $hewan->checklistSeset;
        return view('checklist.seset.show', compact('hewan', 'checklist'));
    }

    public function update(Request $request, Hewan $hewan)
    {
        $checklist = $hewan->checklistSeset ?? new ChecklistSeset(['hewan_id' => $hewan->id]);

        foreach (['bagian_pekurban', 'kesesuaian_bagian', 'otw_pengambilan'] as $field) {
            $newValue = $request->boolean($field);
            if ($newValue && !$checklist->$field) {
                $checklist->{$field . '_at'} = now();
            } elseif (!$newValue) {
                $checklist->{$field . '_at'} = null;
            }
            $checklist->$field = $newValue;
        }

        $checklist->save();

        return redirect()->route('checklist.seset.show', $hewan)->with('success', 'Checklist seset berhasil disimpan.');
    }
}
