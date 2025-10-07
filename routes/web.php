
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Mahasiswa\PenghargaanPrestasiController;
use App\Http\Controllers\Mahasiswa\FormSkpiController;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// HomePage
Route::get('/', function () {
    return view('home');
});

// Auth routes
Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Dashboard routes
Route::get('/mahasiswa', function () {
    return view('mahasiswa.dashboard');
})->name('mahasiswa.dashboard')->middleware('auth');

Route::get('/mahasiswa/formskpi', [FormSkpiController::class, 'index'])->name('mahasiswa.formskpi')->middleware('auth');

Route::get('/admin/prodi', function () {
    return view('admin.prodi.dashboard');
})->name('prodi.dashboard')->middleware('auth');

Route::get('/admin/fakultas', function () {
    return view('admin.fakultas.dashboard');
})->name('fakultas.dashboard')->middleware('auth');

Route::get('/mahasiswa/biodata', [App\Http\Controllers\Mahasiswa\BiodataController::class, 'index'])->name('mahasiswa.biodata')->middleware('auth');
Route::put('/mahasiswa/biodata', [App\Http\Controllers\Mahasiswa\BiodataController::class, 'update'])->name('mahasiswa.biodata.update')->middleware('auth');

    Route::resource('admin/fakultas/prodi', App\Http\Controllers\Admin\ProdiController::class)
    ->middleware('auth')
    ->names([
        'index' => 'admin.fakultas.prodi',
        'create' => 'admin.fakultas.prodi.create',
        'store' => 'admin.fakultas.prodi.store',
        'show' => 'admin.fakultas.prodi.show',
        'edit' => 'admin.fakultas.prodi.edit',
        'update' => 'admin.fakultas.prodi.update',
        'destroy' => 'admin.fakultas.prodi.destroy',
    ]);

    // Routes untuk manajemen admin prodi
    Route::middleware(['auth', 'role:admin_fakultas'])->group(function () {
        Route::resource('admin/fakultas/manajemenuser', \App\Http\Controllers\Admin\Fakultas\UserManagementController::class)
            ->except(['show', 'create', 'edit'])
            ->names([
                'index' => 'admin.fakultas.manajemenuser.index',
                'store' => 'admin.fakultas.manajemenuser.store',
                'update' => 'admin.fakultas.manajemenuser.update',
                'destroy' => 'admin.fakultas.manajemenuser.destroy'
            ]);
    });    // Route untuk verifikasi SKPI Prodi
