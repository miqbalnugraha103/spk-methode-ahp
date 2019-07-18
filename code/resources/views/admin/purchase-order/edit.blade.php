@extends('layouts.admin.frame')

@section('title', 'Edit PO Lists')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/quote-list') }}">Purchase Order Lists</a></li>
    <li class="active">Edit Purchase Order Lists</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Edit Purchase Order Lists<span class="pull-right"><a href="{{ url('/admin/purchase-order-list') }}" class="btn bg-green waves-effect" title="Back">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                {!! Form::model($POLists, [
                    'method' => 'PATCH',
                    'url' => ['/admin/purchase-order-list', $POLists->id],
                    'class' => 'form-horizontal',
                    'files' => true,
                ]) !!}
                <div class="body">
                    <div class="row">
                        
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-float {{ $errors->has('quote_list_code_id') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Quote Name : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                <select name="quote_list_code_id" class="form-control show-tick">
                                    <option value=""> -- Select --</option>
                                    @foreach($QuoteCode as $qc)
                                    <option value="{!! $qc->id !!}" @if($POLists->quote_list_code_id == $qc->id) selected="selected" @endif>{!! $qc->quote_list_code !!}</option>
                                    @endforeach
                                </select>
                                {!! $errors->first('quote_list_code_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <label class="form-label" style="font-weight: 100; color: #aaa;">Purchase Order Name</label>&nbsp;:&nbsp;

                           {{-- @if($POLists->purchase_order_list_code != '') --}}
                                <!-- <h4><span class="label label-default">{{ $POLists->purchase_order_list_code }}</span></h4>
                                <input type="hidden" name="purchase_order_list_code" id="purchase_order_list_code" class="form-control" value="{{ $POLists->purchase_order_list_code }}"> -->
                            {{-- @else --}}
                                <input type="text" name="purchase_order_list_code" id="purchase_order_list_code" class="form-control" value="{{ $POLists->purchase_order_list_code }}" placeholder="">
                                {!! $errors->first('purchase_order_list_code', '<p class="help-block">:message</p>') !!}
                            {{-- @endif --}}
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-float {{ $errors->has('files') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Purchase Order File</label>
                                {!! Form::file('files', null, ['class' => 'form-control']) !!}
                                @if(isset($POLists->file) != '')
                                    <a href="{{ url('/') }}/files/purchase-order/{{ $POLists->file }}" download="{{ $POLists->purchase_order_list_code }}" target="_blank">{{ $POLists->file }}</a>

                                @endif
                                {!! $errors->first('files', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <hr>
                        <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Update', ['class' => 'btn bg-green waves-effect']) !!}
                        </div>
                    </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection