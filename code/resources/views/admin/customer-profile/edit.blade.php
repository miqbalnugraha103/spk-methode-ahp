@extends('layouts.admin.frame')

@section('title', 'Edit Customer Profile')

@section('content')

<ol class="breadcrumb breadcrumb-col-deep-purple">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/customer-profile') }}">Customer Profile</a></li>
    <li class="active">Edit admin/customer-profile</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Edit Customer Profile <span class="pull-right"><a href="{{ url('/admin/customer-profile') }}" title="Back"><button class="btn bg-green waves-effect">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">

                    {!! Form::model($custProfile, [
                        'method' => 'PATCH',
                        'url' => ['/admin/customer-profile', $custProfile->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('admin.customer-profile.form', ['submitButtonText' => 'Update'])

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
