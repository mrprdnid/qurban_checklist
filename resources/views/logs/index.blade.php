@extends('layouts.app')
@section('title', 'Log Aktivitas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-journal-text me-2 text-success"></i>Log Aktivitas</h5>
</div>

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('logs.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label form-label-sm mb-1 fw-semibold">Model</label>
                <select name="model" class="form-select form-select-sm">
                    <option value="">Semua Model</option>
                    @foreach(\App\Models\ActivityLog::$modelLabels as $class => $label)
                    <option value="{{ $class }}" {{ request('model') === $class ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label form-label-sm mb-1 fw-semibold">Aksi</label>
                <select name="action" class="form-select form-select-sm">
                    <option value="">Semua Aksi</option>
                    @foreach(\App\Models\ActivityLog::$actionLabels as $key => $info)
                    <option value="{{ $key }}" {{ request('action') === $key ? 'selected' : '' }}>{{ $info['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label form-label-sm mb-1 fw-semibold">User</label>
                <select name="user_id" class="form-select form-select-sm">
                    <option value="">Semua User</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-success btn-sm flex-fill"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('logs.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

{{-- Desktop table --}}
<div class="card border-0 shadow-sm d-none d-md-block">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 small">
            <thead>
                <tr>
                    <th style="width:140px">Waktu</th>
                    <th>User</th>
                    <th>Model</th>
                    <th>ID</th>
                    <th>Aksi</th>
                    <th>Perubahan</th>
                </tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
            <tr class="align-top">
                <td class="text-muted text-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                <td>{{ $log->user?->name ?? '<sistem>' }}</td>
                <td>{{ $log->model_label }}</td>
                <td class="text-muted">#{{ $log->loggable_id }}</td>
                <td>
                    @php $al = \App\Models\ActivityLog::$actionLabels[$log->action] ?? ['label'=>$log->action,'class'=>'bg-secondary'] @endphp
                    <span class="badge {{ $al['class'] }}">{{ $al['label'] }}</span>
                </td>
                <td>
                    @if($log->action === 'updated')
                        @foreach($log->new_values_array as $field => $newVal)
                        <div><span class="text-muted">{{ $field }}:</span>
                            <span class="text-danger text-decoration-line-through me-1">{{ $log->old_values_array[$field] ?? '—' }}</span>
                            <span class="text-success">{{ $newVal }}</span>
                        </div>
                        @endforeach
                    @elseif($log->action === 'created')
                        @foreach($log->new_values_array as $field => $val)
                        <div><span class="text-muted">{{ $field }}:</span> {{ $val }}</div>
                        @endforeach
                    @elseif($log->action === 'deleted')
                        @foreach($log->old_values_array as $field => $val)
                        <div><span class="text-muted">{{ $field }}:</span> <span class="text-danger">{{ $val }}</span></div>
                        @endforeach
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada log.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile cards --}}
<div class="d-md-none">
    @forelse($logs as $log)
    @php $al = \App\Models\ActivityLog::$actionLabels[$log->action] ?? ['label'=>$log->action,'class'=>'bg-secondary'] @endphp
    <div class="card border-0 shadow-sm mb-2">
        <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-semibold small">{{ $log->user?->name ?? '<sistem>' }}</div>
                    <div class="text-muted" style="font-size:.75rem">{{ $log->created_at->format('d M Y H:i') }}</div>
                </div>
                <span class="badge {{ $al['class'] }}">{{ $al['label'] }}</span>
            </div>
            <div class="small mt-1 text-muted">{{ $log->model_label }} #{{ $log->loggable_id }}</div>
            @if($log->action === 'updated')
                @foreach($log->new_values_array as $field => $newVal)
                <div class="small"><span class="text-muted">{{ $field }}:</span>
                    <span class="text-danger text-decoration-line-through me-1">{{ $log->old_values_array[$field] ?? '—' }}</span>
                    <span class="text-success">{{ $newVal }}</span>
                </div>
                @endforeach
            @elseif($log->action === 'created')
                @foreach($log->new_values_array as $field => $val)
                <div class="small"><span class="text-muted">{{ $field }}:</span> {{ $val }}</div>
                @endforeach
            @elseif($log->action === 'deleted')
                @foreach($log->old_values_array as $field => $val)
                <div class="small"><span class="text-muted">{{ $field }}:</span> <span class="text-danger">{{ $val }}</span></div>
                @endforeach
            @endif
        </div>
    </div>
    @empty
    <div class="text-center text-muted py-4">Belum ada log.</div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="mt-3 d-flex justify-content-center">
    {{ $logs->links() }}
</div>
@endsection
