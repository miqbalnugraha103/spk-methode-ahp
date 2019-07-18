@extends('layouts.admin.frame')

@section('title', 'Edit Quote Lists')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/quote-list') }}">Quote Lists</a></li>
    <li class="active">Edit Quote Lists</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Edit Qoute Lists<span class="pull-right"><a href="{{ url('/admin/quote-list') }}" title="Back"><button class="btn bg-green waves-effect">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">
    
                    {!! Form::model($quotelist, [
                        'method' => 'PATCH',
                        'url' => ['/admin/quote-list', $quotelist->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                    ]) !!}

                    <div class="row">
                        <div class="col-sm-4 col-xs-6">
                            <div class="form-group form-float {{ $errors->has('prospect_sales_id') ? 'has-error' : ''}}">
                                    <label class="form-label" style="font-weight: 100; color: #aaa;">Prospect Sales</label>
                                    <select name="prospect_sales_id" id="prospect_sales_id" class="form-control show-tick" data-live-search="true">
                                        <option value="0"> -- Select --</option>
                                        @foreach($prospect_sales as $ps)
                                        @if($quotelist->prospect_sales_id == $ps->id)
                                            <option value="{!! $ps->id !!}" selected="selected">{!! $ps->company_name !!} - {!! $ps->name_sales !!}</option>
                                        @else
                                            <option value="{!! $ps->id !!}">{!! $ps->company_name !!} - {!! $ps->name_sales !!}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                {!! $errors->first('prospect_sales_id', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div id="sales_id"></div>
                        </div>
                        <div class="col-sm-4 col-xs-12" style="margin-top: 24px;">
                            <div class="form-group form-float {{ $errors->has('quote_list_code') ? 'has-error' : ''}}">
                                <div class="form-line">
                                    {!! Form::text('quote_list_code', null, ['class' => 'form-control']) !!}
                                    {!! Form::label('quote_list_code', 'Quote Lists', ['class' => 'form-label']) !!}
                                </div>
                                {!! $errors->first('quote_list_code', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12" style="margin-top: 24px;">
                            <div class="form-group form-float {{ $errors->has('requote_list_code') ? 'has-error' : ''}}">
                                <div class="form-line">
                                    {!! Form::text('requote_list_code', null, ['class' => 'form-control']) !!}
                                    {!! Form::label('requote_list_code', 'Requote Lists', ['class' => 'form-label']) !!}
                                </div>
                                {!! $errors->first('requote_list_code', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            {!! Form::label('files', 'Files', ['class' => 'form-label', 'style' => 'font-weight: 100; color: #aaa']) !!}
                            <div class="form-group form-float {{ $errors->has('files') ? 'has-error' : ''}}">
                                {!! Form::file('files', null, ['class' => 'form-control']) !!}
                                @if(isset($quotelist->file) != '')
                                    <a href="{{ url('/') }}/files/quote-list/{{ $quotelist->file }}">{{ $quotelist->file }}</a>
                                @endif
                                {!! $errors->first('files', '<p class="help-block">:message</p>') !!}
                            </div>
                            
                        </div>

                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn bg-green waves-effect']) !!}
                                <input type="reset" value="Clear" class="btn bg-grey waves-effect">
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
        $('#prospect_sales_id').on('change', function(){
            var prospect_sales_id = $(this).val();

             $.ajax({
               url : '{{ url("admin/filter/sales") }}',
               method : "POST",
               data : {
                   prospect_sales_id:prospect_sales_id,
                   _token:"{{csrf_token()}}"
               },
               dataType : "text",
               success : function (data)
               {
                console.log(data);
                   if(data != '')
                   {
                        $('#sales_id').html(data);
                        
                   }
               }
           });
        }).change();
    </script>
@endpush