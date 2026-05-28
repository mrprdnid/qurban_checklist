@extends('layouts.app')
@section('title', 'Laporan Sembelih Domba')

@section('content')
<h5 class="fw-bold mb-1"><i class="bi bi-bar-chart-fill me-2 text-success"></i>Laporan Sembelih Domba</h5>
<p class="text-muted small mb-3">Rekap per kelompok hewan — Checklist: Video Sembelih, Foto Sembelih, OTW Seset</p>

{{-- Summary bar --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3 px-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="fw-semibold">Total Domba</span>
            <span class="fw-bold">{{ $totalSelesai }} / {{ $totalAll }} selesai</span>
        </div>
        @php $pct = $totalAll > 0 ? round($totalSelesai / $totalAll * 100) : 0; @endphp
        <div class="progress" style="height:10px">
            <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
        </div>
        <div class="small text-muted mt-1">{{ $pct }}% selesai</div>
    </div>
</div>

{{-- Per-group cards --}}
@forelse($groups as $group)
@php
    $pctGroup = $group['total'] > 0 ? round($group['selesai'] / $group['total'] * 100) : 0;
@endphp
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header d-flex align-items-center justify-content-between py-2 px-3"
         style="background:#f8f9fa; border-bottom:1px solid #dee2e6"
         data-bs-toggle="collapse" data-bs-target="#group-{{ $loop->index }}" role="button" aria-expanded="false">
        <span class="fw-bold">
            <i class="bi bi-chevron-right me-1 text-muted transition-icon" style="font-size:.75rem"></i>
            <i class="bi bi-collection me-1 text-success"></i>{{ $group['namaHewan'] }}
        </span>
        <div class="d-flex align-items-center gap-2">
            @if($group['selesai'] === $group['total'])
            <span class="badge bg-success">Selesai {{ $group['total'] }}/{{ $group['total'] }}</span>
            @elseif($group['selesai'] > 0 || $group['progress'] > 0)
            <span class="badge bg-warning text-dark">{{ $group['selesai'] }}/{{ $group['total'] }}</span>
            @else
            <span class="badge bg-secondary">Belum {{ $group['total'] }}</span>
            @endif
        </div>
    </div>

    <div class="collapse" id="group-{{ $loop->index }}">
        {{-- Mini progress --}}
        <div class="px-3 pt-2 pb-1">
            <div class="d-flex gap-3 small mb-1">
                <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Selesai: <strong>{{ $group['selesai'] }}</strong></span>
                @if($group['progress'] > 0)
                <span class="text-warning"><i class="bi bi-clock-fill me-1"></i>Progress: <strong>{{ $group['progress'] }}</strong></span>
                @endif
                <span class="text-secondary"><i class="bi bi-circle me-1"></i>Belum: <strong>{{ $group['belum'] }}</strong></span>
            </div>
            <div class="progress mb-2" style="height:6px">
                <div class="progress-bar bg-success" style="width:{{ $pctGroup }}%"></div>
                @if($group['progress'] > 0)
                <div class="progress-bar bg-warning" style="width:{{ round($group['progress'] / $group['total'] * 100) }}%"></div>
                @endif
            </div>
        </div>

        {{-- List items --}}
        <div class="list-group list-group-flush">
        @foreach($group['items'] as $h)
        @php
            $cl   = $h->checklistSembelih;
            $done = ($cl?->video_sembelih ? 1 : 0) + ($cl?->foto_sembelih ? 1 : 0) + ($cl?->otw_seset ? 1 : 0);
        @endphp
        <div class="list-group-item px-3 py-2">
            <div class="d-flex align-items-center gap-3">
                <div class="text-muted text-center flex-shrink-0" style="width:1.5rem;font-size:.75rem">{{ $loop->iteration }}</div>
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-semibold small">{{ $h->nomor_urut }}</span>
                        <span class="text-muted small">{{ $h->nama_pekurban }}</span>
                    </div>
                    <div class="d-flex gap-2 mt-1 flex-wrap">
                        <span class="badge {{ $cl?->video_sembelih ? 'bg-success' : 'bg-light text-secondary border' }}" style="font-size:.65rem">
                            <i class="bi bi-camera-video me-1"></i>Video
                        </span>
                        <span class="badge {{ $cl?->foto_sembelih ? 'bg-success' : 'bg-light text-secondary border' }}" style="font-size:.65rem">
                            <i class="bi bi-image me-1"></i>Foto
                        </span>
                        <span class="badge {{ $cl?->otw_seset ? 'bg-success' : 'bg-light text-secondary border' }}" style="font-size:.65rem">
                            <i class="bi bi-arrow-right-circle me-1"></i>OTW Seset
                        </span>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    @if($done === 3)<span class="badge bg-success">Selesai</span>
                    @elseif($done > 0)<span class="badge bg-warning text-dark">{{ $done }}/3</span>
                    @else<span class="badge bg-secondary">Belum</span>@endif
                </div>
            </div>
        </div>
        @endforeach
        </div>
    </div>
</div>
@empty
<div class="card border-0 shadow-sm">
    <div class="card-body text-center text-muted py-5">Belum ada data domba.</div>
</div>
@endforelse

@push('scripts')
<style>
.transition-icon { transition: transform .2s ease; }
[data-bs-toggle="collapse"][aria-expanded="true"] .transition-icon { transform: rotate(90deg); }
</style>
@endpush
@endsection
