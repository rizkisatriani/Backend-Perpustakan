<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function Register(Request $request)
    {
        $isUserExist=DB::table('users')
        ->select("id","status")
        ->whereNik($request->nik)
        ->orWhere('email',$request->email)
        ->orWhere('no_hp',$request->noHp)
        ->first();
        if($isUserExist){
        if($isUserExist->status==0){
            DB::table('users')
            ->select("id","status")
            ->whereNik($request->nik)
            ->delete();
        }else if($isUserExist){
            return response()->json(['message' => 'Data sudah terdaftar'], 403);
        }
        }
        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'nik' => $request->nik,
            'level' => $request->level,
            'password' => $request->password,
            'avatar' => '/avatar/default.png',
            'status' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return response()->json(['message' => 'Registrasi berhasil']);
    }

    public function Login(Request $request)
    {
        $isUserExist=DB::table('users')
        ->whereNik($request->nik)
        ->wherePassword($request->password)
        ->first();
        if(!$isUserExist){
            return response()->json(['message'=> 'Data yang anda masukan salah'],401);
        }
        if($isUserExist->status==0){
            return response()->json(['message'=> 'Akun anda telah ditolak silahkan daftar kembali'],401);
        }
        if($isUserExist->verified_at==null){
            return response()->json(['message'=> 'Akun anda belum aktif'],401);
        }
        if($isUserExist){
            return response()->json(['data' => $isUserExist] );
        }
        return response()->json(['message'=> 'Data yang anda masukan salah'],401);
    }
}
