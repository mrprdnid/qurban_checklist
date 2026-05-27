@extends('layouts.app')
@section('title', 'Checklist Pengambilan — ' . $hewan->nomor_urut)

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('checklist.pengambilan') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Checklist Pengambilan</h5>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2 px-3">
        <div class="d-flex align-items-center gap-3">
            <div>
                <span class="fw-bold fs-5">{{ $hewan->nomor_urut }}</span>
                @if($hewan->nama_hewan)<span class="text-muted ms-1">— {{ $hewan->nama_hewan }}</span>@endif
            </div>
            <span class="badge {{ $hewan->jenis==='domba' ? 'bg-primary' : 'bg-warning text-dark' }}">{{ ucfirst($hewan->jenis) }}</span>
            @if($checklist?->diambil_at)
            <span class="badge bg-success ms-auto"><i class="bi bi-check-circle me-1"></i>Diambil {{ $checklist->diambil_at->format('H:i') }}</span>
            @endif
        </div>
        <div class="small text-muted mt-1">{{ $hewan->nama_pekurban }}@if($hewan->nomor_wa) · <i class="bi bi-whatsapp text-success"></i> {{ $hewan->nomor_wa }}@endif</div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header card-header-green fw-semibold">Data Pengambilan</div>
    <div class="card-body">
        <form action="{{ route('checklist.pengambilan.update', $hewan) }}" method="POST">
            @csrf @method('PATCH')
            <div class="mb-3">
                <label class="form-label fw-semibold">Nomor WA Pemesan</label>
                <input type="tel" name="nomor_wa_pemesan" class="form-control"
                    value="{{ old('nomor_wa_pemesan', $checklist?->nomor_wa_pemesan) }}"
                    placeholder="08xx-xxxx-xxxx">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Data Pengambilan</label>
                <textarea name="data_pengambilan" class="form-control" rows="3"
                    placeholder="Nama pengambil, alamat, catatan...">{{ old('data_pengambilan', $checklist?->data_pengambilan) }}</textarea>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Paraf Pengambil</label>
                <input type="text" name="paraf_pengambil" class="form-control"
                    value="{{ old('paraf_pengambil', $checklist?->paraf_pengambil) }}"
                    placeholder="Nama / paraf">
                <div class="form-text">Mengisi paraf akan mencatat waktu pengambilan secara otomatis.</div>
            </div>
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-save me-1"></i> Simpan</button>
        </form>
    </div>
</div>
@endsection
