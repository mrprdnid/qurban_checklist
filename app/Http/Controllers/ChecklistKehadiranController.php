<?php

namespace App\Http\Controllers;

use App\Models\Hewan;
use App\Models\ChecklistKehadiran;
use App\Services\WhatsAppService;
use App\Models\Setting;
use Illuminate\Http\Request;

class ChecklistKehadiranController extends Controller
{
    public function index(Request $request)
    {
        $q      = $request->query('q');
        $status = $request->query('status');
        $jenis  = in_array($request->query('jenis'), ['domba', 'sapi']) ? $request->query('jenis') : null;
        $sort   = $request->query('sort');
        $dir    = in_array($request->query('direction'), ['asc', 'desc']) ? $request->query('direction') : 'asc';
        if (!in_array($sort, ['nomor_urut', 'nama_pekurban'])) { $sort = null; }

        $base = Hewan::with('checklistKehadiran')
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
            ->when($status === 'belum', fn($q) => $q->where(function ($x) {
                $x->whereNull('checklist_kehadiran.id')
                  ->orWhere(fn($a) => $a->where('checklist_kehadiran.absensi', 0)->where('checklist_kehadiran.penyerahan_tagging', 0));
            }));

        $grouped = null;

        if ($jenis === 'sapi') {
            $list = (clone $base)
                ->where('hewan.jenis', 'sapi')
                ->orderBy('hewan.nomor_urut', 'asc')
                ->get();

            $grouped = $list->groupBy(fn($h) => $h->nama_hewan ?: '—');

            $hewan = null;
        } else {
            $hewan = (clone $base)
                ->when($jenis, fn($q) => $q->where('hewan.jenis', $jenis))
                ->when($sort,
                    fn($q) => $q->orderBy('hewan.' . $sort, $dir),
                    fn($q) => $status === 'progress'
                        ? $q->orderByRaw('CASE WHEN checklist_kehadiran.absensi_at IS NULL THEN 1 ELSE 0 END ASC')->orderBy('checklist_kehadiran.absensi_at', 'asc')
                        : ($status === 'selesai'
                            ? $q->orderBy('checklist_kehadiran.updated_at', 'desc')
                            : $q->orderByRaw('CASE WHEN checklist_kehadiran.absensi = 1 AND checklist_kehadiran.penyerahan_tagging = 1 THEN 1 ELSE 0 END ASC')->orderBy('hewan.id', 'desc'))
                )
                ->paginate(20)
                ->withQueryString();
        }

        return view('checklist.kehadiran.index', compact('hewan', 'grouped', 'q', 'status', 'jenis', 'sort', 'dir'));
    }

    public function show(Hewan $hewan)
    {
        $checklist = $hewan->checklistKehadiran;
        $waEnabled = WhatsAppService::isEnabled();
        return view('checklist.kehadiran.show', compact('hewan', 'checklist', 'waEnabled'));
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
        if (!WhatsAppService::isEnabled()) {
            return back()->with('error', 'WhatsApp API sedang dimatikan. Aktifkan terlebih dahulu di Pengaturan.');
        }

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
