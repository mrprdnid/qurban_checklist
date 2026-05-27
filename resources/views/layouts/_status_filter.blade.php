@php
$filters = [
    ''         => ['label' => 'Semua',      'outline' => 'btn-outline-secondary', 'solid' => 'btn-secondary'],
    'belum'    => ['label' => 'Belum',       'outline' => 'btn-outline-danger',    'solid' => 'btn-danger'],
    'progress' => ['label' => 'On Progress', 'outline' => 'btn-outline-warning',   'solid' => 'btn-warning text-dark'],
    'selesai'  => ['label' => 'Selesai',     'outline' => 'btn-outline-success',   'solid' => 'btn-success'],
];
@endphp
<div class="d-flex gap-2 mb-3 flex-wrap">
    @foreach($filters as $val => $f)
    @php $active = ($status ?? '') === $val; @endphp
    <a href="{{ route($filterRoute, array_filter(['q' => $q ?? null, 'status' => $val ?: null])) }}"
       class="btn btn-sm {{ $active ? $f['solid'] : $f['outline'] }}">
        {{ $f['label'] }}
    </a>
    @endforeach
</div>
