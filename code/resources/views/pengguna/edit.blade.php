@extends('layouts.admin.frame')

@section('title', 'Edit Sales Person')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/sales') }}">Sales Person</a></li>
    <li class="active">Edit Sales Person</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Edit Sales Person<span class="pull-right"><a href="{{ url('/admin/sales') }}" title="Back"><button class="btn bg-green waves-effect">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">


                    {!! Form::model($sales, [
                        'method' => 'PATCH',
                        'url' => ['/admin/sales', $sales->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label for="full_name">Full Name</label>
                            <div class="form-group form-float {{ $errors->has('name') ? 'has-error' : ''}}">
                                <div class="form-line">
                                    {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'full_name', 'placeholder' => 'Full Name']) !!}
                                    {{--{!! Form::label('name', 'Full Name', ['class' => 'form-label']) !!}--}}
                                </div>
                                {!! $errors->first('name', '<p class="help-block" style="color: red;">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label for="username">Username</label>
                            <div class="form-group form-float {{ $errors->has('username') ? 'has-error' : ''}}">
                                <div class="form-line">
                                    {!! Form::text('username', null, ['class' => 'form-control', 'id' => 'username', 'placeholder' => 'Username']) !!}

                                </div>
                                {!! $errors->first('username', '<p class="help-block" style="color: red;">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label for="email">Email</label>
                            <div class="form-group form-float {{ $errors->has('email') ? 'has-error' : ''}}">
                                <div class="form-line">
                                    {!! Form::text('email', null, ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Email Address']) !!}
                                </div>
                                {!! $errors->first('email', '<p class="help-block" style="color: red;">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label for="password">Password</label>
                            <div class="form-group form-float {{ $errors->has('password') ? 'has-error' : ''}}">
                                <div class="form-line">
                                    {!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'placeholder' => 'Password']) !!}
                                </div>
                                {!! $errors->first('password', '<p class="help-block" style="color: red;">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn bg-green waves-effect']) !!}
                        <input type="reset" value="Clear" class="btn bg-grey waves-effect">
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
