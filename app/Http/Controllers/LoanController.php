<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\LoanRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoanController extends Controller
{
    /**
     * Menampilkan formulir pengajuan peminjaman aset.
     */
    public function create(Request $request): View
    {
        $equipments = Equipment::with('category')->get();
        $selectedEquipmentId = $request->query('equipment_id');

        return view('loans.create', compact('equipments', 'selectedEquipmentId'));
    }

    /**
     * Memvalidasi aturan bisnis dan menyimpan transaksi peminjaman.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi format dasar input formulir
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'borrower_name' => 'required|min:3',
            'borrower_nim' => 'required|regex:/^[0-9A-Za-z]{8}$/',
            'borrower_email' => 'required|email',
            'loan_date' => 'required|date|after_or_equal:today',
            'expected_return_date' => 'required|date|after_or_equal:loan_date',
            'purpose' => 'required|string',
        ], [
            'equipment_id.required' => 'Perangkat wajib dipilih.',
            'equipment_id.exists' => 'Perangkat yang dipilih tidak terdaftar di sistem.',
            'borrower_name.required' => 'Nama peminjam wajib diisi.',
            'borrower_name.min' => 'Nama peminjam wajib diisi minimal 3 karakter.',
            'borrower_nim.required' => 'NIM peminjam wajib diisi.',
            'borrower_nim.regex' => 'Format NIM harus terdiri dari tepat 8 digit angka/huruf institusi.',
            'borrower_email.required' => 'Alamat surel wajib diisi.',
            'borrower_email.email' => 'Format alamat surel yang Anda masukkan tidak valid.',
            'loan_date.required' => 'Tanggal pinjam wajib diisi.',
            'loan_date.after_or_equal' => 'Tanggal peminjaman tidak boleh sebelum tanggal hari ini.',
            'expected_return_date.required' => 'Rencana tanggal kembali wajib diisi.',
            'expected_return_date.after_or_equal' => 'Tanggal kembali harus sama atau setelah tanggal pinjam.',
            'purpose.required' => 'Tujuan peminjaman wajib diisi.',
        ]);

        $equipment = Equipment::findOrFail($validated['equipment_id']);

        // 2. Validasi Aturan Bisnis Ketersediaan Perangkat
        if ($equipment->status !== 'tersedia') {
            return back()
                ->withInput()
                ->withErrors([
                    'equipment_id' => "Perangkat {$equipment->name} saat ini sedang dipinjam dan tidak tersedia.",
                ])
                ->with('error', 'Pengajuan tidak dapat diproses. Silakan periksa kembali seluruh masukan data Anda.');
        }

        // 3. Validasi Aturan Bisnis Durasi Sewa Maksimal 7 Hari Kalender
        $loanDate = Carbon::parse($validated['loan_date']);
        $returnDate = Carbon::parse($validated['expected_return_date']);
        $durationDays = $loanDate->diffInDays($returnDate);

        if ($durationDays > 7) {
            return back()
                ->withInput()
                ->withErrors([
                    'expected_return_date' => 'Durasi peminjaman maksimal adalah 7 hari kalender.',
                ])
                ->with('error', 'Pengajuan tidak dapat diproses. Silakan periksa kembali seluruh masukan data Anda.');
        }

        // Hitung total estimasi biaya (minimal 1 hari sewa jika dipinjam dan kembali pada hari yang sama)
        $chargeDays = max(1, $durationDays);
        $totalEstimatedFee = $chargeDays * $equipment->daily_rental_fee;

        // 4. Eksekusi Transaksi Mutasi Status & Penyimpanan (ACID Database Transaction)
        DB::transaction(function () use ($validated, $equipment, $totalEstimatedFee) {
            LoanRequest::create([
                'equipment_id' => $equipment->id,
                'borrower_name' => $validated['borrower_name'],
                'borrower_nim' => $validated['borrower_nim'],
                'borrower_email' => $validated['borrower_email'],
                'loan_date' => $validated['loan_date'],
                'expected_return_date' => $validated['expected_return_date'],
                'purpose' => $validated['purpose'],
                'status' => 'diajukan',
                'total_estimated_fee' => $totalEstimatedFee,
            ]);

            // Mutasi status perangkat menjadi dipinjam
            $equipment->update([
                'status' => 'dipinjam',
            ]);
        });

        // Pola Post-Redirect-Get (PRG)
        return redirect()->route('equipments.index')->with(
            'success',
            "Pengajuan peminjaman untuk perangkat '{$equipment->name}' berhasil dikirimkan!"
        );
    }
}
