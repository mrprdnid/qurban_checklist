<?php

namespace App\Http\Controllers;

use App\Models\Hewan;
use App\Models\ChecklistKandang;
use Illuminate\Http\Request;

class ChecklistKandangController extends Controller
{
    public function index(Request $request)
    {
        $q      = $request->query('q');
        $status = $request->query('status');
        $hewan = Hewan::with('checklistKandang')
            ->where('jenis', 'domba')
            ->when($q, fn($query) => $query->where(function ($x) use ($q) {
                $x->where('nomor_urut', 'like', "%{$q}%")
                  ->orWhere('nama_hewan', 'like', "%{$q}%")
                  ->orWhere('nama_pekurban', 'like', "%{$q}%");
            }))
            ->leftJoin('checklist_kandang', 'checklist_kandang.hewan_id', '=', 'hewan.id')
            ->select('hewan.*')
            ->when($status === 'selesai',  fn($q) => $q->whereRaw('checklist_kandang.ambil_domba = 1 AND checklist_kandang.foto_hidup = 1 AND checklist_kandang.otw_sembelih = 1'))
            ->when($status === 'belum',    fn($q) => $q->where(fn($x) => $x->whereNull('checklist_kandang.id')->orWhereRaw('(checklist_kandang.ambil_domba + checklist_kandang.foto_hidup + checklist_kandang.otw_sembelih) = 0')))
            ->when($status === 'progress', fn($q) => $q->whereNotNull('checklist_kandang.id')->whereRaw('(checklist_kandang.ambil_domba + checklist_kandang.foto_hidup + checklist_kandang.otw_sembelih) > 0')->whereRaw('NOT (checklist_kandang.ambil_domba = 1 AND checklist_kandang.foto_hidup = 1 AND checklist_kandang.otw_sembelih = 1)'))
            ->orderByRaw('CASE WHEN checklist_kandang.ambil_domba = 1 AND checklist_kandang.foto_hidup = 1 AND checklist_kandang.otw_sembelih = 1 THEN 1 ELSE 0 END ASC')
            ->orderBy('hewan.id', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('checklist.kandang.index', compact('hewan', 'q', 'status'));
    }

    public function show(Hewan $hewan)
    {
        $checklist = $hewan->checklistKandang;
        return view('checklist.kandang.show', compact('hewan', 'checklist'));
    }

    public function update(Request $request, Hewan $hewan)
    {
        $checklist = $hewan->checklistKandang ?? new ChecklistKandang(['hewan_id' => $hewan->id]);

        foreach (['ambil_domba', 'foto_hidup', 'otw_sembelih'] as $field) {
            $newValue = $request->boolean($field);
            if ($newValue && !$checklist->$field) {
                $checklist->{$field . '_at'} = now();
            } elseif (!$newValue) {
                $checklist->{$field . '_at'} = null;
            }
            $checklist->$field = $newValue;
        }

        $checklist->save();

        return redirect()->route('checklist.kandang.show', $hewan)->with('success', 'Checklist kandang berhasil disimpan.');
    }
}
