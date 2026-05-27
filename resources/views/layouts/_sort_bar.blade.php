@php
$_sortCols  = ['nomor_urut' => 'No. Urut', 'nama_pekurban' => 'Nama Pekurban'];
$_sort      = $sort ?? null;
$_dir       = $dir ?? 'asc';
$_mkSortUrl = fn($col) => request()->fullUrlWithQuery([
    'sort'      => $col,
    'direction' => ($_sort === $col && $_dir === 'asc') ? 'desc' : 'asc',
]);
$_remaining = request()->except(['sort', 'direction']);
$_clearUrl  = url()->current() . ($_remaining ? '?' . http_build_query($_remaining) : '');
@endphp
<div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
    <span class="small text-muted">Urutkan:</span>
    @foreach($_sortCols as $_col => $_label)
    @php $_active = $_sort === $_col; @endphp
    <a href="{{ $_mkSortUrl($_col) }}"
       class="btn btn-sm {{ $_active ? 'btn-secondary' : 'btn-outline-secondary' }} py-0 px-2"
       style="font-size:.75rem">
        {{ $_label }}
        @if($_active)
            <i class="bi bi-caret-{{ $_dir === 'asc' ? 'up' : 'down' }}-fill ms-1" style="font-size:.6rem"></i>
        @endif
    </a>
    @endforeach
    @if($_sort)
    <a href="{{ $_clearUrl }}" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:.75rem" title="Reset urutan">
        <i class="bi bi-x-lg"></i>
    </a>
    @endif
</div>
