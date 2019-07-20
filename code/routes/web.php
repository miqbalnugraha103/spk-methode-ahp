<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/admin', function () {
    return view('auth.login');
});

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/home', function () {
    return view('admin.dashboard');
});

Route::get('/pengguna', function () { return view('pengguna.index'); });
Route::get('/pengguna/tambah', function () { return view('pengguna.create'); });
Route::get('/pengguna/edit', function () { return view('pengguna.edit'); });
//End sales & detail sales
Route::get('/seleksi', function () { return view('seleksi.index'); });
Route::get('/seleksi/tambah', function () { return view('seleksi.create'); });
Route::get('/seleksi/edit', function () { return view('seleksi.edit'); });

Route::get('/kriteria', function () { return view('kriteria.index'); });
Route::get('/kriteria/tambah', function () { return view('kriteria.create'); });
Route::get('/kriteria/edit', function () { return view('kriteria.edit'); });

Route::get('/kriteria-seleksi', function () { return view('kriteria-seleksi.index'); });
Route::get('/kriteria-seleksi/tambah', function () { return view('kriteria-seleksi.create'); });
Route::get('/kriteria-seleksi/edit', function () { return view('kriteria-seleksi.edit'); });

Route::get('/alternatif', function () { return view('alternatif.index'); });
Route::get('/alternatif/tambah', function () { return view('alternatif.create'); });
Route::get('/alternatif/edit', function () { return view('alternatif.edit'); });

Route::get('/nilai-kriteria', function () { return view('nilai-kriteria.index'); });
Route::get('/nilai-alternatif', function () { return view('nilai-alternatif.index'); });
Route::get('/hasil-seleksi', function () { return view('hasil-seleksi.index'); });
Auth::routes();
// Route::get('/admin', 'AdminController@index')->name('admin');
Route::get('/admin', function () {
    return redirect('/home');
});
Route::get('/logout', 'Auth\\LoginController@logout')->name('logout');
Route::get('/profile', 'Admin\\UserController@profile');
