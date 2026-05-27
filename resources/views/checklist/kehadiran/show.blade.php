@extends('layouts.app')
@section('title', 'Kehadiran — ' . $hewan->nomor_urut)

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('checklist.kehadiran') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Registrasi Kehadiran Pekurban</h5>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2 px-3">
        <div class="d-flex align-items-center gap-3">
            <div>
                <span class="fw-bold fs-5">{{ $hewan->nomor_urut }}</span>
                @if($hewan->nama_hewan)<span class="text-muted ms-1">— {{ $hewan->nama_hewan }}</span>@endif
            </div>
            <span class="badge {{ $hewan->jenis === 'domba' ? 'bg-primary' : 'bg-warning text-dark' }}">{{ ucfirst($hewan->jenis) }}</span>
        </div>
        <div class="small text-muted mt-1">{{ $hewan->nama_pekurban }}@if($hewan->nomor_wa) · <i class="bi bi-whatsapp text-success"></i> {{ $hewan->nomor_wa }}@endif</div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header card-header-green fw-semibold">Item Checklist</div>
    <div class="card-body p-0">
        <form action="{{ route('checklist.kehadiran.update', $hewan) }}" method="POST">
            @csrf @method('PATCH')
            @foreach([
                ['field'=>'absensi',            'label'=>'Absensi Pekurban',     'at'=>'absensi_at'],
                ['field'=>'penyerahan_tagging',  'label'=>'Penyerahan Tagging Hewan', 'at'=>'penyerahan_tagging_at'],
            ] as $item)
            <div class="d-flex align-items-center px-3 py-3 border-bottom">
                <div class="flex-grow-1">
                    <div class="fw-semibold">{{ $item['label'] }}</div>
                    @if($checklist?->{$item['at']})
                    <div class="small text-muted"><i class="bi bi-clock me-1"></i>{{ $checklist->{$item['at']}->format('H:i, d M Y') }}</div>
                    @endif
                </div>
                <div class="form-check form-switch ms-3 mb-0">
                    <input class="form-check-input" type="checkbox" role="switch"
                        name="{{ $item['field'] }}" value="1" style="width:2.5em; height:1.3em;"
                        {{ $checklist?->{$item['field']} ? 'checked' : '' }}>
                </div>
            </div>
            @endforeach
            <div class="px-3 py-3">
                <button type="submit" class="btn btn-success w-100"><i class="bi bi-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
