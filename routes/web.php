<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\GuruLoginController;
use App\Http\Controllers\Auth\SiswaLoginController;

// Auth
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login');
Route::post('/guru/login', [GuruLoginController::class, 'login'])->name('guru.login');
Route::post('/siswa/login', [SiswaLoginController::class, 'login'])->name('siswa.login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->middleware('auth.admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    // Kelas
    Route::get('/kelas', [\App\Http\Controllers\Admin\KelasController::class, 'index'])->name('admin.kelas.index');
    Route::post('/kelas', [\App\Http\Controllers\Admin\KelasController::class, 'store'])->name('admin.kelas.store');
    Route::put('/kelas/{kela}', [\App\Http\Controllers\Admin\KelasController::class, 'update'])->name('admin.kelas.update');
    Route::delete('/kelas/{kela}', [\App\Http\Controllers\Admin\KelasController::class, 'destroy'])->name('admin.kelas.destroy');
    Route::post('/kelas/bulk-delete', [\App\Http\Controllers\Admin\KelasController::class, 'destroyBulk'])->name('admin.kelas.bulk-delete');

    // Mapel
    Route::get('/mapel', [\App\Http\Controllers\Admin\MapelController::class, 'index'])->name('admin.mapel.index');
    Route::post('/mapel', [\App\Http\Controllers\Admin\MapelController::class, 'store'])->name('admin.mapel.store');
    Route::put('/mapel/{mapel}', [\App\Http\Controllers\Admin\MapelController::class, 'update'])->name('admin.mapel.update');
    Route::delete('/mapel/{mapel}', [\App\Http\Controllers\Admin\MapelController::class, 'destroy'])->name('admin.mapel.destroy');
    Route::post('/mapel/bulk-delete', [\App\Http\Controllers\Admin\MapelController::class, 'destroyBulk'])->name('admin.mapel.bulk-delete');

    // Guru
    Route::get('/guru', [\App\Http\Controllers\Admin\GuruController::class, 'index'])->name('admin.guru.index');
    Route::post('/guru', [\App\Http\Controllers\Admin\GuruController::class, 'store'])->name('admin.guru.store');
    Route::put('/guru/{guru}', [\App\Http\Controllers\Admin\GuruController::class, 'update'])->name('admin.guru.update');
    Route::delete('/guru/{guru}', [\App\Http\Controllers\Admin\GuruController::class, 'destroy'])->name('admin.guru.destroy');
    Route::post('/guru/bulk-delete', [\App\Http\Controllers\Admin\GuruController::class, 'destroyBulk'])->name('admin.guru.bulk-delete');
    Route::post('/guru/reset-password', [\App\Http\Controllers\Admin\GuruController::class, 'resetPasswordBulk'])->name('admin.guru.reset-password');
    Route::post('/guru/import', [\App\Http\Controllers\Admin\GuruController::class, 'importExcel'])->name('admin.guru.import');
    Route::get('/guru/template', [\App\Http\Controllers\Admin\GuruController::class, 'downloadTemplate'])->name('admin.guru.template');

    // Siswa
    Route::get('/siswa', [\App\Http\Controllers\Admin\SiswaController::class, 'index'])->name('admin.siswa.index');
    Route::post('/siswa', [\App\Http\Controllers\Admin\SiswaController::class, 'store'])->name('admin.siswa.store');
    Route::put('/siswa/{siswa}', [\App\Http\Controllers\Admin\SiswaController::class, 'update'])->name('admin.siswa.update');
    Route::delete('/siswa/{siswa}', [\App\Http\Controllers\Admin\SiswaController::class, 'destroy'])->name('admin.siswa.destroy');
    Route::post('/siswa/bulk-delete', [\App\Http\Controllers\Admin\SiswaController::class, 'destroyBulk'])->name('admin.siswa.bulk-delete');
    Route::post('/siswa/reset-password', [\App\Http\Controllers\Admin\SiswaController::class, 'resetPasswordBulk'])->name('admin.siswa.reset-password');
    Route::post('/siswa/import', [\App\Http\Controllers\Admin\SiswaController::class, 'importExcel'])->name('admin.siswa.import');
    Route::get('/siswa/template', [\App\Http\Controllers\Admin\SiswaController::class, 'downloadTemplate'])->name('admin.siswa.template');

    // Relasi
    Route::get('/relasi', [\App\Http\Controllers\Admin\RelasiController::class, 'index'])->name('admin.relasi.index');
    Route::post('/relasi/{guru}', [\App\Http\Controllers\Admin\RelasiController::class, 'update'])->name('admin.relasi.update');

    // Bank Soal
    Route::get('/bank-soal', [\App\Http\Controllers\Admin\BankSoalController::class, 'index'])->name('admin.bank-soal.index');
    Route::post('/bank-soal', [\App\Http\Controllers\Admin\BankSoalController::class, 'store'])->name('admin.bank-soal.store');
    Route::put('/bank-soal/{bankSoal}', [\App\Http\Controllers\Admin\BankSoalController::class, 'update'])->name('admin.bank-soal.update');
    Route::delete('/bank-soal/{bankSoal}', [\App\Http\Controllers\Admin\BankSoalController::class, 'destroy'])->name('admin.bank-soal.destroy');
    Route::post('/bank-soal/arsipkan', [\App\Http\Controllers\Admin\BankSoalController::class, 'arsipkan'])->name('admin.bank-soal.arsipkan');
    Route::get('/arsip-bank-soal', [\App\Http\Controllers\Admin\BankSoalController::class, 'arsip'])->name('admin.bank-soal.arsip');
    Route::post('/arsip-bank-soal/aktifkan', [\App\Http\Controllers\Admin\BankSoalController::class, 'aktifkan'])->name('admin.bank-soal.aktifkan');
    Route::post('/arsip-bank-soal/hapus-permanen', [\App\Http\Controllers\Admin\BankSoalController::class, 'hapusPermanenBulk'])->name('admin.bank-soal.hapus-permanen');

    // Soal Editor
    Route::get('/bank-soal/{id}/edit-soal', [\App\Http\Controllers\Admin\SoalController::class, 'edit'])->name('admin.soal.edit');
    Route::post('/bank-soal/{id}/save-soal', [\App\Http\Controllers\Admin\SoalController::class, 'save'])->name('admin.soal.save');
    Route::post('/bank-soal/{id}/import-word', [\App\Http\Controllers\Admin\SoalController::class, 'importWord'])->name('admin.soal.import-word');
    Route::post('/bank-soal/{id}/import-excel', [\App\Http\Controllers\Admin\SoalController::class, 'importExcel'])->name('admin.soal.import-excel');

    // Ruang Ujian
    Route::get('/ruang-ujian', [\App\Http\Controllers\Admin\RuangUjianController::class, 'index'])->name('admin.ruang-ujian.index');
    Route::post('/ruang-ujian', [\App\Http\Controllers\Admin\RuangUjianController::class, 'store'])->name('admin.ruang-ujian.store');
    Route::put('/ruang-ujian/{ruangUjian}', [\App\Http\Controllers\Admin\RuangUjianController::class, 'update'])->name('admin.ruang-ujian.update');
    Route::delete('/ruang-ujian/{ruangUjian}', [\App\Http\Controllers\Admin\RuangUjianController::class, 'destroy'])->name('admin.ruang-ujian.destroy');
    Route::get('/ruang-ujian/{id}/monitoring', [\App\Http\Controllers\Admin\MonitoringController::class, 'index'])->name('admin.monitoring.index');
    Route::post('/ruang-ujian/{id}/reset-siswa', [\App\Http\Controllers\Admin\MonitoringController::class, 'resetSiswa'])->name('admin.monitoring.reset');
    Route::get('/ruang-ujian/{id}/export-excel', [\App\Http\Controllers\Admin\MonitoringController::class, 'exportExcel'])->name('admin.monitoring.export-excel');
    Route::get('/ruang-ujian/{id}/export-analisis', [\App\Http\Controllers\Admin\MonitoringController::class, 'exportAnalisis'])->name('admin.monitoring.export-analisis');

    // Pengumuman
    Route::get('/pengumuman', [\App\Http\Controllers\Admin\PengumumanController::class, 'index'])->name('admin.pengumuman.index');
    Route::post('/pengumuman', [\App\Http\Controllers\Admin\PengumumanController::class, 'store'])->name('admin.pengumuman.store');
    Route::put('/pengumuman/{pengumuman}', [\App\Http\Controllers\Admin\PengumumanController::class, 'update'])->name('admin.pengumuman.update');
    Route::delete('/pengumuman/{pengumuman}', [\App\Http\Controllers\Admin\PengumumanController::class, 'destroy'])->name('admin.pengumuman.destroy');

    // Administrator
    Route::get('/administrator', [\App\Http\Controllers\Admin\AdministratorController::class, 'index'])->name('admin.administrator.index');
    Route::post('/administrator', [\App\Http\Controllers\Admin\AdministratorController::class, 'store'])->name('admin.administrator.store');
    Route::put('/administrator/{administrator}', [\App\Http\Controllers\Admin\AdministratorController::class, 'update'])->name('admin.administrator.update');
    Route::delete('/administrator/{administrator}', [\App\Http\Controllers\Admin\AdministratorController::class, 'destroy'])->name('admin.administrator.destroy');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');

    // Password
    Route::post('/ubah-password', [\App\Http\Controllers\Admin\PasswordController::class, 'update'])->name('admin.password.update');
});

