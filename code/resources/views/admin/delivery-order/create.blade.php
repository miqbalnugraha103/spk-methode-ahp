@extends('layouts.admin.frame')

@section('title', 'Transaction Delivery Order Lists')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/delivery-order-list') }}">Delivery Order Lists</a></li>
    <li class="active">Delivery Order Create</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Create Delivery Order<span class="pull-right"><a href="{{ url('/admin/delivery-order-list') }}" class="btn bg-green waves-effect" title="Back">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                {!! Form::open(['url' => '/admin/delivery-order-list', 'class' => 'form-horizontal', 'files' => true]) !!}
                <div class="body">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-float {{ $errors->has('purchase_order_list_code_id') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Purchase Order Name : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                <select name="purchase_order_list_code_id" id="purchase_order_list_code_id" class="form-control show-tick">
                                    <option value=""> -- Select --</option>
                                    @foreach($POCode as $qc)
                                        <option value="{!! $qc->id !!}">{!! $qc->purchase_order_list_code !!}</option>
                                    @endforeach

                                    
                                </select>
                                {!! $errors->first('purchase_order_list_code_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-float {{ $errors->has('delivery_order_list_code') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Delivery Order Name : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                <input type="text" name="delivery_order_list_code" class="form-control" value="{{ old('delivery_order_list_code') }}" placeholder="">
                                {!! $errors->first('delivery_order_list_code', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-float {{ $errors->has('files') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Delivery Order File</label>
                                {!! Form::file('files', null, ['class' => 'form-control']) !!}
                                
                                {!! $errors->first('files', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <hr>
                    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn bg-green waves-effect']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection