<?php

namespace App\Http\Controllers;

use App\Models\Hewan;
use Illuminate\Http\Request;

class HewanController extends Controller
{
    public function index(Request $request)
    {
        $q       = $request->query('q');
        $sort    = $request->query('sort', 'nomor_urut');
        $dir     = $request->query('direction', 'asc');
        $allowed = ['nomor_urut', 'jenis', 'nama_hewan', 'nama_pekurban', 'nomor_wa'];
        if (!in_array($sort, $allowed)) { $sort = 'nomor_urut'; }
        if (!in_array($dir, ['asc', 'desc'])) { $dir = 'asc'; }

        $hewan = Hewan::when($q, fn($query) => $query->where(function ($x) use ($q) {
                $x->where('nomor_urut', 'like', "%{$q}%")
                  ->orWhere('nama_hewan', 'like', "%{$q}%")
                  ->orWhere('nama_pekurban', 'like', "%{$q}%");
            }))
            ->orderBy($sort, $dir)
            ->paginate(20)
            ->withQueryString();

        return view('hewan.index', compact('hewan', 'q', 'sort', 'dir'));
    }

    public function create()
    {
        return view('hewan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_urut' => 'required|unique:hewan,nomor_urut',
            'jenis' => 'required|in:domba,sapi',
            'nama_pekurban' => 'required|string|max:255',
            'nomor_wa' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
        ]);

        Hewan::create($request->all());

        return redirect()->route('hewan.index')->with('success', 'Data hewan berhasil ditambahkan.');
    }

    public function show(Hewan $hewan)
    {
        return view('hewan.show', compact('hewan'));
    }

    public function edit(Hewan $hewan)
    {
        return view('hewan.edit', compact('hewan'));
    }

    public function update(Request $request, Hewan $hewan)
    {
        $request->validate([
            'nomor_urut' => 'required|unique:hewan,nomor_urut,' . $hewan->id,
            'jenis' => 'required|in:domba,sapi',
            'nama_pekurban' => 'required|string|max:255',
            'nomor_wa' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
        ]);

        $hewan->update($request->all());

        return redirect()->route('hewan.index')->with('success', 'Data hewan berhasil diperbarui.');
    }

    public function destroy(Hewan $hewan)
    {
        $hewan->delete();
        return redirect()->route('hewan.index')->with('success', 'Data hewan berhasil dihapus.');
    }

    public function journey(Hewan $hewan)
    {
        $hewan->load([
            'checklistKehadiran',
            'checklistKandang',
            'checklistSembelih',
            'checklistSapi',
            'checklistSeset',
            'checklistPengambilan',
        ]);

        return view('hewan.journey', compact('hewan'));
    }
}
