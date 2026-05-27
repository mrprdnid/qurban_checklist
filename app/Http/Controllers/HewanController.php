<?php

namespace App\Http\Controllers;

use App\Models\Hewan;
use Illuminate\Http\Request;

class HewanController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $hewan = Hewan::when($q, fn($query) => $query->where(function ($x) use ($q) {
                $x->where('nomor_urut', 'like', "%{$q}%")
                  ->orWhere('nama_hewan', 'like', "%{$q}%")
                  ->orWhere('nama_pekurban', 'like', "%{$q}%");
            }))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('hewan.index', compact('hewan', 'q'));
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
}
