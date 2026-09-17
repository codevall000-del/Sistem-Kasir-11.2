<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // Mengambil semua data barang
    public function index()
    {
        return response()->json(Barang::all(), 200);
    }

    // Menyimpan data barang baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'harga'       => 'required|numeric',
            'stok'        => 'required|numeric',
        ]);

        $barang = Barang::create([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'harga'       => $request->harga,
            'stok'        => $request->stok,
        ]);

        return response()->json($barang, 201);
    }

    // ----------------------------------------------------
    // INI ADALAH FUNGSI UPDATE YANG BARU KITA TAMBAHKAN
    // ----------------------------------------------------
    public function update(Request $request, $id)
    {
        // 1. Cari barang berdasarkan ID
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        // 2. Validasi inputan baru
        $request->validate([
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'harga'       => 'required|numeric',
            'stok'        => 'required|numeric',
        ]);

        // 3. Update datanya
        $barang->update([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'harga'       => $request->harga,
            'stok'        => $request->stok,
        ]);

        return response()->json(['message' => 'Barang berhasil diperbarui', 'data' => $barang], 200);
    }
    // ----------------------------------------------------

    // Menghapus data barang
    public function destroy($id)
    {
        $barang = Barang::find($id);
        if ($barang) {
            $barang->delete();
            return response()->json(['message' => 'Barang berhasil dihapus'], 200);
        }
        return response()->json(['message' => 'Barang tidak ditemukan'], 404);
    }
}