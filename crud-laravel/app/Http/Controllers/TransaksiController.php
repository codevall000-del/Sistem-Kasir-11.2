<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi; // <-- Jangan lupa import ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'bayar' => 'required|numeric',
            'items' => 'required|array'
        ]);

        return DB::transaction(function () use ($request) {
            $totalHarga = 0;

            // 1. Cek stok dan hitung total harga
            foreach ($request->items as $item) {
                $barang = Barang::findOrFail($item['id']);
                if ($barang->stok < $item['qty']) {
                    return response()->json(['message' => "Stok {$barang->nama_barang} tidak mencukupi!"], 400);
                }
                $totalHarga += $barang->harga * $item['qty'];
            }

            // 2. Cek apakah uang cukup
            if ($request->bayar < $totalHarga) {
                return response()->json(['message' => 'Uang pembayaran kurang!'], 400);
            }

            // 3. Simpan ke tabel Transaksi
            $transaksi = Transaksi::create([
                'no_nota' => 'TRX-' . time(),
                'total_harga' => $totalHarga,
                'bayar' => $request->bayar,
                'kembali' => $request->bayar - $totalHarga,
            ]);

            // 4. Kurangi stok barang & Simpan ke tabel Detail Transaksi
            foreach ($request->items as $item) {
                $barang = Barang::findOrFail($item['id']);
                
                // Kurangi stok
                $barang->decrement('stok', $item['qty']);

                // Simpan detail item yang dibeli
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'nama_barang' => $barang->nama_barang,
                    'qty' => $item['qty'],
                    'harga' => $barang->harga,
                ]);
            }

            return response()->json(['message' => 'Transaksi Berhasil', 'data' => $transaksi], 201);
        });
    }

    // Fungsi untuk mengambil data riwayat transaksi + detail barangnya
    public function index()
    {
        $transaksi = Transaksi::latest()->get();

        return response()->json($transaksi, 200);
    }
}