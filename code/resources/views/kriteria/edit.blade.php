@extends('layouts.admin.frame')

@section('title', 'Edit Color')

@section('content')

<ol class="breadcrumb breadcrumb-col-deep-purple">
    <li><a href="{{ url('/home') }}">Home</a></li>
    <li><a href="{{ url('/kriteria') }}">Daftar Kriteria</a></li>
    <li class="active">Ubah Kriteria</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Ubah Daftar Kriteria <span class="pull-right"><a href="{{ url('/kriteria') }}" title="Kembali"><button class="btn bg-red waves-effect">
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
                                    {!! Form::text('color_name', 'Kejujuran', ['class' => 'form-control']) !!}
                                    <label class="form-label">Nama Seleksi <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                </div>
                                {!! $errors->first('color_name', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-float">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Keterangan :</label>
                                <textarea name="note" id="note" class="form-control" rows="3">-</textarea>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Simpan Data', ['class' => 'btn bg-green waves-effect']) !!}
                    </div>

                    {{-- {!! Form::close() !!} --}}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