Route::middleware(['auth', 'role:admin_prodi'])->group(function () {
    Route::get('/prodi/verifikasi', function () {
        // Eager load biodataMahasiswa dan pengajuanSkpi
        // Debug: tampilkan prodi_id admin
        $adminProdiId = auth()->user()->prodi_id;
        Log::info('Admin prodi_id: ' . $adminProdiId);

        if (!$adminProdiId) {
            Log::warning('Admin tidak memiliki prodi_id yang valid');
            return redirect()->back()->with('error', 'Anda belum ditugaskan ke prodi manapun. Silahkan hubungi admin fakultas.');
        }

        // Query mahasiswa: filter berdasarkan prodi_id dari biodata_mahasiswa (fallback jika kolom users.prodi_id kosong)
        $mahasiswasQuery = \App\Models\User::where('role', 'mahasiswa')
            ->where(function($q) use ($adminProdiId) {
                $q->where('prodi_id', $adminProdiId)
                  ->orWhereHas('biodataMahasiswa', function($qb) use ($adminProdiId) {
                      $qb->where('prodi_id', $adminProdiId);
                  });
            });

        // Debug: tampilkan jumlah total mahasiswa sebelum dan sesudah filter
        Log::info('Total mahasiswa (sebelum filter): ' . \App\Models\User::where('role', 'mahasiswa')->count());

        $mahasiswas = $mahasiswasQuery
            ->with(['biodataMahasiswa', 'pengajuanSkpi', 'prestasi', 'organisasi', 'kompetensiBahasa', 'magang', 'kompetensiKeagamaan'])
            ->get();
        Log::info('Mahasiswa (setelah filter by prodi via users/biodata): ' . $mahasiswas->count());
            
        // Debug: tampilkan jumlah mahasiswa setelah filter dan dump query
        Log::info('Mahasiswa dengan prodi_id ' . $adminProdiId . ': ' . $mahasiswas->count());
        Log::info('SQL Query: ' . $mahasiswasQuery->toSql());
        Log::info('Query Bindings: ' . json_encode($mahasiswasQuery->getBindings()));
        
        return view('admin.prodi.verifikasi', compact('mahasiswas'));
    })->name('prodi.verifikasi');
    
    Route::post('/prodi/verifikasi/{id}', [\App\Http\Controllers\Admin\VerifikasiController::class, 'verifikasi'])
        ->name('prodi.verifikasi.aksi');

    // Terima seluruh SKPI mahasiswa (jika semua komponen sudah disetujui)
    Route::post('/prodi/terima-mahasiswa/{userId}', [\App\Http\Controllers\Admin\VerifikasiController::class, 'terimaMahasiswa'])
        ->name('prodi.verifikasi.terimaMahasiswa');

    // Routes untuk kelola mahasiswa
    Route::resource('prodi/mahasiswa', \App\Http\Controllers\MahasiswaProdiController::class)
        ->names([
            'index' => 'prodi.mahasiswa.index',
            'create' => 'prodi.mahasiswa.create',
            'store' => 'prodi.mahasiswa.store',
            'edit' => 'prodi.mahasiswa.edit',
            'update' => 'prodi.mahasiswa.update',
            'destroy' => 'prodi.mahasiswa.destroy'
        ]);

    // Generate SKPI
    Route::get('/prodi/generate-skpi', [\App\Http\Controllers\Admin\GenerateSkpiController::class, 'index'])
        ->name('prodi.generateskpi');
    Route::post('/prodi/generate-skpi/{userId}', [\App\Http\Controllers\Admin\GenerateSkpiController::class, 'generate'])
        ->name('prodi.generateskpi.generate');
});

Route::get('/mahasiswa/skpi', [FormSkpiController::class, 'index'])->name('mahasiswa.skpi');



Route::post('/mahasiswa/penghargaan', [FormSkpiController::class, 'storePenghargaan'])->name('penghargaan.store');
Route::post('/mahasiswa/organisasi', [FormSkpiController::class, 'storeOrganisasi'])->name('organisasi.store');
Route::post('/mahasiswa/organisasi/{id}/update', [FormSkpiController::class, 'updateOrganisasi'])->name('organisasi.update');
Route::post('/mahasiswa/kompetensi-bahasa', [FormSkpiController::class, 'storeKompetensiBahasa'])->name('bahasa.store');
Route::post('/mahasiswa/kompetensi-bahasa/{id}/update', [FormSkpiController::class, 'updateKompetensiBahasa'])->name('bahasa.update');
Route::post('/mahasiswa/magang', [FormSkpiController::class, 'storeMagang'])->name('magang.store');
Route::post('/mahasiswa/keagamaan', [FormSkpiController::class, 'storeKeagamaan'])->name('keagamaan.store');
Route::get('/mahasiswa/penghargaan/{id}/edit', [FormSkpiController::class, 'editPenghargaan'])->name('penghargaan.edit');
Route::post('/mahasiswa/penghargaan/{id}/update', [FormSkpiController::class, 'updatePenghargaan'])->name('penghargaan.update');
Route::delete('/mahasiswa/penghargaan/{id}', [FormSkpiController::class, 'destroyPenghargaan'])->name('penghargaan.destroy');
Route::post('/mahasiswa/magang/{id}/update', [FormSkpiController::class, 'updateMagang'])->name('magang.update');
Route::post('/mahasiswa/keagamaan/{id}/update', [FormSkpiController::class, 'updateKeagamaan'])->name('keagamaan.update');