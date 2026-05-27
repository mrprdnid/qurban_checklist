<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Journey Qurban</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f0f7f0; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .search-card { width: 100%; max-width: 420px; }
        .search-header { background: #198754; color: #fff; border-radius: 12px 12px 0 0; padding: 2rem; text-align: center; }
        .kode-input { letter-spacing: .2em; font-size: 1.25rem; text-transform: uppercase; text-align: center; font-weight: 600; }
    </style>
</head>
<body>
<div class="search-card mx-3">
    <div class="search-header">
        <i class="bi bi-moon-stars-fill fs-1 mb-2 d-block"></i>
        <h5 class="fw-bold mb-0">Journey Qurban</h5>
        <div class="small opacity-75 mt-1">Lacak proses hewan qurbanmu</div>
    </div>
    <div class="card border-0 shadow-sm" style="border-radius: 0 0 12px 12px;">
        <div class="card-body p-4">
            @if(isset($error))
            <div class="alert alert-danger py-2 text-center">
                <i class="bi bi-exclamation-circle me-1"></i> {{ $error }}
            </div>
            @endif

            <p class="text-muted text-center small mb-3">Masukkan kode registrasi 4 karakter yang kamu terima via WhatsApp.</p>

            <form method="GET" action="{{ url('/journey') }}" onsubmit="this.action='{{ url('/journey') }}/' + document.getElementById('kode').value.toUpperCase().trim(); return true;">
                <div class="mb-3">
                    <input type="text" id="kode" name="kode" class="form-control kode-input"
                        placeholder="A1B2"
                        maxlength="4"
                        autocomplete="off"
                        autofocus
                        oninput="this.value = this.value.toUpperCase()">
                </div>
                <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                    <i class="bi bi-search me-1"></i> Cari Journey
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
