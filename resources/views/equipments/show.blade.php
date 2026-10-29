@extends('layouts.app')

@section('title', 'Detail Perangkat - ' . $equipment->name)

@section('content')
<section style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('equipments.index') }}" style="color: #2563eb; text-decoration: none; font-size: 0.9rem; font-weight: 600;">
            &larr; Kembali ke Katalog Aset
        </a>
        <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-top: 0.5rem;">Detail Spesifikasi Perangkat</h1>
    </div>

    <article class="card" style="padding: 0; overflow: hidden;">
        @if ($equipment->image_path)
            <img src="{{ asset('storage/' . $equipment->image_path) }}" alt="{{ $equipment->name }}" style="width: 100%; max-height: 360px; object-fit: cover; background: #f1f5f9;">
        @else
            <div style="height: 220px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #94a3b8;">
                📷
            </div>
        @endif

        <div style="padding: 1.75rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap;">
                <div>
                    <div class="asset-code" style="font-size: 0.9rem;">{{ $equipment->asset_code }}</div>
                    <h2 style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-top: 0.25rem;">{{ $equipment->name }}</h2>
                    <p style="color: #64748b; font-size: 0.95rem; margin-top: 0.25rem;">
                        Kategori: <strong>{{ $equipment->category->name }}</strong>
                    </p>
                </div>

                <div>
                    <span class="badge badge-{{ $equipment->status }}" style="font-size: 0.9rem; padding: 0.4rem 0.9rem;">
                        Status: {{ str_replace('_', ' ', $equipment->status) }}
                    </span>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 1.5rem 0;">

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <h3 style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Tarif Kompensasi Sewa</h3>
                    <p style="font-size: 1.25rem; font-weight: 800; color: #0284c7; margin-top: 0.25rem;">
                        Rp {{ number_format($equipment->daily_rental_fee, 0, ',', '.') }} <span style="font-size: 0.85rem; font-weight: 400; color: #64748b;">/ hari</span>
                    </p>
                </div>

                <div>
                    <h3 style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Kondisi Fisik Saat Ini</h3>
                    <p style="font-size: 1.1rem; font-weight: 600; color: #334155; margin-top: 0.25rem; text-transform: capitalize;">
                        {{ str_replace('_', ' ', $equipment->condition) }}
                    </p>
                </div>
            </div>

            <div>
                <h3 style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem;">Deskripsi & Spesifikasi Teknis</h3>
                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; color: #334155; line-height: 1.6; white-space: pre-line;">
                    {{ $equipment->specification }}
                </div>
            </div>

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <a href="{{ route('equipments.index') }}" class="btn btn-outline" style="padding: 0.75rem 1.5rem;">
                    &larr; Kembali
                </a>

                @if ($equipment->status === 'tersedia')
                    <a href="{{ route('loans.create', ['equipment_id' => $equipment->id]) }}" class="btn btn-success" style="flex: 1; padding: 0.75rem;">
                        📝 Ajukan Peminjaman Perangkat Ini
                    </a>
                @else
                    <button type="button" class="btn btn-disabled" style="flex: 1; padding: 0.75rem;" disabled>
                        Perangkat Sedang Tidak Tersedia untuk Dipinjam
                    </button>
                @endif
            </div>
        </div>
    </article>
</section>
@endsection
