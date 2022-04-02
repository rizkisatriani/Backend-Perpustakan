<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PinjamController extends Controller
{
    public function get(Request $request)
    {
        $peminjaman = DB::table('peminjaman')
            ->select(
                'peminjaman.id',
                'users.name',
                'users.nik',
                'users.no_hp',
                DB::raw('IFNULL(peminjam.name,"-") as peminjam_name'),
                DB::raw('IFNULL(peminjam.nik,"-") as peminjam_nik'),
                'peminjaman.tanggal_pinjam'
            )
            ->join('users', 'users.id', '=', 'peminjaman.user_id')
            ->Leftjoin(DB::raw('users as peminjam'), DB::raw('peminjam.id'), '=', 'peminjaman.peminjam_admin_id')
            ->where('peminjaman.tanggal_kembali', null)
            ->get();
        return response()->json(['data' => $peminjaman]);
    }

    public function getHistoryById(Request $request)
    {
        $peminjaman = DB::table('peminjaman')
            ->select(
                'peminjaman.id',
                'users.name',
                'users.nik',
                'users.no_hp',
                DB::raw('IFNULL(peminjam.name,"-") as peminjam_name'),
                DB::raw('IFNULL(peminjam.nik,"-") as peminjam_nik'),
                'peminjaman.tanggal_pinjam'
            )
            ->join('users', 'users.id', '=', 'peminjaman.user_id')
            ->Leftjoin(DB::raw('users as peminjam'), DB::raw('peminjam.id'), '=', 'peminjaman.peminjam_admin_id')
            ->where('users.nik', $request->nik)
            ->where('peminjaman.tanggal_kembali', null)
            ->orderByDesc('peminjaman.tanggal_pinjam')
            ->get();
        return response()->json(['data' => $peminjaman]);
    }

    public function getById(Request $request)
    {
        $peminjaman = DB::table('peminjaman')
            ->select(
                'peminjaman.id',
                'users.name',
                'users.nik',
                'users.no_hp',
                DB::raw('IFNULL(peminjam.name,"-") as peminjam_name'),
                DB::raw('IFNULL(peminjam.nik,"-") as peminjam_nik'),
                'peminjaman.tanggal_pinjam'
            )
            ->join('users', 'users.id', '=', 'peminjaman.user_id')
            ->Leftjoin(DB::raw('users as peminjam'), DB::raw('peminjam.id'), '=', 'peminjaman.peminjam_admin_id')
            ->where('users.nik', $request->nik)
            ->where('peminjaman.tanggal_kembali', null)
            ->orderByDesc('peminjaman.tanggal_pinjam')
            ->first();
            if(!$peminjaman){
                return response()->json(['status'=>1,'data' => []]);
             }
        $buku = DB::table('peminjaman_buku')
            ->select('buku.*')
            ->join('buku', 'buku.id', '=', 'peminjaman_buku.buku_id')
            ->where('peminjaman_id', $peminjaman->id)
            ->get();


        return response()->json(['peminjaman' => $peminjaman, 'data' => $buku]);
    }
    public function getDetil(Request $request)
    {
        $peminjaman = DB::table('peminjaman_buku')
            ->select(
                'peminjaman.id',
                'peminjaman.tanggal_pinjam',
                'peminjaman.tanggal_perpanjang',
                'buku.judul',
                'buku.nama_pengarang',
                'buku.penerbit',
                'buku.tahun_terbit',
            )
            ->join('buku', 'buku.id', '=', 'peminjaman_buku.buku_id')
            ->join('peminjaman', 'peminjaman.id', '=', 'peminjaman_buku.peminjaman_id')
            ->where('peminjaman.id', $request->id)
            ->get();
        $tglPinjam = new Carbon(
            $peminjaman[0]->tanggal_perpanjang ?
                $peminjaman[0]->tanggal_perpanjang :
                $peminjaman[0]->tanggal_pinjam
        );
        $jmlHari = $tglPinjam->diffInDays(Carbon::now());
        $jmlTelat = ($jmlHari - 7) > 0 ? ($jmlHari - 7) * 5000 : 0;
        return response()->json([
            'data' => $peminjaman,
            'denda' => $jmlTelat
        ]);
    }

    public function add(Request $request)
    {
        $peminjam = DB::table('peminjaman')->insertGetId([
            'user_id' => $request->user_id,
            'peminjam_admin_id' => $request->peminjam ? $request->peminjam :
                null,
            'tanggal_pinjam' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $dataBuku = collect(json_decode($request->data_buku))->map(function ($buku) use ($peminjam) {
            return [
                "buku_id" => $buku->id,
                "peminjaman_id" => $peminjam
            ];
        });
        DB::table('peminjaman_buku')->insert($dataBuku->toArray());
        return response()->json(['message' => 'Peminjaman berhasil diajukan']);
    }

    public function AccPinjam(Request $request)
    {
        $peminjam = DB::table('peminjaman')
            ->whereId($request->id)
            ->Update([
                'peminjam_admin_id' => $request->peminjam,
                'tanggal_pinjam' => Carbon::now(),
            ]);
        return response()->json(['message' => 'Pengembalian berhasil.']);
    }
    public function pengembalian(Request $request)
    {
        $peminjam = DB::table('peminjaman')
            ->whereId($request->id)
            ->Update([
                'pengembalian_admin_id' => $request->admin,
                'tanggal_kembali' => Carbon::now(),
                'denda' => $request->denda,
                'updated_at' => Carbon::now(),
            ]);
        return response()->json(['message' => 'Pengembalian berhasil.']);
    }

    public function Requestpengembalian(Request $request)
    {
     DB::table('peminjaman')
            ->whereId($request->id)
            ->Update([
                'tanggal_kembali' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        return response()->json(['message' => 'Pengembalian berhasil.']);
    }

    public function perpanjang(Request $request)
    {
        $peminjam = DB::table('peminjaman')
            ->whereId($request->id)
            ->Update([
                'perpanjang_admin_id' => $request->admin,
                'tanggal_perpanjang' => Carbon::now()->addDays(7),
                'updated_at' => Carbon::now(),
            ]);
        return response()->json(['message' => 'Peminjaman berhasil di perpanjang']);
    }


    public function Requestperpanjang(Request $request)
    {
        $peminjam = DB::table('peminjaman')
            ->whereId($request->id)
            ->Update([
                'tanggal_perpanjang' => Carbon::now()->addDays(7),
                'updated_at' => Carbon::now(),
            ]);
        return response()->json(['message' => 'Peminjaman berhasil di perpanjang']);
    }
}
