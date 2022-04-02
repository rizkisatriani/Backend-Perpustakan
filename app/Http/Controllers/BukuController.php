<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BukuController extends Controller
{
    public function get(Request $request)
    {
       $buku= DB::table('buku')
        ->where('judul', 'like', '%'.$request->q.'%')
        ->get();
        return response()->json(['data' => $buku]);
    }

    public function getMobile(Request $request)
    {
        $peminjaman= DB::table('peminjaman')
        ->where('peminjaman.tanggal_kembali',null)
         ->get();
         if($peminjaman->count()>0){
            return response()->json(['status'=>1,'data' => []]);
         }
       $buku= DB::table('buku')
       ->where('judul', 'like', '%'.$request->q.'%')
        ->get();
        return response()->json(['status'=>0,'data' => $buku]);
    }

    public function add(Request $request)
    {
        $file = $request->file('cover');
        $tujuan_upload = 'data_file';
        $file->move($tujuan_upload,$file->getClientOriginalName());

        DB::table('buku')->insert([
            'judul' => $request->judul,
            'nama_pengarang' => $request->nama_pengarang,
            'penerbit' => $request->penerbit,
            'cover_buku' => 'data_file/'.$file->getClientOriginalName(),
            'tahun_terbit' => $request->tahun_terbit,
            'stock' => $request->stock,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return response()->json(['message' => 'Buku berhasil ditambah']);
    }

    public function edit(Request $request)
    {
        $file = $request->file('cover');
        if($file){
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload,$file->getClientOriginalName());
        }

        DB::table('buku')
        ->where('id', $request->id)
        ->update([
            'judul' => $request->judul,
            'nama_pengarang' => $request->nama_pengarang,
            'penerbit' => $request->penerbit,
            'cover_buku' => $file?'data_file/'.$file->getClientOriginalName():DB::raw('cover_buku'),
            'tahun_terbit' => $request->tahun_terbit,
            'stock' => $request->stock,
            'updated_at' => Carbon::now(),
        ]);
        return response()->json(['message' => 'Buku berhasil di edit']);
    }

    public function hapus(Request $request)
    {
        DB::table('buku')
        ->where('id', $request->id)
        ->delete();
        return response()->json(['message' => 'Buku berhasil di hapus']);
    }
}
