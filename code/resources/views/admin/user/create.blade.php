@extends('layouts.admin.frame')

@section('title', 'Create New Users')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/users') }}">User</a></li>
    <li class="active">Create User</li>
</ol>

<!-- @include('layouts.admin.message') -->

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Create New User <span class="pull-right"><a href="{{ url('/admin/users') }}" title="Back"><button class="btn bg-green waves-effect">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">

                    {!! Form::open(['url' => '/admin/users', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('admin.user.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
