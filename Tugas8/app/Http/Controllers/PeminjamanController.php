<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeminjamanController extends Controller
{
    public function index()
    {
        return view('peminjaman.index');
    }

    public function getData()
    {
        $peminjaman = Peminjaman::orderBy('created_at', 'desc')->get();
        return response()->json($peminjaman);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_peminjam' => 'required|string|max:100',
            'nomor_telepon' => 'required|string|max:20',
            'judul_buku' => 'required|string|max:200',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:Dipinjam,Dikembalikan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $peminjaman = Peminjaman::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $peminjaman
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data'
            ], 500);
        }
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::where('id_peminjaman', $id)->first();
        
        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $peminjaman
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'nama_peminjam' => 'required|string|max:100',
            'nomor_telepon' => 'required|string|max:20',
            'judul_buku' => 'required|string|max:200',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:Dipinjam,Dikembalikan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $peminjaman = Peminjaman::where('id_peminjaman', $request->id_peminjaman)->first();
            $peminjaman->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $peminjaman
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data'
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $peminjaman = Peminjaman::where('id_peminjaman', $request->id)->first();
            
            if (!$peminjaman) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $peminjaman->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data'
            ], 500);
        }
    }
}