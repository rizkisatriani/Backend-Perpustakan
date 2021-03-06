<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $anggota= DB::table('users')
        ->where(function($q)use($request){
            $q->orWhere('name', 'like', '%'.$request->q.'%');
            $q->orWhere('no_hp', 'like', '%'.$request->q.'%');
            $q->orWhere('email', 'like', '%'.$request->q.'%');
            $q->orWhere('nik', 'like', '%'.$request->q.'%');
        })
        ->get();
        return response()->json(['data' => $anggota]);
    }

    public function requestAnggota(Request $request)
    {
        $anggota= DB::table('users')
        ->where('level', 1)
        ->where('verified_at', NULL)
        ->get();
        return response()->json(['data' => $anggota]);
    }

    public function AnggotaById(Request $request)
    {
        $anggota= DB::table('users')
        ->where('id', $request->id)
        ->first();
        return response()->json(['data' => $anggota]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'nik' => $request->nik,
            'level' => $request->level,
            'avatar' => '/avatar/default.png',
            'password' => $request->nik,
            'verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return response()->json(['message' => 'Anggota berhasil ditambah']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        DB::table('users')
        ->where('id', $request->id)
        ->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'nik' => $request->nik,
            'updated_at' => Carbon::now(),
        ]);
        return response()->json(['message' => 'Anggota berhasil di edit']);
    }

    public function changeAvatar(Request $request)
    {
        $file = $request->file('avatar');
        $tujuan_upload = 'avatar';
        $file->move($tujuan_upload,$request->id);
        DB::table('users')
        ->where('id', $request->id)
        ->update([
            'avatar' => '/avatar/'.$request->id,
        ]);
        return response()->json(['message' => 'Avatar berhasil di edit',
                                 'url' => '/avatar/'.$request->id]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::table('users')
        ->where('id', $request->id)
        ->delete();
        return response()->json(['message' => 'Anggota berhasil di hapus']);
    }
}
