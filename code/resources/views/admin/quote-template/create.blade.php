@extends('layouts.admin.frame')

@section('title', 'Create New Quote Template')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/quote-template') }}">Quote Template</a></li>
    <li class="active">Create New Quote Template</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Create New Quote Template<span class="pull-right"><a href="{{ url('/admin/quote-template') }}" title="Back"><button class="btn bg-green waves-effect">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">

                    {!! Form::open(['url' => '/admin/quote-template', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('admin.quote-template.form')

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
