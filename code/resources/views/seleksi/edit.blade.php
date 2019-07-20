@extends('layouts.admin.frame')

@section('title', 'Edit Color')

@section('content')

<ol class="breadcrumb breadcrumb-col-deep-purple">
    <li><a href="{{ url('/home') }}">Home</a></li>
    <li><a href="{{ url('/seleksi') }}">Daftar Seleksi</a></li>
    <li class="active">Ubah Seleksi</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Ubah Daftar Seleksi <span class="pull-right"><a href="{{ url('/seleksi') }}" title="Kembali"><button class="btn bg-red waves-effect">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">

                    {{-- {!! Form::model($color, [
                        'method' => 'PATCH',
                        'url' => ['/admin/color', $color->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!} --}}

                    <div class="row">
                        <div class="col-md-6" style="margin-top:20px;">
                            <div class="form-group form-float {{ $errors->has('color_name') ? 'has-error' : ''}}">
                                <div class="form-line">
                                    {!! Form::text('color_name', 'Seleksi Bagian Marketing', ['class' => 'form-control']) !!}
                                    <label class="form-label">Nama Seleksi <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                </div>
                                {!! $errors->first('color_name', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <div class="form-float {{ $errors->has('date_out') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;"> Tahun Seleksi : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                <input name="date_out" class="form-control" type="number" min="1900" max="2099" step="1" value="2015" />
                                {!! $errors->first('date_out', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-float">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Note :</label>
                                <textarea name="note" id="note" class="form-control" rows="3">Penyeleksian Bagian Marketing Cabang Baru</textarea>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Tambah Data', ['class' => 'btn bg-green waves-effect']) !!}
                    </div>

                    {{-- {!! Form::close() !!} --}}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
