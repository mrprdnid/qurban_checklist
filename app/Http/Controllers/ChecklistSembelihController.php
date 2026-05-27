<?php

namespace App\Http\Controllers;

use App\Models\Hewan;
use App\Models\ChecklistSembelih;
use Illuminate\Http\Request;

class ChecklistSembelihController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $hewan = Hewan::with('checklistSembelih')
            ->where('jenis', 'domba')
            ->when($q, fn($query) => $query->where(function ($x) use ($q) {
                $x->where('nomor_urut', 'like', "%{$q}%")
                  ->orWhere('nama_hewan', 'like', "%{$q}%")
                  ->orWhere('nama_pekurban', 'like', "%{$q}%");
            }))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('checklist.sembelih.index', compact('hewan', 'q'));
    }

    public function show(Hewan $hewan)
    {
        $checklist = $hewan->checklistSembelih;
        return view('checklist.sembelih.show', compact('hewan', 'checklist'));
    }

    public function update(Request $request, Hewan $hewan)
    {
        $checklist = $hewan->checklistSembelih ?? new ChecklistSembelih(['hewan_id' => $hewan->id]);

        foreach (['video_sembelih', 'foto_sembelih', 'otw_seset'] as $field) {
            $newValue = $request->boolean($field);
            if ($newValue && !$checklist->$field) {
                $checklist->{$field . '_at'} = now();
            } elseif (!$newValue) {
                $checklist->{$field . '_at'} = null;
            }
            $checklist->$field = $newValue;
        }

        $checklist->save();

        return redirect()->route('checklist.sembelih.show', $hewan)->with('success', 'Checklist sembelih berhasil disimpan.');
    }
}
