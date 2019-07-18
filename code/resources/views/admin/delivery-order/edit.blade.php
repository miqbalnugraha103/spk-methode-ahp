@extends('layouts.admin.frame')

@section('title', 'Edit Delivery Order by Upload File')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/delivery-order-list') }}">Delivery Order Lists</a></li>
    <li class="active">Edit Delivery Order Lists</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Edit Delivery Order Lists<span class="pull-right"><a href="{{ url('/admin/delivery-order-list') }}" title="Back"><button class="btn bg-green waves-effect">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">

                    {!! Form::model($deliveryorderlist, [
                        'method' => 'PATCH',
                        'url' => ['/admin/delivery-order-list', $deliveryorderlist->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                    ]) !!}
                        <div class="row">
                            <div class="col-sm-4 col-xs-12">
                                <div class="form-float {{ $errors->has('purchase_order_list_code_id') ? 'has-error' : ''}}">
                                    <label class="form-label" style="font-weight: 100; color: #aaa;">Purchase Order Name : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                    <select name="purchase_order_list_code_id" id="purchase_order_list_code_id" class="form-control show-tick">
                                        <option value=""> -- Select --</option>
                                        @foreach($POCode as $qc)
                                            <option value="{!! $qc->id !!}" @if($deliveryorderlist->purchase_order_list_code_id == $qc->id) selected="selected" @endif>{!! $qc->purchase_order_list_code !!}</option>
                                        @endforeach

                                        
                                    </select>
                                    {!! $errors->first('purchase_order_list_code_id', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <div class="form-float {{ $errors->has('delivery_order_list_code') ? 'has-error' : ''}}">
                                    <label class="form-label" style="font-weight: 100; color: #aaa;">Delivery Order Name : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                    <input type="text" name="delivery_order_list_code" class="form-control" value="{{ $deliveryorderlist->delivery_order_list_code }}" placeholder="">
                                    {!! $errors->first('delivery_order_list_code', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <div class="form-float {{ $errors->has('files') ? 'has-error' : ''}}">
                                    <label class="form-label" style="font-weight: 100; color: #aaa;">Delivery Order File</label>
                                    {!! Form::file('files', null, ['class' => 'form-control']) !!}
                                    @if(isset($deliveryorderlist->file) != '')
                                        <a href="{{ url('/') }}/files/quote-list/{{ $deliveryorderlist->file }}" download="{{ $deliveryorderlist->quote_list_code }}" target="_blank">{{ $deliveryorderlist->file }}</a>

                                    @endif
                                    {!! $errors->first('files', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                        <hr>
                        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Update', ['class' => 'btn bg-green waves-effect']) !!}
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection