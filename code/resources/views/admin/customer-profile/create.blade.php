@extends('layouts.admin.frame')

@section('title', 'Create New Customer Profile')

@section('content')

<ol class="breadcrumb breadcrumb-col-deep-purple">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/customer-profile') }}">Customer Profile</a></li>
    <li class="active">Create New Customer Profile</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Create New Customer Profile <span class="pull-right"><a href="{{ url('/admin/customer-profile') }}" title="Back"><button class="btn bg-green waves-effect">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::open(['url' => '/admin/customer-profile', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('admin.customer-profile.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
