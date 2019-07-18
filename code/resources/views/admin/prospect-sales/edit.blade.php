@extends('layouts.admin.frame')

@section('title', 'Edit Prospect Sales')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/prospect') }}">Prospect Sales</a></li>
    <li class="active">Edit Prospect Sales</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Edit Prospect Sales <span class="pull-right"><a href="{{ url('/admin/prospect') }}" title="Back"><button class="btn bg-green waves-effect">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">

                    {!! Form::model($prospectsales, [
                        'method' => 'PATCH',
                        'url' => ['/admin/edit-company', $prospectsales->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'form_company'
                    ]) !!}

                <div class="row" style="margin-top: 5px;">
<!-- input type Company Name -->
                    @if(Auth::user()->role ==  \App\User::ROLE_SUPERADMIN || Auth::user()->role ==  \App\User::ROLE_ADMIN)
                        <div class="col-sm-7 col-xs-12" style="margin-top: 0;">
                            <div class="form-group form-float">
                                <label class="form-label" style="margin-top: 10px; font-weight: normal; color: #aaa;">Customer Company <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                {!! Form::select('customer_profile_id', $custProfile, null, ['class' => 'form-control show-tick', 'id' => 'customer_profile_id', 'data-live-search' => 'true']) !!}
                            </div>
                            <p class="help-block error_company" style="color: #a94442;display: none;">The company name field is required</p>
                        </div>
                    @else
                        <div class="col-sm-6 col-xs-6">
                            <label class="form-label">Company Name :</label>
                            <div class="form-group form-float">
                                {{ $prospectsales->company_name }}
                            </div>
                        </div>
                    @endif
<!-- input type CP Name -->
                    @if(Auth::user()->role ==  \App\User::ROLE_SUPERADMIN || Auth::user()->role ==  \App\User::ROLE_ADMIN)
                        <div class="col-sm-5 col-xs-12">
                            <label class="form-label" style="margin-top: 10px; font-weight: normal; color: #aaa;">PIC Name</label>
                            <p class="panel-title" id="get_pic_name">-</p>
                        </div>
                    @else
                   <div class="col-sm-6 col-xs-6">
                        <label class="form-label">CP Name :</label>
                        <div class="form-group form-float">
                            {{ $prospectsales->name_pic }}
                        </div>
                   </div>
                   <br>
                   <br>
                   <br>
                   <hr>
                    @endif
<!-- input type Company Address -->
                    @if(Auth::user()->role ==  \App\User::ROLE_SUPERADMIN || Auth::user()->role ==  \App\User::ROLE_ADMIN)
                        <div class="col-sm-12 col-xs-12">
                            <h2 class="card-inside-title" style="margin-top: 10px; font-weight: normal; color: #aaa;">Company Address :</h2>
                            <div class="form-float">
                                <div class="form-line">
                                    {!! Form::textarea('', '-', ['class' => 'form-control', 'id' => 'get_company_address', 'rows' => '3', 'readonly' => '', 'style' => 'background-color:#dedede;'] ) !!}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-sm-12 col-xs-12">
                            <label class="form-label">Company Address :</label>
                            <div class="form-group form-float">
                                {{ $prospectsales->company_address }}
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <hr>
                    @endif
<!-- input type Company Phone -->
                    @if(Auth::user()->role ==  \App\User::ROLE_SUPERADMIN || Auth::user()->role ==  \App\User::ROLE_ADMIN)
                        <div class="col-sm-12 col-xs-12">
                            <label class="form-label" style="margin-top: 10px; font-weight: normal; color: #aaa;">Company Phone</label>
                            <p class="panel-title" id="get_company_phone">-</p>
                        </div>
                    @else
                        <div class="col-sm-12 col-xs-12">
                            <label class="form-label">Company Phone :</label>
                            <div class="form-group form-float">
                                {{ $prospectsales->company_phone }}
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <hr>
                    @endif
<!-- Checkbox Brand -->
                    @if(Auth::user()->role ==  \App\User::ROLE_SUPERADMIN || Auth::user()->role ==  \App\User::ROLE_ADMIN)
                        <div class="col-sm-12 col-xs-12">
                            <label class="panel-title" style="margin-top: 10px; font-weight: normal; color: #aaa;">Brand : <span class="error_brand" style="font-size: 15px;color: red;line-height:15px;">*</span></label><hr>
                            <div class="row">
                                @php
                                    $checked = "";
                                    $i=1;
                                @endphp
                                @foreach($brandId as $i => $tg)
                                    <div class="col-sm-3 col-xs-3">
                                        @foreach($brandData as $td)
                                            @if($td->brand_id == $tg->id)
                                                <?php
                                                $checked = "checked";
                                                break;
                                                ?>
                                            @endif
                                        @endforeach
                                        <input type="checkbox" name="brand[]" id="md_checkbox_{{ $i }}" class="filled-in chk-col-cyan" value="{{ $tg->id }}" {{ $checked }}>
                                        <label for="md_checkbox_{{ $i }}">{{ $tg->brand }}</label>
                                        @php $i++ @endphp
                                    </div>
                                    <?php $checked = ""; ?>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="col-sm-12 col-xs-12">
                            <label class="form-label">Brand :</label>
                            <div class="form-group form-float">
                                <ol>
                                @foreach($brandId as $i => $tg)
                                    @foreach($brandData as $td)
                                        @if($td->brand_id == $tg->id)
                                                <li>{{ $tg->brand }}</li>
                                        @endif
                                    @endforeach
                                @endforeach
                                </ol>
                            </div>
                        </div>
                    @endif
                </div>
                @if(Auth::user()->role ==  \App\User::ROLE_SUPERADMIN || Auth::user()->role ==  \App\User::ROLE_ADMIN)
                    <div class="form-group">
                        <input type="button" id="btn_company" value="Update Company" class="btn bg-green waves-effect">
                    </div>
                @endif
                {!! Form::close() !!}

                {!! Form::model($prospectsales, [
                        'method' => 'PATCH',
                        'url' => ['/admin/edit-assignment', $prospectsales->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' =>  'form_sales_person'
                    ]) !!}
                <div class="box box-solid">
                    <div class="box-body">
                      <div class="box-group" id="accordion">
                        <div class="panel box box-primary">
                          <div class="box-header with-border">
                            <h4 class="box-title">
                              <a data-toggle="collapse" href="#collapseOne" id="sales_person" class="btn bg-blue-grey btn-lg waves-effect" style="width: 100%;">
                                Sales Person<i class="fa fa-angle-down pull-right"></i>
                              </a>
                            </h4>
                          </div>
                          <div id="collapseOne" class="panel-collapse collapse in">
                            <div class="box-body">
                                <div class="row" id="field_sales_person">
                                    <div class="col-sm-4 col-xs-6">
                                        <div class="form-group form-float">
                                            {!! Form::label('sales_person_id', 'Sales Person', ['class' => 'form-label', 'style' => 'font-weight: 100; color: #aaa']) !!} <span style="font-size: 15px;color: red;line-height:15px;">*</span> <br>
                                            {{ Form::select('sales_person_id',$salesperson, null, ['class' => 'show-tick', 'id' => 'sales_assignment', 'id' => 'sales_person_select', 'data-live-search' => 'true']) }}

                                            <p class="help-block error_sales_person" style="color: #a94442;display: none;">The sales person field is required</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-xs-6">
                                        <div class="form-group form-float">
                                            {!! Form::label('assignment_date', 'Action Date', ['class' => 'form-label', 'style' => 'font-weight: 100; color: #aaa']) !!} <span style="font-size: 15px;color: red;line-height:15px;">*</span> <br>
                                            <div class="form-line">
                                                <input name="assignment_date" type="text" id="assignment_date_sales" class="datetimepicker form-control" value="" placeholder="Please choose date & time..." style="margin-top: -4px;">
                                            </div>
                                            <p class="help-block error_date_sales" style="color: #a94442;display: none;">The action date field is required</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-xs-6">
                                        <div style="margin-top: 20px;">
                                            <input type="button" id="btn_sales_person" value="Submit" class="btn bg-green waves-effect">
                                            <a class="btn btn-danger waves-effect" id="close_new_sales"><i class="fa fa-close"></i></a>

                                        </div>
                                    </div>
                                </div>
                                @if(Auth::user()->role ==  \App\User::ROLE_SUPERADMIN || Auth::user()->role ==  \App\User::ROLE_ADMIN)
                                    <a class="btn bg-green waves-effect" style="margin-top: 20px;" id="add_new_sales_person"><i class="fa fa-plus"></i> Add New Sales Person</a>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="prospect-assignment-table" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th width="2%">#</th><th>Sales Person</th><th>Action Date</th><th width="15%">Action Update</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                          </div>
                        </div>
                        {!! Form::close() !!}

                        {!! Form::model($prospectsales, [
                        'method' => 'PATCH',
                        'url' => ['/admin/edit-progress', $prospectsales->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'form-edit-progress'
                    ]) !!}

                        <div class="panel box box-danger">
                          <div class="box-header with-border">
                            <h4 class="box-title">
                              <a data-toggle="collapse" href="#collapseTwo" id="on_duty" class="btn bg-blue-grey btn-lg waves-effect" style="width: 100%;">
                                On Duty<i class="fa fa-angle-down pull-right"></i>
                              </a>
                            </h4>
                          </div>
                          <div id="collapseTwo" class="panel-collapse collapse in">
                            <div class="box-body">
                                <div class="row" id="field_on_duty">
                                    @if(Auth::user()->role ==  \App\User::ROLE_SUPERADMIN || Auth::user()->role ==  \App\User::ROLE_ADMIN)
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="form-group form-float">
                                            {!! Form::label('sales_person_id', 'Sales Person', ['class' => 'form-label', 'style' => 'font-weight: 100; color: #aaa']) !!} <span style="font-size: 15px;color: red;line-height:15px;">*</span> <br>
                                            {{ Form::select('sales_person_id',$salesonduty, null, ['class' => 'show-tick', 'id' => 'sales_progress', 'data-live-search' => 'true']) }}
                                        </div>
                                        <p class="help-block error_sales" style="color: #a94442;display: none;">The sales person field is required</p>
                                    </div>
                                    @else
                                        <div class="col-sm-3 col-xs-6">
                                            <div class="form-group form-float">{!! Form::label('sales_person_id', 'Sales Person', ['class' => 'form-label', 'style' => 'font-weight: 100; color: #aaa']) !!}<br>
                                                <div class="form-line">
                                                    <input name="sales_person_id" type="hidden" id="sales_person_id" value="{{ $sales->user_id }}">
                                                    <input type="text" readonly="" class="show-tick form-control" value="{{ $sales->name_sales }}" style="margin-top: -4px;" >
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="form-group form-float">
                                            {!! Form::label('assignment_date', 'Action Date', ['class' => 'form-label', 'style' => 'font-weight: 100; color: #aaa']) !!} <span style="font-size: 15px;color: red;line-height:15px;">*</span><br>
                                            <div class="form-line">
                                                <input name="assignment_date" type="text" id="assignment_date_progress" class="datetimepicker form-control" value="" placeholder="Please choose date & time..." style="margin-top: -4px;" >
                                            </div>
                                            <p class="help-block error_date" style="color: #a94442;display: none;">The action date field is required</p>
                                        </div>
                                    </div>
                                     <div class="col-sm-3 col-xs-6">
                                        <div class="form-group form-float">
                                            {!! Form::label('status_id', 'Action Update', ['class' => 'form-label', 'style' => 'font-weight: 100; color: #aaa']) !!} <span style="font-size: 15px;color: red;line-height:15px;">*</span><br>
                                            <select name="status_id" class="show-tick" id="status_id">
                                                <option value="">Choose Status</option>
                                                @foreach($status as $st)
                                                <option value="{!! $st->id !!}">{!! $st->name_progress !!}</option>
                                                @endforeach
                                            </select>
                                            <p class="help-block error_status" style="color: #a94442;display: none;">The action update field is required</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <div style="margin-top: 20px;">
                                            <input type="button" id="btn_proggress" value="Submitt" class="btn bg-green waves-effect">
                                            <a class="btn btn-danger waves-effect" id="close_new_on_duty"><i class="fa fa-close"></i></a>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-xs-12">
                                        <h2 class="card-inside-title" style="margin-top: 10px; font-weight: normal; color: #aaa;">Person Notes</h2>
                                        <div class="form-float{{ $errors->has('notes') ? 'has-error' : ''}}">
                                            <div class="form-line">
                                                {!! Form::textarea('notes', null, ['class' => 'form-control', 'rows' => '3'] ) !!}
                                            </div>
                                            {!! $errors->first('notes', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>

                                </div>
                                <a class="btn bg-green waves-effect" style="margin-top: 20px;" id="add_new_on_duty"><i class="fa fa-plus"></i> Add New On Duty</a>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="prospect-progress-table" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">#</th><th style="width: 20%">Sales Person</th><th style="width: 15%">Action Date</th><th style="width: 10%">Action Update</th><th style="width: 35%">Person Notes</th><th style="width: 15%">Status Date</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                {!! Form::close() !!}

                                {!! Form::model($prospectsales, [
                                'method' => 'PATCH',
                                'url' => ['/admin/prospect', $prospectsales->id],
                                'class' => 'form-horizontal',
                                'files' => true
                            ]) !!}
                                <h2 class="card-inside-title" style="margin-top: 10px; font-weight: normal; color: #aaa;">Progress Notes</h2>
                                <div class="form-float{{ $errors->has('progress_notes') ? 'has-error' : ''}}">
                                    <div class="form-line">
                                        {!! Form::textarea('progress_notes', null, ['class' => 'form-control', 'rows' => '3'] ) !!}
                                    </div>
                                    {!! $errors->first('progress_notes', '<p class="help-block">:message</p>') !!}
                                </div>
                                <br>


                                <div class="form-group">
                                    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Update', ['class' => 'btn bg-green btn-sm btn-block waves-effect', 'style' => 'font-size:19px;']) !!}
                                </div>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                <!-- /.box -->
                {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        $('#customer_profile_id').change(function(){
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

        $('#btn_company').on('click',function() {

            $(".error_company").css("display","none");

            var customer_profile_id = $("#customer_profile_id").val();
            var error_flag = 0;

            if (customer_profile_id == ""){
                $(".error_company").css("display","inline-block");
                error_flag = 1;
            }
            if (error_flag == 0){
                $('#form_company').submit();
            }

        });


        $('#btn_sales_person').on('click',function() {

            $(".error_sales_person").css("display","none");
            $(".error_date_sales").css("display","none");

            var sales_person = $("#sales_person_select").val();
            var assignment_date_sales = $("#assignment_date_sales").val();
            var error_flag = 0;

            if (sales_person.trim() == ""){
                $(".error_sales_person").css("display","inline-block");
                error_flag = 1;
            }
            if (assignment_date_sales.trim() == ""){
                $(".error_date_sales").css("display","inline-block");
                error_flag = 1;
            }
            if (error_flag == 0){
                $('#form_sales_person').submit();
            }

        });

        $('#btn_proggress').on('click',function() {

            $(".error_sales").css("display","none");
            $(".error_date").css("display","none");
            $(".error_status").css("display","none");

            var sales_progress = $("#sales_progress").val();
            var assignment_date_progress = $("#assignment_date_progress").val();
            var status_id = $("#status_id").val();
            var error_flag = 0;

            if (sales_progress == ""){
                $(".error_sales").css("display","inline-block");
                error_flag = 1;
            }
            if (assignment_date_progress == ""){
                $(".error_date").css("display","inline-block");
                error_flag = 1;
            }
            if (status_id == ""){
                $(".error_status").css("display","inline-block");
                error_flag = 1;
            }

            if (error_flag == 0){
                $('#form-edit-progress').submit();
            }

        });
        $(document).ready(function(){
            $("#field_sales_person").hide();
            $("#field_on_duty").hide();

            // $("#sales_person").click(function(){
            //     $("#field_sales_person").hide();
            //     $("#add_new_sales_person").show();
            // });

            $("#on_duty").click(function(){
                $("#field_on_duty").show();
                $("#add_new_on_duty").hide();
            });
            $("#add_new_sales_person").click(function(){
                $("#field_sales_person").show();
                $("#add_new_sales_person").hide();

            });
            $("#add_new_on_duty").click(function(){
                $("#field_on_duty").show();
                $("#add_new_on_duty").hide();

            });
            $("#close_new_on_duty").click(function(){
                $("#field_on_duty").hide();
                $("#add_new_on_duty").show();

            });
            $("#close_new_sales").click(function(){
                $("#field_sales_person").hide();
                $("#add_new_sales_person").show();

            });
        });

        $("#status_update").click(function(){
            $("#field_on_duty").show();
            $("#field_sales_person").hide();
            $("#add_new_sales_person").show();
            $("#add_new_on_duty").hide();

                $("#collapseTwo").addClass("in");
                $('html, body').animate({
                scrollTop: $("#on_duty").offset().top
                }, 500);
            });

        var oTable;
        oTable = $('#prospect-assignment-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: false,
            scrollX : false,
            paging: false,
            info: false,
            order: [[ 2, "desc" ]],
           // dom : 'Bfrtip',
           // buttons: [
           //     'copy', 'csv', 'excel', 'pdf', 'print'
           // ],
            ajax: '{!! route('prospect-assignment.data', ['id' => $id]) !!}',
            columns: [
                { data: "rownum", name: "rownum" },
                { data: 'name_sales', name: 'name_sales' },
                { data: 'assignment_date', name: 'assignment_date' },
                { data: 'status', name: 'status' }
               // { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    </script>
    <script>
        var oTable;
        oTable = $('#prospect-progress-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: false,
            scrollX : false,
            paging: false,
            info: false,
           // dom : 'Bfrtip',
           // buttons: [
           //     'copy', 'csv', 'excel', 'pdf', 'print'
           // ],
            ajax: '{!! route('prospect-progress.data', ['id' => $id]) !!}',
            columns: [
                { data: "rownum", name: "rownum" },
                { data: 'name_sales', name: 'name_sales' },
                { data: 'assignment_date', name: 'assignment_date' },

                { data: 'name_progress', name: 'name_progress' },
                { data: 'notes', name: 'notes', className: 'notes_warp'},
                {
                    data: 'created_at',
                    type: 'num',
                    render: {
                        _: 'display',
                        sort: 'timestamp'
                    }
                },
               // { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    </script>
    <script>

        //Datetimepicker plugin
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format : 'DD-MM-YYYY HH:mm:ss',
            clearButton: true,
            weekStart: 1,
        });
    </script>
@endpush