<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\MapPointController;
use App\Http\Controllers\Admin\DashboardContentController;
use App\Models\DashboardContent;

Route::get('/', function () {
    $content = DashboardContent::firstOrCreate(['id' => 1], DashboardContent::defaults());
    return view('public.dashboard', compact('content'));
});

Route::get('/dashboard', function () {
    $content = DashboardContent::firstOrCreate(['id' => 1], DashboardContent::defaults());
    return view('public.dashboard', compact('content'));
});

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login']);
});

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('admin.logout');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::view('/admin/map', 'admin.map');

    // ✅ simpan konten dashboard (inline di halaman publik)
    Route::post('/admin/dashboard-content', [DashboardContentController::class, 'update'])
        ->name('admin.dashboard-content.update');
});

Route::prefix('api')->middleware(['web'])->group(function () {
    Route::get('map-points', [MapPointController::class, 'index']);

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('proker-points', [MapPointController::class, 'storeProkerPoint']);
        Route::delete('proker-points/{seq}', [MapPointController::class, 'destroyProkerPoint']);
        Route::patch('admin/clear-all-points', [MapPointController::class, 'clearAllPoints']);
    });
});

// ROUTE HALAMAN DETAIL (Edukasi & Dokumentasi)
Route::get('/detail/{type}/{index}', function ($type, $index) {
    // 1. Ambil data konten
    $content = DashboardContent::first();
    
    // 2. Tentukan array mana yang diambil
    $items = [];
    $category = '';
    
    if ($type === 'edukasi') {
        $items = $content->edukasi_cards ?? [];
        $category = 'Edukasi Singkat';
    } elseif ($type === 'dokumentasi') {
        $items = $content->dokumentasi_cards ?? [];
        $category = 'Dokumentasi Kegiatan';
    } else {
        abort(404);
    }

    // 3. Cek apakah index ada
    if (!isset($items[$index])) {
        abort(404);
    }

    $item = $items[$index];

    // 4. Tampilkan View Detail
    return view('public.detail', [
        'item' => $item,
        'category' => $category,
        'type' => $type
    ]);
})->name('public.detail');
