@extends('layouts.app')
@section('title', 'Pengaturan')

@section('content')
<h5 class="fw-bold mb-4"><i class="bi bi-gear-fill me-2 text-success"></i>Pengaturan</h5>

<div class="card border-0 shadow-sm">
    <div class="card-header card-header-green fw-semibold">Notifikasi WhatsApp</div>
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="fw-semibold">WhatsApp API</div>
                <div class="small text-muted mt-1">
                    Kirim notifikasi otomatis saat registrasi kehadiran selesai.<br>
                    Saat ini:
                    @if($waEnabled)
                    <span class="badge bg-success ms-1"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                    @else
                    <span class="badge bg-danger ms-1"><i class="bi bi-x-circle me-1"></i>Nonaktif</span>
                    @endif
                </div>
            </div>
            <form method="POST" action="{{ route('settings.wa.toggle') }}"
                  onsubmit="return confirm('{{ $waEnabled ? 'Matikan' : 'Aktifkan' }} WhatsApp API?')">
                @csrf
                <button type="submit"
                        class="btn {{ $waEnabled ? 'btn-outline-danger' : 'btn-success' }} btn-sm px-3">
                    <i class="bi bi-power me-1"></i>
                    {{ $waEnabled ? 'Matikan' : 'Aktifkan' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
