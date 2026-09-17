<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Data;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Data::all();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik'      => 'required|string|max:255',
            'nama'     => 'required|string|max:255',
            'telepon'  => 'required|string|max:255',
            'alamat'   => 'required|string',
        ]);

        $data = Data::create($validated);
        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */

        public function show(string $id)
    {
        $data = Data::findOrFail($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Data::findOrFail($id);

        $validated = $request->validate([
            'nik'      => 'required|string|max:255',
            'nama'     => 'required|string|max:255',
            'telepon'  => 'required|string|max:255',
            'alamat'   => 'required|string',
        ]);

        $data->update($validated);
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Data::findOrFail($id);

        $data->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}