@extends('layouts.admin.frame')

@section('title', 'Edit Kriteria')

@section('content')

<ol class="breadcrumb breadcrumb-col-deep-purple">
    <li><a href="{{ url('/home') }}">Home</a></li>
    <li><a href="{{ url('/kriteria-seleksi') }}">Daftar Kriteria Seleksi</a></li>
    <li class="active">Tambah Kriteria Untuk Seleksi (ke Dalam Seleksi)</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Tambah Kriteria Untuk Seleksi (ke Dalam Seleksi) <span class="pull-right"><a href="{{ url('/kriteria-seleksi') }}" title="Kembali"><button class="btn bg-red waves-effect">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">
                    <button class="btn btn-primary btn-lg btn-block waves-effect" type="button" style="cursor:context-menu;">Seleksi	: 2015 - Seleksi Bagian Marketing</button>
                    {!! Form::open(['url' => '/admin/color', 'class' => 'form-horizontal', 'files' => true]) !!}

                    <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="colsor_table" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="5%"><i class="fa fa-check" ></i></th>
                                        <th width="30%">Nama Kriteria</th>
                                        <th width="50%">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <input type="checkbox" id="md_checkbox_1" class="filled-in chk-col-black">
                                            <label for="md_checkbox_1"></label>
                                        </td>
                                        <td>Kreatifitas</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>
                                            <input type="checkbox" id="md_checkbox_2" class="filled-in chk-col-black">
                                            <label for="md_checkbox_2"></label>
                                        </td>
                                        <td>Logika Berpikir</td>
                                        <td>-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <div class="form-group">
                        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Tambah Data', ['class' => 'btn bg-green waves-effect']) !!}
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
