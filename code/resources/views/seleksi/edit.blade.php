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

                    @include ('seleksi.form', ['submitButtonText' => 'Simpan Data'])

                    {{-- {!! Form::close() !!} --}}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
