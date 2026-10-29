@extends('layouts.app')

@section('title', 'Katalog Aset Laboratorium - DEL-LEATS')

@section('content')
<section>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a;">Katalog Peralatan Laboratorium</h1>
            <p style="color: #64748b; font-size: 0.95rem;">Telusuri dan ajukan peminjaman perangkat teknologi laboratorium secara real-time.</p>
        </div>
        <div>
            <a href="{{ route('equipments.create') }}" class="btn btn-primary">
                + Tambah Aset Baru
            </a>
        </div>
    </div>

    <!-- Formulir Pencarian & Filter Multi-Kriteria (Metode GET) -->
    <div class="card">
        <form method="GET" action="{{ route('equipments.index') }}" style="display: grid; grid-template-columns: 2fr 1.5fr 1.5fr auto auto; gap: 1rem; align-items: end;">
            <!-- 1. Input Kata Kunci -->
            <div class="form-group" style="margin-bottom: 0;">
                <label for="search">Cari Nama / Kode Aset</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Contoh: Drone, Server, EQ-IOT..." value="{{ request('search') }}">
            </div>

            <!-- 2. Dropdown Filter Kategori -->
            <div class="form-group" style="margin-bottom: 0;">
                <label for="category_id">Kategori</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 3. Dropdown Filter Status Ketersediaan -->
            <div class="form-group" style="margin-bottom: 0;">
                <label for="status">Status Ketersediaan</label>
                <select name="status" id="status" class="form-control">
                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dalam_perbaikan" {{ request('status') == 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                </select>
            </div>

            <!-- Tombol Submit Pencarian -->
            <div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    🔍 Filter
                </button>
            </div>

            <!-- Tombol Reset Filter -->
            @if(request()->hasAny(['search', 'category_id', 'status']))
                <div>
                    <a href="{{ route('equipments.index') }}" class="btn btn-outline" style="width: 100%;">
                        Reset
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Keterangan Ringkasan Hasil Pencarian -->
    <div style="margin-bottom: 1rem; color: #475569; font-size: 0.95rem;">
        @if (request('search'))
            Menampilkan <strong>{{ $equipments->count() }}</strong> dari <strong>{{ $equipments->total() }}</strong> hasil pencarian untuk kata kunci <em>"{{ request('search') }}"</em>
        @else
            Menampilkan total <strong>{{ $equipments->total() }}</strong> unit perangkat laboratorium.
        @endif
    </div>

    <!-- Grid Kartu Aset Responsif HTML5 Semantik -->
    @if ($equipments->count() > 0)
        <div class="equipments-grid">
            @foreach ($equipments as $item)
                <article class="asset-card">
                    <!-- Foto Thumbnail Fisik Perangkat -->
                    @if ($item->image_path)
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="asset-card-img">
                    @else
                        <div class="asset-card-img" style="display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 2.5rem;">
                            📷
                        </div>
                    @endif

                    <div class="asset-card-body">
                        <div class="asset-code">{{ $item->asset_code }}</div>
                        <h2 class="asset-title">{{ $item->name }}</h2>

                        <div class="asset-meta">
                            <span>📂 {{ $item->category->name }}</span>
                            <!-- Status Badge Berwarna Kontras Sesuai Soal -->
                            <span class="badge badge-{{ $item->status }}">
                                {{ str_replace('_', ' ', $item->status) }}
                            </span>
                        </div>

                        <div class="asset-fee">
                            Rp {{ number_format($item->daily_rental_fee, 0, ',', '.') }} <span style="font-size: 0.8rem; font-weight: 400; color: #64748b;">/ hari</span>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="asset-actions">
                            <a href="{{ route('equipments.show', $item->id) }}" class="btn btn-outline" style="flex: 1;">
                                Detail Spesifikasi
                            </a>

                            @if ($item->status === 'tersedia')
                                <a href="{{ route('loans.create', ['equipment_id' => $item->id]) }}" class="btn btn-success" style="flex: 1;">
                                    Ajukan Pinjam
                                </a>
                            @else
                                <button type="button" class="btn btn-disabled" style="flex: 1;" disabled>
                                    Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Tautan Paginasi Otomatis (Mempertahankan Query String URL) -->
        <div class="pagination-container">
            {{ $equipments->links() }}
        </div>
    @else
        <div class="card" style="text-align: center; padding: 3rem 1rem; color: #64748b;">
            <p style="font-size: 1.1rem; margin-bottom: 0.5rem;">Tidak ada perangkat yang sesuai dengan kriteria filter Anda.</p>
            <a href="{{ route('equipments.index') }}" class="btn btn-primary" style="margin-top: 0.5rem;">
                Lihat Seluruh Katalog
            </a>
        </div>
    @endif
</section>
@endsection
