<?php

namespace App\Http\Controllers;

use App\Models\Hewan;
use App\Models\ChecklistSapi;
use Illuminate\Http\Request;

class ChecklistSapiController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $hewan = Hewan::with('checklistSapi')
            ->where('jenis', 'sapi')
            ->when($q, fn($query) => $query->where(function ($x) use ($q) {
                $x->where('nomor_urut', 'like', "%{$q}%")
                  ->orWhere('nama_hewan', 'like', "%{$q}%")
                  ->orWhere('nama_pekurban', 'like', "%{$q}%");
            }))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('checklist.sapi.index', compact('hewan', 'q'));
    }

    public function show(Hewan $hewan)
    {
        $checklist = $hewan->checklistSapi;
        return view('checklist.sapi.show', compact('hewan', 'checklist'));
    }

    public function update(Request $request, Hewan $hewan)
    {
        $checklist = $hewan->checklistSapi ?? new ChecklistSapi(['hewan_id' => $hewan->id]);

        foreach (['foto_hidup', 'video_sembelih', 'bagian_pekurban', 'kesesuaian_bagian', 'otw_pengambilan'] as $field) {
            $newValue = $request->boolean($field);
            if ($newValue && !$checklist->$field) {
                $checklist->{$field . '_at'} = now();
            } elseif (!$newValue) {
                $checklist->{$field . '_at'} = null;
            }
            $checklist->$field = $newValue;
        }

        $checklist->save();

        return redirect()->route('checklist.sapi.show', $hewan)->with('success', 'Checklist sapi berhasil disimpan.');
    }
}
