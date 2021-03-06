<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register',  'AuthController@Register');
Route::post('/login',  'AuthController@Login');
Route::post('/getMobile',  'BukuController@getMobile');
Route::get('/getBuku',  'BukuController@get');
Route::post('/addBuku',  'BukuController@add');
Route::post('/editBuku',  'BukuController@edit');
Route::post('/deleteBuku',  'BukuController@hapus');
Route::get('/getAnggota',  'AnggotaController@index');
Route::post('/addAnggota',  'AnggotaController@create');
Route::post('/editAnggota',  'AnggotaController@update');
Route::post('/deleteAnggota',  'AnggotaController@destroy');
Route::get('/getPeminjaman',  'PinjamController@get');
Route::get('/getDetilPeminjaman',  'PinjamController@getDetil');
Route::post('/simpanPeminjaman',  'PinjamController@add');
Route::post('/pengembalian',  'PinjamController@pengembalian');
Route::post('/perpanjang',  'PinjamController@perpanjang');
Route::post('/AccPinjam',  'PinjamController@AccPinjam');
Route::post('/getHistoryById',  'PinjamController@getHistoryById');
Route::post('/getById',  'PinjamController@getById');
Route::post('/Requestpengembalian',  'PinjamController@Requestpengembalian');
Route::post('/Requestperpanjang',  'PinjamController@Requestperpanjang');
Route::get('/requestAnggota',  'AnggotaController@requestAnggota');
Route::get('/getRequestPinjamAdmin',  'PinjamController@getRequestPinjamAdmin');
Route::get('/getRequestKembaliAdmin',  'PinjamController@getRequestKembaliAdmin');
Route::get('/getRequestPerpanjangAdmin',  'PinjamController@getRequestPerpanjangAdmin');
Route::post('/getByIdPinjam',  'PinjamController@getByIdPinjam');
Route::post('/AnggotaById',  'AnggotaController@AnggotaById');
Route::post('/accMobile',  'PinjamController@AccMobile');
Route::post('/TolakMobile',  'PinjamController@TolakMobile');
Route::post('/changeAvatar',  'AnggotaController@changeAvatar');
Route::post('/request',  'RequestBuku@create');
Route::get('/getrequest',  'RequestBuku@index');
Route::post('/deleterequest',  'RequestBuku@destroy');
