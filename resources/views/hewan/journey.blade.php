@extends('layouts.app')
@section('title', 'Journey — ' . $hewan->nomor_urut)

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('hewan.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Journey Hewan Kurban</h5>
</div>

{{-- Info hewan --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-2 px-3">
        <div class="d-flex align-items-center gap-3">
            <span class="fw-bold fs-5">{{ $hewan->nomor_urut }}</span>
            @if($hewan->nama_hewan)<span class="text-muted">— {{ $hewan->nama_hewan }}</span>@endif
            <span class="badge {{ $hewan->jenis === 'domba' ? 'bg-primary' : 'bg-warning text-dark' }}">{{ ucfirst($hewan->jenis) }}</span>
        </div>
        <div class="small text-muted mt-1">
            {{ $hewan->nama_pekurban }}
            @if($hewan->nomor_wa) · <i class="bi bi-whatsapp text-success"></i> {{ $hewan->nomor_wa }}@endif
        </div>
    </div>
</div>

@php
$kehadiran = $hewan->checklistKehadiran;
$pengambilan = $hewan->checklistPengambilan;

if ($hewan->jenis === 'domba') {
    $kandang = $hewan->checklistKandang;
    $sembelih = $hewan->checklistSembelih;
    $seset = $hewan->checklistSeset;

    $steps = [
        [
            'label'   => 'Registrasi Kehadiran',
            'icon'    => 'bi-person-check',
            'route'   => route('checklist.kehadiran.show', $hewan),
            'items'   => [
                ['label' => 'Absensi Pekurban',      'done' => (bool)$kehadiran?->absensi,            'at' => $kehadiran?->absensi_at],
                ['label' => 'Penyerahan Tagging',    'done' => (bool)$kehadiran?->penyerahan_tagging, 'at' => $kehadiran?->penyerahan_tagging_at],
            ],
        ],
        [
            'label'   => 'Kandang',
            'icon'    => 'bi-house-door',
            'route'   => route('checklist.kandang.show', $hewan),
            'items'   => [
                ['label' => 'Ambil Domba',   'done' => (bool)$kandang?->ambil_domba,   'at' => $kandang?->ambil_domba_at],
                ['label' => 'Foto Hidup',    'done' => (bool)$kandang?->foto_hidup,    'at' => $kandang?->foto_hidup_at],
                ['label' => 'OTW Sembelih',  'done' => (bool)$kandang?->otw_sembelih,  'at' => $kandang?->otw_sembelih_at],
            ],
        ],
        [
            'label'   => 'Sembelih Domba',
            'icon'    => 'bi-scissors',
            'route'   => route('checklist.sembelih.show', $hewan),
            'items'   => [
                ['label' => 'Foto Sembelih',  'done' => (bool)$sembelih?->foto_sembelih,  'at' => $sembelih?->foto_sembelih_at],
                ['label' => 'Video Sembelih', 'done' => (bool)$sembelih?->video_sembelih, 'at' => $sembelih?->video_sembelih_at],
                ['label' => 'OTW Seset',      'done' => (bool)$sembelih?->otw_seset,      'at' => $sembelih?->otw_seset_at],
            ],
        ],
        [
            'label'   => 'Seset Domba',
            'icon'    => 'bi-tools',
            'route'   => route('checklist.seset.show', $hewan),
            'items'   => [
                ['label' => 'Bagian Pekurban',   'done' => (bool)$seset?->bagian_pekurban,   'at' => $seset?->bagian_pekurban_at],
                ['label' => 'Kesesuaian Bagian', 'done' => (bool)$seset?->kesesuaian_bagian, 'at' => $seset?->kesesuaian_bagian_at],
                ['label' => 'OTW Pengambilan',   'done' => (bool)$seset?->otw_pengambilan,   'at' => $seset?->otw_pengambilan_at],
            ],
        ],
        [
            'label'   => 'Pengambilan Bagian',
            'icon'    => 'bi-bag-check',
            'route'   => route('checklist.pengambilan.show', $hewan),
            'items'   => [
                ['label' => 'Kesesuaian Bagian', 'done' => (bool)$pengambilan?->kesesuaian_bagian, 'at' => $pengambilan?->kesesuaian_bagian_at],
                ['label' => 'Sudah Diambil',     'done' => (bool)$pengambilan?->sudah_diambil,     'at' => $pengambilan?->sudah_diambil_at],
            ],
        ],
    ];
} else {
    $sapi = $hewan->checklistSapi;

    $steps = [
        [
            'label'   => 'Registrasi Kehadiran',
            'icon'    => 'bi-person-check',
            'route'   => route('checklist.kehadiran.show', $hewan),
            'items'   => [
                ['label' => 'Absensi Pekurban',   'done' => (bool)$kehadiran?->absensi,            'at' => $kehadiran?->absensi_at],
                ['label' => 'Penyerahan Tagging', 'done' => (bool)$kehadiran?->penyerahan_tagging, 'at' => $kehadiran?->penyerahan_tagging_at],
            ],
        ],
        [
            'label'   => 'Checklist Sapi',
            'icon'    => 'bi-scissors',
            'route'   => route('checklist.sapi.show', $hewan),
            'items'   => [
                ['label' => 'Foto Hidup',          'done' => (bool)$sapi?->foto_hidup,          'at' => $sapi?->foto_hidup_at],
                ['label' => 'Video Sembelih',      'done' => (bool)$sapi?->video_sembelih,      'at' => $sapi?->video_sembelih_at],
                ['label' => 'Bagian Pekurban',     'done' => (bool)$sapi?->bagian_pekurban,     'at' => $sapi?->bagian_pekurban_at],
                ['label' => 'Kesesuaian Bagian',   'done' => (bool)$sapi?->kesesuaian_bagian,   'at' => $sapi?->kesesuaian_bagian_at],
                ['label' => 'OTW Pengambilan',     'done' => (bool)$sapi?->otw_pengambilan,     'at' => $sapi?->otw_pengambilan_at],
            ],
        ],
        [
            'label'   => 'Pengambilan Bagian',
            'icon'    => 'bi-bag-check',
            'route'   => route('checklist.pengambilan.show', $hewan),
            'items'   => [
                ['label' => 'Kesesuaian Bagian', 'done' => (bool)$pengambilan?->kesesuaian_bagian, 'at' => $pengambilan?->kesesuaian_bagian_at],
                ['label' => 'Sudah Diambil',     'done' => (bool)$pengambilan?->sudah_diambil,     'at' => $pengambilan?->sudah_diambil_at],
            ],
        ],
    ];
}

// Tentukan status tiap step
foreach ($steps as &$step) {
    $total = count($step['items']);
    $done  = collect($step['items'])->where('done', true)->count();
    if ($done === $total)      $step['status'] = 'done';
    elseif ($done > 0)         $step['status'] = 'progress';
    else                       $step['status'] = 'pending';
    $step['done_count']  = $done;
    $step['total_count'] = $total;
}
unset($step);

// Step aktif = pertama yang belum selesai
$activeIndex = collect($steps)->search(fn($s) => $s['status'] !== 'done');
if ($activeIndex === false) $activeIndex = count($steps) - 1;
@endphp

{{-- Timeline --}}
<div class="journey-timeline">
@foreach($steps as $i => $step)
@php
    $isCurrent = ($i === $activeIndex && $step['status'] !== 'done');
    $statusColor = match($step['status']) {
        'done'     => '#198754',
        'progress' => '#ffc107',
        default    => '#adb5bd',
    };
    $statusIcon = match($step['status']) {
        'done'     => 'bi-check-circle-fill',
        'progress' => 'bi-clock-fill',
        default    => 'bi-circle',
    };
@endphp
<div class="journey-step {{ $isCurrent ? 'journey-step--active' : '' }}">
    {{-- Connector line --}}
    @if(!$loop->last)
    <div class="journey-line" style="background: {{ $step['status'] === 'done' ? '#198754' : '#dee2e6' }}"></div>
    @endif

    {{-- Step circle --}}
    <div class="journey-dot" style="background: {{ $statusColor }}; border-color: {{ $statusColor }}">
        <i class="bi {{ $statusIcon }} text-white" style="font-size:.85rem"></i>
    </div>

    {{-- Step content --}}
    <div class="journey-content card border-0 shadow-sm {{ $isCurrent ? 'border-warning border-2' : '' }}" style="{{ $isCurrent ? 'border: 2px solid #ffc107 !important;' : '' }}">
        <div class="card-body py-2 px-3">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi {{ $step['icon'] }}" style="color: {{ $statusColor }}"></i>
                    <span class="fw-semibold">{{ $step['label'] }}</span>
                    @if($isCurrent)<span class="badge bg-warning text-dark ms-1" style="font-size:.65rem">Sedang di sini</span>@endif
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="small text-muted">{{ $step['done_count'] }}/{{ $step['total_count'] }}</span>
                    <a href="{{ $step['route'] }}" class="btn btn-outline-success btn-sm py-0 px-2">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                </div>
            </div>

            {{-- Items --}}
            <div class="mt-2">
            @foreach($step['items'] as $item)
            <div class="d-flex align-items-center gap-2 py-1 border-top" style="border-color:#f0f0f0 !important">
                @if($item['done'])
                <i class="bi bi-check-circle-fill text-success" style="font-size:.85rem;flex-shrink:0"></i>
                @else
                <i class="bi bi-circle text-secondary" style="font-size:.85rem;flex-shrink:0"></i>
                @endif
                <span class="small {{ $item['done'] ? '' : 'text-muted' }}">{{ $item['label'] }}</span>
                @if($item['done'] && $item['at'])
                <span class="ms-auto small text-muted text-nowrap">{{ $item['at']->format('H:i, d M') }}</span>
                @endif
            </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
@endforeach
</div>

@push('scripts')
<style>
.journey-timeline { position: relative; padding-left: 2rem; }
.journey-step { position: relative; margin-bottom: 1.25rem; }
.journey-dot {
    position: absolute;
    left: -2rem;
    top: .5rem;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 2px solid;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
}
.journey-line {
    position: absolute;
    left: calc(-2rem + 13px);
    top: 1.75rem;
    width: 2px;
    bottom: -1.25rem;
    z-index: 0;
}
.journey-content { border-radius: 8px; }
</style>
@endpush
@endsection
