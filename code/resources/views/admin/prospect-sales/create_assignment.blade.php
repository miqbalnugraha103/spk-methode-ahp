@extends('layouts.admin.frame')

@section('title', 'Create New Prospect Sales')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/prospect') }}">Prospect</a></li>
    <li class="active">Create New Prospect</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Create New Prospect Sales<span class="pull-right"><a href="{{ url('/admin/prospect') }}" title="Back"><button class="btn bg-green waves-effect">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">

                    {!! Form::open(['url' => '/admin/prospect', 'class' => 'form-horizontal', 'files' => true]) !!}

                    <div class="row" style="margin-top: 5px;">
                        <div class="col-sm-3 col-xs-6">
                            <div class="form-group form-float{{ $errors->has('sales_person_id') ? 'has-error' : ''}}">
                                <div>
                                    {!! Form::label('sales_person_id', 'Sales Person', ['class' => 'form-label', 'style' => 'font-weight: 100; color: #aaa']) !!} <br>
                                    {{ Form::select('sales_person_id',$sales, null, ['class' => 'show-tick']) }}
                                </div>
                                {!! $errors->first('sales_person_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>


                        <div class="col-sm-3 col-xs-6">
                            <div class="form-group form-float">
                                {!! Form::label('assignment_date', 'Assignment  Date', ['class' => 'form-label', 'style' => 'font-weight: 100; color: #aaa']) !!} <br>
                                <div class="form-line">
                                    <input name="assignment_date" type="text" class="datetimepicker form-control" value="{{ isset($prospectsales->assignment_date) ? $prospectsales->assignment_date : '' }}" placeholder="Please choose date & time..." style="margin-top: -4px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
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
@push('script')
    <script>
        tinymce.init({
            selector: "textarea#tinymce",
            theme: "modern",
            height: 80,

            plugins: [
                'advlist autolink lists link charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools'
            ],
            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
            toolbar2: 'preview| forecolor backcolor emoticons',
            image_advtab: false
        });
        tinymce.suffix = ".min";
        tinyMCE.baseURL = '{{ url('/') }}/plugins/tinymce';

        //Datetimepicker plugin
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'DD-MM-YYYY HH:mm',
            clearButton: true,
            weekStart: 1
        });
    </script>
@endpush