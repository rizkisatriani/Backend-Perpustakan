<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestBuku extends Controller
{
    public function index(Request $request)
    {
        $anggota= DB::table('request_buku')
        ->get();
        return response()->json(['data' => $anggota]);
    }

    public function create(Request $request)
    {
        DB::table('request_buku')->insert([
            'judul' => $request->judul,
            'nama_pengarang' => $request->nama_pengarang,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return response()->json(['message' => 'Anggota berhasil ditambah']);
    }

    public function destroy(Request $request)
    {
        DB::table('request_buku')
        ->where('id', $request->id)
        ->delete();
        return response()->json(['message' => 'Anggota berhasil di hapus']);
    }
}
