<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EquipmentController extends Controller
{
    /**
     * Menampilkan katalog aset dengan pencarian, filter, dan paginasi.
     */
    public function index(Request $request): View
    {
        $query = Equipment::with('category');

        // Pencarian kata kunci parsial pada name dan asset_code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('asset_code', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter berdasarkan status ketersediaan
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        // Tampilkan 6 kartu aset per halaman dengan mempertahankan query string
        $equipments = $query->latest('id')->paginate(6)->withQueryString();
        $categories = Category::all();

        return view('equipments.index', compact('equipments', 'categories'));
    }

    /**
     * Menampilkan formulir pendaftaran aset baru.
     */
    public function create(): View
    {
        $categories = Category::all();
        return view('equipments.create', compact('categories'));
    }

    /**
     * Memvalidasi input dan menyimpan aset baru beserta berkas foto fisik.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'asset_code' => 'required|unique:equipments,asset_code',
            'name' => 'required|min:5|max:150',
            'category_id' => 'required|exists:categories,id',
            'specification' => 'required|string',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
            'daily_rental_fee' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,webp|max:2048',
        ], [
            'asset_code.required' => 'Kode aset wajib diisi.',
            'asset_code.unique' => 'Kode aset ini sudah terdaftar.',
            'name.required' => 'Nama perangkat wajib diisi.',
            'name.min' => 'Nama perangkat minimal 5 karakter.',
            'name.max' => 'Nama perangkat maksimal 150 karakter.',
            'category_id.required' => 'Kategori perangkat wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'specification.required' => 'Spesifikasi perangkat wajib diisi.',
            'condition.required' => 'Kondisi fisik perangkat wajib dipilih.',
            'daily_rental_fee.required' => 'Tarif sewa harian wajib diisi.',
            'daily_rental_fee.numeric' => 'Tarif sewa harus berupa angka.',
            'daily_rental_fee.min' => 'Tarif sewa tidak boleh bernilai negatif.',
            'image.required' => 'Berkas foto fisik wajib diunggah.',
            'image.image' => 'Berkas yang diunggah harus berupa gambar.',
            'image.mimes' => 'Format gambar harus berupa jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal adalah 2048 KB.',
        ]);

        // Simpan berkas gambar ke storage/app/public/equipments dengan penamaan unik berbasis waktu
        $file = $request->file('image');
        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file->getClientOriginalName());
        $imagePath = $file->storeAs('equipments', $filename, 'public');

        $equipment = Equipment::create([
            'category_id' => $validated['category_id'],
            'asset_code' => $validated['asset_code'],
            'name' => $validated['name'],
            'specification' => $validated['specification'],
            'condition' => $validated['condition'],
            'status' => 'tersedia',
            'daily_rental_fee' => $validated['daily_rental_fee'],
            'image_path' => $imagePath,
        ]);

        // Pola Post-Redirect-Get (PRG) dengan Flash Session Notification
        return redirect()->route('equipments.index')->with(
            'success',
            "Perangkat '{$equipment->name}' (Kode: {$equipment->asset_code}) berhasil didaftarkan!"
        );
    }

    /**
     * Menampilkan detail spesifikasi dan ketersediaan aset.
     */
    public function show(string $id): View
    {
        $equipment = Equipment::with('category')->findOrFail($id);
        return view('equipments.show', compact('equipment'));
    }
}
