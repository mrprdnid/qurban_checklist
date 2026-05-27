@extends('layouts.app')

@section('title', 'Tambah Hewan Kurban')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('hewan.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Tambah Hewan Kurban</h4>
</div>

<div class="card border-0 shadow-sm" style="max-width:500px;">
    <div class="card-body">
        <form action="{{ route('hewan.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Nomor Urut <span class="text-danger">*</span></label>
                <input type="text" name="nomor_urut" class="form-control @error('nomor_urut') is-invalid @enderror" value="{{ old('nomor_urut') }}" placeholder="Contoh: D001 atau S001">
                @error('nomor_urut')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Jenis Hewan <span class="text-danger">*</span></label>
                <select name="jenis" class="form-select @error('jenis') is-invalid @enderror">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="domba" {{ old('jenis')=='domba'?'selected':'' }}>Domba</option>
                    <option value="sapi" {{ old('jenis')=='sapi'?'selected':'' }}>Sapi</option>
                </select>
                @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Hewan</label>
                <input type="text" name="nama_hewan" class="form-control" value="{{ old('nama_hewan') }}" placeholder="Contoh: si Hitam, no. 3...">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Pekurban <span class="text-danger">*</span></label>
                <input type="text" name="nama_pekurban" class="form-control @error('nama_pekurban') is-invalid @enderror" value="{{ old('nama_pekurban') }}">
                @error('nama_pekurban')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nomor WhatsApp</label>
                <input type="text" name="nomor_wa" class="form-control @error('nomor_wa') is-invalid @enderror" value="{{ old('nomor_wa') }}" placeholder="08xx-xxxx-xxxx">
                @error('nomor_wa')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
            </div>
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-save me-1"></i> Simpan</button>
        </form>
    </div>
</div>
@endsection