// Guru Routes
Route::prefix('guru')->middleware('auth.guru')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Guru\DashboardController::class, 'index'])->name('guru.dashboard');

    Route::get('/bank-soal', [\App\Http\Controllers\Guru\BankSoalController::class, 'index'])->name('guru.bank-soal.index');
    Route::post('/bank-soal', [\App\Http\Controllers\Guru\BankSoalController::class, 'store'])->name('guru.bank-soal.store');
    Route::put('/bank-soal/{id}', [\App\Http\Controllers\Guru\BankSoalController::class, 'update'])->name('guru.bank-soal.update');
    Route::delete('/bank-soal/{id}', [\App\Http\Controllers\Guru\BankSoalController::class, 'destroy'])->name('guru.bank-soal.destroy');
    Route::post('/bank-soal/arsipkan', [\App\Http\Controllers\Guru\BankSoalController::class, 'arsipkan'])->name('guru.bank-soal.arsipkan');
    Route::get('/arsip-bank-soal', [\App\Http\Controllers\Guru\BankSoalController::class, 'arsip'])->name('guru.bank-soal.arsip');
    Route::post('/arsip-bank-soal/aktifkan', [\App\Http\Controllers\Guru\BankSoalController::class, 'aktifkan'])->name('guru.bank-soal.aktifkan');
    Route::post('/arsip-bank-soal/hapus-permanen', [\App\Http\Controllers\Guru\BankSoalController::class, 'hapusPermanenBulk'])->name('guru.bank-soal.hapus-permanen');

    Route::get('/bank-soal/{id}/edit-soal', [\App\Http\Controllers\Guru\SoalController::class, 'edit'])->name('guru.soal.edit');
    Route::post('/bank-soal/{id}/save-soal', [\App\Http\Controllers\Guru\SoalController::class, 'save'])->name('guru.soal.save');
    Route::post('/bank-soal/{id}/import-word', [\App\Http\Controllers\Guru\SoalController::class, 'importWord'])->name('guru.soal.import-word');
    Route::post('/bank-soal/{id}/import-excel', [\App\Http\Controllers\Guru\SoalController::class, 'importExcel'])->name('guru.soal.import-excel');

    Route::get('/ruang-ujian', [\App\Http\Controllers\Guru\RuangUjianController::class, 'index'])->name('guru.ruang-ujian.index');
    Route::post('/ruang-ujian', [\App\Http\Controllers\Guru\RuangUjianController::class, 'store'])->name('guru.ruang-ujian.store');
    Route::put('/ruang-ujian/{id}', [\App\Http\Controllers\Guru\RuangUjianController::class, 'update'])->name('guru.ruang-ujian.update');
    Route::delete('/ruang-ujian/{id}', [\App\Http\Controllers\Guru\RuangUjianController::class, 'destroy'])->name('guru.ruang-ujian.destroy');
    Route::get('/ruang-ujian/{id}/monitoring', [\App\Http\Controllers\Guru\MonitoringController::class, 'index'])->name('guru.monitoring.index');
    Route::post('/ruang-ujian/{id}/reset-siswa', [\App\Http\Controllers\Guru\MonitoringController::class, 'resetSiswa'])->name('guru.monitoring.reset');
    Route::get('/ruang-ujian/{id}/export-excel', [\App\Http\Controllers\Guru\MonitoringController::class, 'exportExcel'])->name('guru.monitoring.export-excel');
    Route::get('/ruang-ujian/{id}/export-analisis', [\App\Http\Controllers\Guru\MonitoringController::class, 'exportAnalisis'])->name('guru.monitoring.export-analisis');

    Route::post('/ubah-password', [\App\Http\Controllers\Guru\PasswordController::class, 'update'])->name('guru.password.update');
});

// Siswa Routes
Route::prefix('siswa')->middleware('auth.siswa')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('siswa.dashboard');
    Route::get('/ujian', [\App\Http\Controllers\Siswa\UjianController::class, 'index'])->name('siswa.ujian');
    Route::post('/ujian/{id}/verify-token', [\App\Http\Controllers\Siswa\UjianController::class, 'verifyToken'])->name('siswa.ujian.verify');
    Route::get('/ujian/{id}/start', [\App\Http\Controllers\Siswa\UjianController::class, 'start'])->name('siswa.ujian.start');
    Route::post('/ujian/{id}/save-answer', [\App\Http\Controllers\Siswa\UjianController::class, 'saveAnswer'])->name('siswa.ujian.save-answer');
    Route::post('/ujian/{id}/submit', [\App\Http\Controllers\Siswa\UjianController::class, 'submit'])->name('siswa.ujian.submit');
    Route::get('/ujian/{id}/time-sync', [\App\Http\Controllers\Siswa\UjianController::class, 'timeSync'])->name('siswa.ujian.time-sync');
    Route::post('/ubah-password', [\App\Http\Controllers\Siswa\PasswordController::class, 'update'])->name('siswa.password.update');
});
