<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HewanController;
use App\Http\Controllers\ChecklistKandangController;
use App\Http\Controllers\ChecklistSembelihController;
use App\Http\Controllers\ChecklistSapiController;
use App\Http\Controllers\ChecklistSesetController;
use App\Http\Controllers\ChecklistPengambilanController;
use App\Models\Hewan;
use App\Models\ChecklistKandang;
use App\Models\ChecklistSembelih;
use App\Models\ChecklistSapi;
use App\Models\ChecklistSeset;
use App\Models\ChecklistPengambilan;

Route::get('/', function () {
    $totalHewan = Hewan::count();
    $totalDomba = Hewan::where('jenis', 'domba')->count();
    $totalSapi = Hewan::where('jenis', 'sapi')->count();
    $totalPengambilan = ChecklistPengambilan::whereNotNull('diambil_at')->count();

    $kandang = (object)[
        'ambil_domba' => ChecklistKandang::where('ambil_domba', true)->count(),
        'foto_hidup'  => ChecklistKandang::where('foto_hidup', true)->count(),
        'otw_sembelih'=> ChecklistKandang::where('otw_sembelih', true)->count(),
    ];
    $sembelih = (object)[
        'video_sembelih' => ChecklistSembelih::where('video_sembelih', true)->count(),
        'otw_seset'      => ChecklistSembelih::where('otw_seset', true)->count(),
    ];
    $sapiProgress = (object)[
        'foto_hidup'       => ChecklistSapi::where('foto_hidup', true)->count(),
        'video_sembelih'   => ChecklistSapi::where('video_sembelih', true)->count(),
        'bagian_pekurban'  => ChecklistSapi::where('bagian_pekurban', true)->count(),
        'kesesuaian_bagian'=> ChecklistSapi::where('kesesuaian_bagian', true)->count(),
        'otw_pengambilan'  => ChecklistSapi::where('otw_pengambilan', true)->count(),
    ];
    $sesetProgress = (object)[
        'bagian_pekurban'  => ChecklistSeset::where('bagian_pekurban', true)->count(),
        'kesesuaian_bagian'=> ChecklistSeset::where('kesesuaian_bagian', true)->count(),
        'otw_pengambilan'  => ChecklistSeset::where('otw_pengambilan', true)->count(),
    ];

    return view('dashboard', compact('totalHewan','totalDomba','totalSapi','totalPengambilan','kandang','sembelih','sapiProgress','sesetProgress'));
})->name('dashboard');

// Data Hewan
Route::resource('hewan', HewanController::class);

// Checklist Kandang
Route::get('/checklist/kandang', [ChecklistKandangController::class, 'index'])->name('checklist.kandang');
Route::get('/checklist/kandang/{hewan}', [ChecklistKandangController::class, 'show'])->name('checklist.kandang.show');
Route::patch('/checklist/kandang/{hewan}', [ChecklistKandangController::class, 'update'])->name('checklist.kandang.update');

// Checklist Sembelih Domba
Route::get('/checklist/sembelih', [ChecklistSembelihController::class, 'index'])->name('checklist.sembelih');
Route::get('/checklist/sembelih/{hewan}', [ChecklistSembelihController::class, 'show'])->name('checklist.sembelih.show');
Route::patch('/checklist/sembelih/{hewan}', [ChecklistSembelihController::class, 'update'])->name('checklist.sembelih.update');

// Checklist Sapi
Route::get('/checklist/sapi', [ChecklistSapiController::class, 'index'])->name('checklist.sapi');
Route::get('/checklist/sapi/{hewan}', [ChecklistSapiController::class, 'show'])->name('checklist.sapi.show');
Route::patch('/checklist/sapi/{hewan}', [ChecklistSapiController::class, 'update'])->name('checklist.sapi.update');

// Checklist Seset Domba
Route::get('/checklist/seset', [ChecklistSesetController::class, 'index'])->name('checklist.seset');
Route::get('/checklist/seset/{hewan}', [ChecklistSesetController::class, 'show'])->name('checklist.seset.show');
Route::patch('/checklist/seset/{hewan}', [ChecklistSesetController::class, 'update'])->name('checklist.seset.update');

// Checklist Pengambilan
Route::get('/checklist/pengambilan', [ChecklistPengambilanController::class, 'index'])->name('checklist.pengambilan');
Route::get('/checklist/pengambilan/{hewan}', [ChecklistPengambilanController::class, 'show'])->name('checklist.pengambilan.show');
Route::patch('/checklist/pengambilan/{hewan}', [ChecklistPengambilanController::class, 'update'])->name('checklist.pengambilan.update');
