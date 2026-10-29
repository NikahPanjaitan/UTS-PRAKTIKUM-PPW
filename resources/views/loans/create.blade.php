@extends('layouts.app')

@section('title', 'Formulir Pengajuan Peminjaman Aset - DEL-LEATS')

@section('content')
<section style="max-width: 700px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('equipments.index') }}" style="color: #2563eb; text-decoration: none; font-size: 0.9rem; font-weight: 600;">
            &larr; Kembali ke Katalog Aset
        </a>
        <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-top: 0.5rem;">Formulir Pengajuan Peminjaman</h1>
        <p style="color: #64748b; font-size: 0.95rem;">Lengkapi data peminjaman di bawah ini untuk diverifikasi oleh staf laboratorium.</p>
    </div>

    <div class="card">
        <!-- Formulir Pengajuan Transaksi Peminjaman (Metode POST) -->
        <form action="{{ route('loans.store') }}" method="POST">
            @csrf

            <!-- 1. Pilihan Perangkat -->
            <div class="form-group">
                <label for="equipment_id">Pilih Perangkat Laboratorium <span style="color: #dc2626;">*</span></label>
                <select name="equipment_id" id="equipment_id" class="form-control">
                    <option value="">-- Pilih Perangkat yang Ingin Dipinjam --</option>
                    @foreach ($equipments as $eq)
                        <option value="{{ $eq->id }}" {{ old('equipment_id', $selectedEquipmentId) == $eq->id ? 'selected' : '' }}>
                            {{ $eq->asset_code }} - {{ $eq->name }} (Status: {{ strtoupper($eq->status) }} | Rp {{ number_format($eq->daily_rental_fee, 0, ',', '.') }}/hari)
                        </option>
                    @endforeach
                </select>
                @error('equipment_id')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- 2. Nama Lengkap Peminjam -->
            <div class="form-group">
                <label for="borrower_name">Nama Lengkap Mahasiswa <span style="color: #dc2626;">*</span></label>
                <input type="text" name="borrower_name" id="borrower_name" class="form-control" placeholder="Contoh: Johannes Panjaitan" value="{{ old('borrower_name') }}">
                @error('borrower_name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- 3. NIM Mahasiswa -->
            <div class="form-group">
                <label for="borrower_nim">NIM Mahasiswa (Format Del: 8 Karakter) <span style="color: #dc2626;">*</span></label>
                <input type="text" name="borrower_nim" id="borrower_nim" class="form-control" placeholder="Contoh: 12S21001" value="{{ old('borrower_nim') }}" maxlength="8">
                <small style="color: #64748b; font-size: 0.8rem;">Wajib tepat 8 digit angka/huruf institusi IT Del</small>
                @error('borrower_nim')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- 4. Alamat Surel / Email -->
            <div class="form-group">
                <label for="borrower_email">Alamat Surel Mahasiswa <span style="color: #dc2626;">*</span></label>
                <input type="text" name="borrower_email" id="borrower_email" class="form-control" placeholder="Contoh: mahasiswa@students.del.ac.id" value="{{ old('borrower_email') }}">
                @error('borrower_email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- 5. Tanggal Pinjam & Rencana Tanggal Kembali -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="loan_date">Tanggal Mulai Pinjam <span style="color: #dc2626;">*</span></label>
                    <input type="date" name="loan_date" id="loan_date" class="form-control" value="{{ old('loan_date') }}">
                    @error('loan_date')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="expected_return_date">Rencana Tanggal Kembali <span style="color: #dc2626;">*</span></label>
                    <input type="date" name="expected_return_date" id="expected_return_date" class="form-control" value="{{ old('expected_return_date') }}">
                    <small style="color: #64748b; font-size: 0.8rem;">Maksimal durasi 7 hari kalender</small>
                    @error('expected_return_date')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- 6. Tujuan Peminjaman -->
            <div class="form-group">
                <label for="purpose">Tujuan & Keperluan Peminjaman <span style="color: #dc2626;">*</span></label>
                <textarea name="purpose" id="purpose" class="form-control" rows="3" placeholder="Contoh: Untuk keperluan pengujian modul sensor praktikum IoT tugas akhir">{{ old('purpose') }}</textarea>
                @error('purpose')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-success" style="flex: 1; padding: 0.75rem;">
                    🚀 Kirim Pengajuan Peminjaman
                </button>
                <a href="{{ route('equipments.index') }}" class="btn btn-outline" style="padding: 0.75rem 1.5rem;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
