<?php

use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

// Pengalihan dari halaman beranda langsung ke katalog peralatan
Route::get('/', function () {
    return redirect()->route('equipments.index');
});

// 1. GET /equipments -> Katalog berpaginasi, pencarian & filter
Route::get('/equipments', [EquipmentController::class, 'index'])->name('equipments.index');

// 2. GET /equipments/create -> Formulir tambah aset
Route::get('/equipments/create', [EquipmentController::class, 'create'])->name('equipments.create');

// 3. POST /equipments -> Validasi, upload foto, penyimpanan aset
Route::post('/equipments', [EquipmentController::class, 'store'])->name('equipments.store');

// 4. GET /equipments/{id} -> Detail spesifikasi & ketersediaan aset
Route::get('/equipments/{id}', [EquipmentController::class, 'show'])->name('equipments.show');

// 5. GET /loans/create -> Formulir pengajuan peminjaman aset
Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');

// 6. POST /loans -> Validasi aturan bisnis & transaksi peminjaman
Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
