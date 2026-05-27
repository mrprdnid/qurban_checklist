<?php

namespace App\Http\Controllers;

use App\Models\Hewan;
use App\Models\ChecklistKehadiran;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class ChecklistKehadiranController extends Controller
{
    public function index(Request $request)
    {
        $q      = $request->query('q');
        $status = $request->query('status'); // belum | progress | selesai

        $hewan = Hewan::with('checklistKehadiran')
            ->when($q, fn($query) => $query->where(function ($x) use ($q) {
                $x->where('nomor_urut', 'like', "%{$q}%")
                  ->orWhere('nama_hewan', 'like', "%{$q}%")
                  ->orWhere('nama_pekurban', 'like', "%{$q}%");
            }))
            ->leftJoin('checklist_kehadiran', 'checklist_kehadiran.hewan_id', '=', 'hewan.id')
            ->select('hewan.*')
            ->when($status === 'selesai',  fn($q) => $q->where('checklist_kehadiran.absensi', 1)->where('checklist_kehadiran.penyerahan_tagging', 1))
            ->when($status === 'progress', fn($q) => $q->where(function ($x) {
                $x->where(fn($a) => $a->where('checklist_kehadiran.absensi', 1)->where('checklist_kehadiran.penyerahan_tagging', 0))
                  ->orWhere(fn($a) => $a->where('checklist_kehadiran.absensi', 0)->where('checklist_kehadiran.penyerahan_tagging', 1));
            }))
            ->when($status === 'belum',    fn($q) => $q->where(function ($x) {
                $x->whereNull('checklist_kehadiran.id')
                  ->orWhere(fn($a) => $a->where('checklist_kehadiran.absensi', 0)->where('checklist_kehadiran.penyerahan_tagging', 0));
            }))
            ->orderByRaw('CASE WHEN checklist_kehadiran.absensi = 1 AND checklist_kehadiran.penyerahan_tagging = 1 THEN 1 ELSE 0 END ASC')
            ->orderBy('hewan.id', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('checklist.kehadiran.index', compact('hewan', 'q', 'status'));
    }

    public function show(Hewan $hewan)
    {
        $checklist = $hewan->checklistKehadiran;
        return view('checklist.kehadiran.show', compact('hewan', 'checklist'));
    }

    public function update(Request $request, Hewan $hewan, WhatsAppService $wa)
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

        // Kirim notifikasi WA sekali saat pertama kali semua item selesai
        $semuaSelesai = $checklist->absensi && $checklist->penyerahan_tagging;
        if ($semuaSelesai && !$hewan->kode_registrasi) {
            $kode = $this->generateKode();
            $hewan->kode_registrasi = $kode;
            $hewan->save();

            if ($hewan->nomor_wa) {
                $wa->sendRegistrasiKehadiran($hewan->nomor_wa, $hewan->nama_pekurban, $kode);
            }
        }

        return redirect()->route('checklist.kehadiran.show', $hewan)->with('success', 'Registrasi kehadiran berhasil disimpan.');
    }

    public function kirimWa(Hewan $hewan, WhatsAppService $wa)
    {
        if (!$hewan->kode_registrasi) {
            return back()->with('error', 'Kode registrasi belum digenerate.');
        }

        if (!$hewan->nomor_wa) {
            return back()->with('error', 'Nomor WA pekurban tidak tersedia.');
        }

        $berhasil = $wa->sendRegistrasiKehadiran($hewan->nomor_wa, $hewan->nama_pekurban, $hewan->kode_registrasi);

        return back()->with(
            $berhasil ? 'success' : 'error',
            $berhasil ? 'Pesan WhatsApp berhasil dikirim.' : 'Gagal mengirim WhatsApp. Cek log untuk detail.'
        );
    }

    private function generateKode(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $kode  = '';
        for ($i = 0; $i < 4; $i++) {
            $kode .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $kode;
    }
}
