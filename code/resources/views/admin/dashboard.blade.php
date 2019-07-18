@extends('layouts.admin.frame')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-6">
                                <h2>Sistem Pendukung Keputusan Seleksi Alternatif Metode AHP</h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div style="font-size:15px;">
                            <span class="dashboard">Sistem ini membantu pengambil keputusan dalam menyeleksi Alternatif dengan membandingkan setiap Alternatif pada setiap kriteria, metode yang digunakan adalah AHP (Analityc Hierarchy Process).</span><br />
                            <br>
                            <span class="dashboard">Proses yang Anda harus lakukan</span>
                            <ol>
                                <li>Buat seleksi baru (atau bisa disebut periode seleksi baru)</li>
                                <li>Setting kriteria seleksi yang digunakan</li>
                                <li>Masukkan data Alternatif yang nantinya akan diseleksi</li>
                                <li>Mulai seleksi dengan metode AHP</li>
                                    <ul>
                                        <li>Masuk ke Nilai Kriteria dan isikan nilai perbandingan kriteria untuk menentukan bobot kriteria</li>
                                        <li>Masuk ke Nilai Peserta Seleksi dan isikan nilai perbandingannya</li>
                                        <li>Masuk hasil seleksi untuk melihat hasil akhir seleksinya</li>
                                    </ul>
                                <li>Anda dapat membuat seleksi baru lagi mulai dari penambahan seleksi baru</li>
                            </ol> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection