@extends('layouts.admin.frame')

@section('title', 'Create New Color')

@section('content')

<ol class="breadcrumb breadcrumb-col-deep-purple">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/color') }}">Color</a></li>
    <li class="active">Create New Color</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Create New Color <span class="pull-right"><a href="{{ url('/admin/color') }}" title="Back"><button class="btn bg-green waves-effect">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">

                    {!! Form::open(['url' => '/admin/color', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('admin/color.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
