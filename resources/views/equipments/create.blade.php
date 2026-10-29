@extends('layouts.app')

@section('title', 'Pendaftaran Aset Laboratorium Baru - DEL-LEATS')

@section('content')
<section style="max-width: 700px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('equipments.index') }}" style="color: #2563eb; text-decoration: none; font-size: 0.9rem; font-weight: 600;">
            &larr; Kembali ke Katalog Aset
        </a>
        <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-top: 0.5rem;">Pendaftaran Inventaris Baru</h1>
        <p style="color: #64748b; font-size: 0.95rem;">Lengkapi informasi detail perangkat fisik laboratorium untuk dimasukkan ke katalog.</p>
    </div>

    <div class="card">
        <!-- Formulir Wajib Enctype Multipart untuk Upload Berkas Foto & Token CSRF -->
        <form action="{{ route('equipments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Kategori Perangkat -->
            <div class="form-group">
                <label for="category_id">Kategori Perangkat <span style="color: #dc2626;">*</span></label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->code }})
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Kode Aset Inventaris -->
            <div class="form-group">
                <label for="asset_code">Kode Aset Inventaris <span style="color: #dc2626;">*</span></label>
                <input type="text" name="asset_code" id="asset_code" class="form-control" placeholder="Contoh: EQ-IOT-005, EQ-SRV-003..." value="{{ old('asset_code') }}">
                <small style="color: #64748b; font-size: 0.8rem;">Format baku: EQ-[KODE_KAT]-[NOMOR]</small>
                @error('asset_code')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nama Perangkat -->
            <div class="form-group">
                <label for="name">Nama Perangkat <span style="color: #dc2626;">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Raspberry Pi 4 Model B Starter Kit" value="{{ old('name') }}">
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Kondisi Fisik -->
            <div class="form-group">
                <label for="condition">Kondisi Fisik Perangkat <span style="color: #dc2626;">*</span></label>
                <select name="condition" id="condition" class="form-control">
                    <option value="baik" {{ old('condition', 'baik') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ old('condition') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ old('condition') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
                @error('condition')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tarif Kompensasi Harian -->
            <div class="form-group">
                <label for="daily_rental_fee">Tarif Kompensasi Harian (Rupiah) <span style="color: #dc2626;">*</span></label>
                <input type="number" name="daily_rental_fee" id="daily_rental_fee" class="form-control" placeholder="Contoh: 15000" value="{{ old('daily_rental_fee') }}" min="0">
                @error('daily_rental_fee')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Spesifikasi Teknis -->
            <div class="form-group">
                <label for="specification">Spesifikasi Teknis Lengkap <span style="color: #dc2626;">*</span></label>
                <textarea name="specification" id="specification" class="form-control" rows="4" placeholder="Tuliskan spesifikasi detail prosesor, memori, kelengkapan aksesoris, dll...">{{ old('specification') }}</textarea>
                @error('specification')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Upload Berkas Foto Fisik -->
            <div class="form-group">
                <label for="image">Berkas Foto Fisik Perangkat <span style="color: #dc2626;">*</span></label>
                <input type="file" name="image" id="image" class="form-control" accept="image/jpeg,image/png,image/webp">
                <small style="color: #64748b; font-size: 0.8rem;">Format didukung: JPG, PNG, WEBP (Maksimal 2 MB)</small>
                @error('image')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tombol Aksi Simpan -->
            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1; padding: 0.75rem;">
                    💾 Daftarkan Aset ke Sistem
                </button>
                <a href="{{ route('equipments.index') }}" class="btn btn-outline" style="padding: 0.75rem 1.5rem;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
