@extends('layouts.admin.frame')

@section('title', 'Edit Invoice by Upload File')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/invoice-list') }}">Invoice Lists</a></li>
    <li class="active">Edit Invoice Lists</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Edit Invoice Lists<span class="pull-right"><a href="{{ url('/admin/invoice-list') }}" title="Back"><button class="btn bg-green waves-effect">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">

                    {!! Form::model($invoicelist, [
                        'method' => 'PATCH',
                        'url' => ['/admin/invoice-list', $invoicelist->id],
                        'class' => 'form-horizontal',
                        'id' => 'form_upload_file',
                        'files' => true,
                    ]) !!}

                     @include ('admin.invoice.form', ['submitButtonText' => 'Update'])

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection