<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DEL-LEATS - Lab Equipment & Asset Tracking System')</title>
    <style>
        /* CSS Murni & Mandiri (Bekerja 100% Offline Tanpa Kebutuhan Internet) */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: #1e293b;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* 1. Header & Navigasi Semantik HTML5 */
        header {
            background-color: #1e3a8a; /* Del Navy Blue */
            color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .brand-title {
            font-size: 1.25rem;
            font-weight: 700;
            text-decoration: none;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        nav a {
            color: #e2e8f0;
            text-decoration: none;
            padding: 0.5rem 0.85rem;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: background-color 0.2s, color 0.2s;
        }

        nav a:hover, nav a.active {
            background-color: #3b82f6;
            color: #ffffff;
        }

        /* 2. Main Kontainer */
        main {
            max-width: 1200px;
            width: 100%;
            margin: 1.5rem auto;
            padding: 0 1.5rem;
            flex: 1;
        }

        /* Notifikasi Flash Session */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            line-height: 1.5;
            border: 1px solid transparent;
        }

        .alert-success {
            background-color: #DCFCE7;
            color: #166534;
            border-color: #86efac;
        }

        .alert-danger {
            background-color: #FEE2E2;
            color: #991B1B;
            border-color: #fca5a5;
        }

        /* Status Badge Standar Soal Ujian */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.65rem;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: capitalize;
        }

        .badge-tersedia {
            background-color: #DCFCE7;
            color: #166534;
        }

        .badge-dipinjam {
            background-color: #FEF9C3;
            color: #854D0E;
        }

        .badge-dalam_perbaikan {
            background-color: #FEE2E2;
            color: #991B1B;
        }

        /* Form Styling */
        .card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: #334155;
        }

        .form-control {
            width: 100%;
            padding: 0.6rem 0.75rem;
            font-size: 0.95rem;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-error {
            color: #dc2626;
            font-size: 0.825rem;
            margin-top: 0.35rem;
            font-weight: 500;
        }

        /* Tata Letak Responsif Grid Kartu Aset */
        /* Desktop: Minimal 3 Kolom */
        .equipments-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        /* Tablet: 2 Kolom */
        @media (max-width: 992px) {
            .equipments-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Ponsel: 1 Kolom */
        @media (max-width: 640px) {
            .equipments-grid {
                grid-template-columns: 1fr;
            }
            .header-container {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        /* Kartu Aset (Article Semantik) */
        article.asset-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        article.asset-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .asset-card-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background-color: #f1f5f9;
            border-bottom: 1px solid #e2e8f0;
        }

        .asset-card-body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .asset-code {
            font-size: 0.8rem;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 0.05em;
            margin-bottom: 0.3rem;
        }

        .asset-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .asset-meta {
            font-size: 0.875rem;
            color: #475569;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .asset-fee {
            font-size: 1rem;
            font-weight: 700;
            color: #0284c7;
            margin-bottom: 1rem;
        }

        .asset-actions {
            margin-top: auto;
            display: flex;
            gap: 0.5rem;
        }

        /* Tombol */
        .btn {
            display: inline-block;
            padding: 0.55rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: background-color 0.2s;
        }

        .btn-primary {
            background-color: #2563eb;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
        }

        .btn-success {
            background-color: #16a34a;
            color: #ffffff;
        }

        .btn-success:hover {
            background-color: #15803d;
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid #cbd5e1;
            color: #334155;
        }

        .btn-outline:hover {
            background-color: #f1f5f9;
        }

        .btn-disabled {
            background-color: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
        }

        /* Paginasi */
        .pagination-container {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            list-style: none;
            gap: 0.35rem;
        }

        .pagination li a, .pagination li span {
            padding: 0.5rem 0.85rem;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        .pagination li.active span {
            background-color: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        /* 3. Footer Semantik */
        footer {
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            text-align: center;
            padding: 1.5rem;
            font-size: 0.875rem;
            margin-top: auto;
        }
    </style>
</head>
<body>

    <!-- Header & Navigasi Semantik HTML5 -->
    <header>
        <div class="header-container">
            <a href="{{ route('equipments.index') }}" class="brand-title">
                🏢 DEL-LEATS
            </a>
            <nav>
                <ul>
                    <li>
                        <a href="{{ route('equipments.index') }}" class="{{ request()->routeIs('equipments.index') ? 'active' : '' }}">
                            Katalog Aset
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('equipments.create') }}" class="{{ request()->routeIs('equipments.create') ? 'active' : '' }}">
                            Tambah Aset
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('loans.create') }}" class="{{ request()->routeIs('loans.create') ? 'active' : '' }}">
                            Ajukan Peminjaman
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Konten Utama Semantik HTML5 -->
    <main>
        <!-- Notifikasi Kilat (Flash Session Message) -->
        @if (session('success'))
            <div class="alert alert-success">
                <strong>[SUKSES]</strong> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <strong>[GAGAL]</strong> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer Semantik HTML5 -->
    <footer>
        <p>&copy; 2026 Institut Teknologi Del - Laboratorium Rekayasa Perangkat Lunak & Jaringan</p>
    </footer>

</body>
</html>
