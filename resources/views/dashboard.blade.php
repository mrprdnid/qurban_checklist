@extends('layouts.app')

@section('title', 'Dashboard - Checklist Hewan Kurban')

@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-grid-fill me-2 text-success"></i>Dashboard</h5>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="fs-1 fw-bold text-success">{{ $totalHewan }}</div>
                <div class="text-muted small">Total Hewan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="fs-1 fw-bold text-primary">{{ $totalDomba }}</div>
                <div class="text-muted small">Domba</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="fs-1 fw-bold text-warning">{{ $totalSapi }}</div>
                <div class="text-muted small">Sapi</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="fs-1 fw-bold text-danger">{{ $totalPengambilan }}</div>
                <div class="text-muted small">Sudah Diambil</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-green fw-bold"><i class="bi bi-house-door me-1"></i> Progress Kandang Domba</div>
            <div class="card-body">
                @foreach([['label'=>'Ambil Domba','val'=>$kandang->ambil_domba??0],['label'=>'Foto Hidup','val'=>$kandang->foto_hidup??0],['label'=>'OTW Sembelih','val'=>$kandang->otw_sembelih??0]] as $item)
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small">{{ $item['label'] }}</span>
                    <span class="badge bg-success">{{ $item['val'] }} / {{ $totalDomba }}</span>
                </div>
                <div class="progress progress-mini mb-3">
                    <div class="progress-bar bg-success" style="width:{{ $totalDomba > 0 ? ($item['val']/$totalDomba*100) : 0 }}%"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-green fw-bold"><i class="bi bi-scissors me-1"></i> Progress Sembelih Domba</div>
            <div class="card-body">
                @foreach([['label'=>'Video Sembelih','val'=>$sembelih->video_sembelih??0],['label'=>'OTW Seset','val'=>$sembelih->otw_seset??0]] as $item)
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small">{{ $item['label'] }}</span>
                    <span class="badge bg-success">{{ $item['val'] }} / {{ $totalDomba }}</span>
                </div>
                <div class="progress progress-mini mb-3">
                    <div class="progress-bar bg-success" style="width:{{ $totalDomba > 0 ? ($item['val']/$totalDomba*100) : 0 }}%"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-green fw-bold"><i class="bi bi-scissors me-1"></i> Progress Checklist Sapi</div>
            <div class="card-body">
                @foreach([['label'=>'Foto Hidup','val'=>$sapiProgress->foto_hidup??0],['label'=>'Video Sembelih','val'=>$sapiProgress->video_sembelih??0],['label'=>'Kesesuaian Bagian Pekurban','val'=>$sapiProgress->kesesuaian_bagian??0],['label'=>'OTW Pengambilan','val'=>$sapiProgress->otw_pengambilan??0]] as $item)
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small">{{ $item['label'] }}</span>
                    <span class="badge bg-warning text-dark">{{ $item['val'] }} / {{ $totalSapi }}</span>
                </div>
                <div class="progress progress-mini mb-2">
                    <div class="progress-bar bg-warning" style="width:{{ $totalSapi > 0 ? ($item['val']/$totalSapi*100) : 0 }}%"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-green fw-bold"><i class="bi bi-bag-check me-1"></i> Progress Seset Domba</div>
            <div class="card-body">
                @foreach([['label'=>'Bagian Pekurban','val'=>$sesetProgress->bagian_pekurban??0],['label'=>'Kesesuaian Bagian','val'=>$sesetProgress->kesesuaian_bagian??0],['label'=>'OTW Pengambilan','val'=>$sesetProgress->otw_pengambilan??0]] as $item)
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small">{{ $item['label'] }}</span>
                    <span class="badge bg-success">{{ $item['val'] }} / {{ $totalDomba }}</span>
                </div>
                <div class="progress progress-mini mb-3">
                    <div class="progress-bar bg-success" style="width:{{ $totalDomba > 0 ? ($item['val']/$totalDomba*100) : 0 }}%"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
