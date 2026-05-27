@extends('layouts.app')
@section('title', 'Import Data Hewan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-file-earmark-arrow-up me-2 text-success"></i>Import Data Hewan</h5>
    <a href="{{ route('hewan.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

{{-- Hasil import --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('import_errors') && count(session('import_errors')))
<div class="alert alert-warning alert-dismissible fade show">
    <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>Beberapa baris dilewati:</div>
    <ul class="mb-0 ps-3">
        @foreach(session('import_errors') as $err)
        <li class="small">{{ $err }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-3">
    {{-- Upload form --}}
    <div class="col-12 col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-green fw-semibold">Upload File Excel</div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger py-2">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('hewan.import.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                            accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Format: .xlsx, .xls, atau .csv — maksimal 5 MB</div>
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-upload me-1"></i>Import
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Panduan --}}
    <div class="col-12 col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-green fw-semibold">Panduan</div>
            <div class="card-body small">
                <p class="mb-2">Download template lalu isi sesuai format kolom berikut:</p>
                <table class="table table-sm table-bordered mb-3">
                    <thead><tr><th>Kolom</th><th>Keterangan</th></tr></thead>
                    <tbody>
                        <tr><td><code>nomor_urut</code></td><td>Wajib, unik</td></tr>
                        <tr><td><code>jenis</code></td><td>Wajib: <code>domba</code> / <code>sapi</code></td></tr>
                        <tr><td><code>nama_hewan</code></td><td>Opsional</td></tr>
                        <tr><td><code>nama_pekurban</code></td><td>Wajib</td></tr>
                        <tr><td><code>nomor_wa</code></td><td>Opsional</td></tr>
                        <tr><td><code>keterangan</code></td><td>Opsional</td></tr>
                    </tbody>
                </table>
                <div class="alert alert-info py-2 mb-2">
                    <i class="bi bi-info-circle me-1"></i>
                    Jika <code>nomor_urut</code> sudah ada, data akan <strong>diperbarui</strong>. Jika belum ada, data akan <strong>ditambahkan</strong>.
                </div>
                <a href="{{ route('hewan.import.template') }}" class="btn btn-outline-success w-100">
                    <i class="bi bi-file-earmark-excel me-1"></i>Download Template Excel
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
