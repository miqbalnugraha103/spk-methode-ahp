@extends('layouts.admin.frame')

@section('title', 'Create New Prospect')

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
                            <h2>Create New Prospect<span class="pull-right"><a href="{{ url('/admin/prospect') }}" title="Back"><button class="btn bg-green waves-effect">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">

                    {!! Form::open(['url' => '/admin/prospect', 'class' => 'form-horizontal', 'files' => true]) !!}
                    
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-sm-7 col-xs-12" style="margin-top: 0;">
                            <div class="form-group form-float {{ $errors->has('customer_profile_id') ? 'has-error' : ''}}">
                                <label class="form-label" style="margin-top: 10px; font-weight: normal; color: #aaa;">Customer Company <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                {!! Form::select('customer_profile_id', $custProfile, null, ['class' => 'form-control show-tick', 'id' => 'customer_profile_id', 'data-live-search' => 'true']) !!}
                                {!! $errors->first('customer_profile_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-5 col-xs-12">
                            <label class="form-label" style="margin-top: 10px; font-weight: normal; color: #aaa;">PIC Name</label>
                            <p class="panel-title" id="get_pic_name">-</p>
                        </div>
                    </div>

                    <h2 class="card-inside-title" style="margin-top: 10px; font-weight: normal; color: #aaa;">Company Address</h2>
                    <div class="form-float">
                        <div class="form-line">
                            {!! Form::textarea('', '-', ['class' => 'form-control', 'id' => 'get_company_address', 'rows' => '3', 'readonly' => '', 'style' => 'background-color:#dedede;'] ) !!}
                        </div>
                    </div>
                    <br>
                    
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-sm-5 col-xs-12">
                            <label class="form-label" style="margin-top: 10px; font-weight: normal; color: #aaa;">Company Phone</label>
                            <p class="panel-title" id="get_company_phone">-</p>
                        </div>
                        @if (Auth::user()->role == \App\User::ROLE_SUPERADMIN || Auth::user()->role == \App\User::ROLE_ADMIN)
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group form-float {{ $errors->has('user_id') ? 'has-error' : ''}}">
                                    <label class="form-label" style="margin-top: 10px; font-weight: normal; color: #aaa;">Sales Person <span style="font-size: 15px;color: red;line-height:15px;">*</span></label> <br>
                                    {{ Form::select('user_id',$sales, null, ['class' => 'form-control show-tick', 'data-live-search' => 'true' ]) }}
                                    {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-12 col-xs-12">
                            <label class="panel-title">Brand <span style="font-size: 15px;color: red;line-height:15px;">*</span></label><hr>
                            <div class="row">
                            @php $i=1; @endphp
                            @foreach($brandId as $i => $tg)
                                <div class="col-sm-3 col-xs-3">
                                    <input type="checkbox" name="brand[]" id="md_checkbox_{{ $i }}" class="filled-in chk-col-cyan" value="{{ $tg->id }}" 
                                    @if(old('brand'))
                                        @foreach(old('brand') as $brand)
                                            @if($brand == $tg->id)
                                                checked=""
                                            @endif
                                        @endforeach
                                    @endif>
                                    <label for="md_checkbox_{{ $i }}">{{ $tg->brand }}</label>
                                    @php $i++ @endphp
                                </div>
                            @endforeach
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <h2 class="card-inside-title" style="margin-top: 10px; font-weight: normal; color: #aaa;">Progress Notes</h2>
                            <div class="form-float{{ $errors->has('progress_notes') ? 'has-error' : ''}}">
                                <div class="form-line">
                                    {!! Form::textarea('progress_notes', null, ['class' => 'form-control', 'rows' => '3'] ) !!}
                                </div>
                                {!! $errors->first('progress_notes', '<p class="help-block">:message</p>') !!}
                            </div>
                            <br>
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
        $(document).on('change', '#customer_profile_id', function(){
            var customer_profile_id = $(this).val();
            $.ajax({
                url : '{{ url("/admin/get-customer-profile") }}',
                method : "GET",
                data : {
                    "customer_profile_id":customer_profile_id,
                    _token:"{{csrf_token()}}"
                },
                dataType : "json",
                success : function (data)
                {
                    // console.log(data);
                    $('#get_pic_name').html(data.name_pic);
                    $('#get_company_address').text(data.company_address);
                    $('#get_company_phone').text(data.company_phone);
                },
                error: function(){
                    $('#get_pic_name').html('-');
                    $('#get_company_address').text('-');
                    $('#get_company_phone').text('-');
                }
            });
        }).change();
    </script>
    <script>
        //Datetimepicker plugin
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format : 'DD/MM/YYYY HH:mm:ss',
            clearButton: true,
            weekStart: 1,
            minDate : new Date()
        });
    </script>
@endpush