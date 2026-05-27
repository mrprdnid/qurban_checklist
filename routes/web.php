<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HewanController;
use App\Http\Controllers\ChecklistKandangController;
use App\Http\Controllers\ChecklistSembelihController;
use App\Http\Controllers\ChecklistSapiController;
use App\Http\Controllers\ChecklistSesetController;
use App\Http\Controllers\ChecklistPengambilanController;
use App\Http\Controllers\ChecklistKehadiranController;
use App\Http\Controllers\HewanImportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Models\Hewan;
use App\Models\ChecklistKandang;
use App\Models\ChecklistSembelih;
use App\Models\ChecklistSapi;
use App\Models\ChecklistSeset;
use App\Models\ChecklistPengambilan;

// Guest only
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        $totalHewan = Hewan::count();
        $totalDomba = Hewan::where('jenis', 'domba')->count();
        $totalSapi = Hewan::where('jenis', 'sapi')->count();
        $totalPengambilan = ChecklistPengambilan::where('sudah_diambil', true)->count();

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
    Route::get('/hewan/{hewan}/journey', [HewanController::class, 'journey'])->name('hewan.journey');
    Route::get('/hewan-import', [HewanImportController::class, 'index'])->name('hewan.import');
    Route::post('/hewan-import', [HewanImportController::class, 'store'])->name('hewan.import.store');
    Route::get('/hewan-import/template', [HewanImportController::class, 'template'])->name('hewan.import.template');

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

    // Registrasi Kehadiran Pekurban
    Route::get('/checklist/kehadiran', [ChecklistKehadiranController::class, 'index'])->name('checklist.kehadiran');
    Route::get('/checklist/kehadiran/{hewan}', [ChecklistKehadiranController::class, 'show'])->name('checklist.kehadiran.show');
    Route::patch('/checklist/kehadiran/{hewan}', [ChecklistKehadiranController::class, 'update'])->name('checklist.kehadiran.update');
    Route::post('/checklist/kehadiran/{hewan}/kirim-wa', [ChecklistKehadiranController::class, 'kirimWa'])->name('checklist.kehadiran.kirim-wa');

    // Admin only
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
    });
});
