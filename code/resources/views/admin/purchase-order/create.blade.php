@extends('layouts.admin.frame')

@section('title', 'Create New Purchase Order Lists')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/purchase-order-list') }}" id="title">Purchase Order Lists</a></li>
    <li class="active" id="title2">Create purchase Order Lists</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2><div id="title3">Create Purchase Order Lists</div><span class="pull-right"><a href="{{ url('/admin/purchase-order-list') }}" title="Back" id="back"><button class="btn bg-green waves-effect">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">
                    {!! Form::open(['url' => '/admin/purchase-order-list', 'class' => 'form-horizontal', 'files' => true]) !!}
                    
                    @include ('admin.purchase-order.form', ['submitButtonText' => 'Create'])
                    
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection