@extends('layouts.admin.frame')

@section('title', 'Create New Quote Lists')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/quote-list') }}">Quote Lists</a></li>
    <li class="active">Create New Quote Lists</li>
</ol>

<div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Create New Quote Lists<span class="pull-right">
                                        {!! Form::open(['url' => '/admin/quote-list/'.$quotelist->id.'', 'class' => 'form-horizontal']) !!}
                                            <input type="hidden" name="_method" value="delete">
                                            <button type="submit" class="btn bg-green waves-effect"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></span>
                                    {!! Form::close() !!}
                                </h2>
                            </div>
                        </div>
                    </div>
                    {!! Form::model($quotelist, [
                        'method' => 'PATCH',
                        'url' => ['/admin/quote-list/do_create', $quotelist->id],
                        'class' => 'form-horizontal',
                        'id' => 'form-create',
                        'files' => true,
                    ]) !!}
                    <div class="body">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-float {{ $errors->has('prospect_sales_id') ? 'has-error' : ''}}">
                                    <label class="form-label" style="font-weight: 100; color: #aaa;">Prospect <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                    <select name="prospect_sales_id" id="prospect_sales_id" class="form-control show-tick" data-live-search="true">
                                        <option value=""> -- Select --</option>
                                        @foreach($prospect_sales as $ps)
                                        <option value="{!! $ps->id !!}" @if($quotelist->prospect_sales_id == $ps->id) selected="selected" @endif>{!! $ps->company_name !!} - {!! $ps->name_sales !!}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('prospect_sales_id', '<p class="help-block">:message</p>') !!}
                                </div>
                                <div id="sales_id"></div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-float {{ $errors->has('quote_list_code') ? 'has-error' : ''}}">
                                    <label class="form-label" style="font-weight: 100; color: #aaa;">Quote Name <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                    @if(old('quote_list_code') != '')
                                        @php $quote_list_code = old('quote_list_code'); @endphp
                                    @else
                                        @php $quote_list_code = $quotelist->quote_list_code; @endphp
                                    @endif
                                    <input type="text" name="quote_list_code" id="quote_list_code" class="form-control" value="{{ $quote_list_code }}" placeholder="">
                                    {!! $errors->first('quote_list_code', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="refresh_data">
                                <div class="col-sm-3 col-xs-5">
                                    <hr>
                                    <label class="form-label" style="font-weight: 100; color: #aaa;">Total QTY</label>&nbsp;:&nbsp;
                                    <h4><span class="label label-default">{{ $list_detail->sum('qty') }}</span></h4>
                                    <input type="hidden" name="qty_sum" id="qty_sum" value="{{ $list_detail->sum('qty') }}">
                                </div>
                                <div class="col-sm-3 col-xs-7">
                                    <hr>
                                    <label class="form-label" style="font-weight: 100; color: #aaa;">Total Gross Price</label>&nbsp;:&nbsp;
                                    <h4><span class="label label-default">Rp. {{ number_format($list_detail->sum('gross_price'), 2) }}</span></h4>
                                    <input type="hidden" name="gross_price" id="gross_price_sum" value="{{ $list_detail->sum('price') }}">
                                </div>
                                <div class="col-sm-3 col-xs-5">
                                    <hr>
                                    <label class="form-label" style="font-weight: 100; color: #aaa;">Total Discount</label>&nbsp;:&nbsp;
                                    <h4><span class="label label-default">Rp. {{ number_format($list_detail->sum('diskon_nominal'), 2) }}</span></h4>
                                    <input type="hidden" name="total_diskon" id="diskon_total_sum" value="{{ $list_detail->sum('diskon_nominal') }}">
                                </div>
                                <div class="col-sm-3 col-xs-7">
                                    <hr>
                                    <label class="form-label" style="font-weight: 100; color: #aaa;">Total Price</label>&nbsp;:&nbsp;
                                    <h4><span class="label label-default">Rp. {{ number_format($list_detail->sum('net_price'), 2) }}</span></h4>
                                    <input type="hidden" name="total_price" id="total_price_sum" value="{{ $list_detail->sum('net_price') }}">
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <hr>
                                <div class="form-float {{ $errors->has('date_out') ? 'has-error' : ''}}">
                                    <label class="form-label" style="font-weight: 100; color: #aaa;">Quote Date : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                    @if(old('date_out') != '')
                                        @php $date_out = old('date_out'); @endphp
                                    @else
                                        @php $date_out = $quotelist->date_out; @endphp
                                    @endif
                                    <input name="date_out" type="text" id="date_out" class="datetimepicker form-control" value="{{ isset($date_out) ? $date_out : '' }}" placeholder="Please choose date & time..." style="margin-top: -4px;">
                                    {!! $errors->first('date_out', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <hr>
                                <div class="form-float">
                                    <label class="form-label" style="font-weight: 100; color: #aaa;">Note :</label>
                                    @if(old('note') != '')
                                        @php $note = old('note'); @endphp
                                    @else
                                        @php $note = $quotelist->note; @endphp
                                    @endif
                                    <textarea name="note" id="note" class="form-control" rows="3">{{ $note }}</textarea>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <hr>
                                <div class="form-float {{ $errors->has('quote_template_id') ? 'has-error' : ''}}">
                                    <label class="form-label" style="font-weight: 100; color: #aaa;">Quote Template : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                    <select name="quote_template_id" id="quote_template_id" class="form-control show-tick" onchange="getTemplate(this.value);">
                                        <option value=""> -- Select --</option>
                                        @foreach($getTemplate as $Template)
                                        <option value="{!! $Template->id !!}" @if($quotelist->quote_template_id == $Template->id) selected="selected" @endif>{!! $Template->code !!}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('quote_template_id', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <hr>
                                <div class="form-float {{ $errors->has('term_condition_id') ? 'has-error' : ''}}">
                                    <label class="form-label" style="font-weight: 100; color: #aaa;">Term & Condition :</label>
                                    <select name="term_condition_id" id="term_condition_id" class="form-control show-tick" onchange="getTermCondition(this.value);">
                                        <option value=""> -- Select --</option>
                                        @foreach($getTerm as $term)
                                        <option value="{!! $term->id !!}" @if($quotelist->term_condition_id == $term->id) selected="selected" @endif>{!! $term->code !!}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('term_condition_id', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                        <hr>
                        
                    </div>
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Quote Detail <span style="font-size: 15px;color: red;line-height:15px;">*</span>
                                    <!-- <span class="pull-right"><a href="{{ url('/admin/quote-list') }}" title="Back"><button class="btn bg-green waves-effect">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span> -->
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        @if($quotelist->prospect_sales_id != '')
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th><th width="30%">Name Product</th><th width="10%">Image</th><th width="5%">Qty</th><th width="20%">Price (Rp.)</th><th width="15%">Gross Price (Rp.)</th><th width="5%">Disc (%)</th><th width="15%">Disc (Rp.)</th><th width="10%">Net Price</th><th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="detail-table">
                                </tbody>
                                <tfoot id="detail-table-tfoot">
                                    <tr class="table-secondary">
                                        <td></td>
                                        <td>
                                            <select name="product_id" id="product_idd" class="form-control show-tick" data-live-search ="true">
                                            <option value="">-- Choose Product --</option>
                                            @foreach($selectlist as $list)
                                                <option value="{{ $list->id }}">{{ $list->name }}</option>';
                                            @endforeach
                                            </select>
                                        </td>
                                        <td></td>
                                        <td><input type="text" class="form-control" name="qty" id="qty" value="1" maxlength="5" onkeyup="this.value = qty(this.value, 1, 9999)" style="width:60px;"></td>
                                        <td id="price"></td>
                                        <td id="gross_price"></td>
                                        <td><input type="text" class="form-control" name="diskon" id="diskon" value="" onkeyup="this.value = diskon(this.value, 0, 100)" style="width:60px;"></td>
                                        <td><input type="text" class="form-control text-right" name="diskon_nominal" id="diskon_nominal" value="" onkeyup="this.value = diskon_nominal(this.value, 0, 1000000000)"></td>
                                        <td id="net_price" contenteditable="false"></td>
                                        <td align="center"><button type="button" name="btn_add" id="btn_add" class="btn btn-info btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add</button></td>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th class="text-right"></th>
                                        <th>{{ $list_detail->sum('qty') }}</th>
                                        <th></th>
                                        <th>{{ number_format($list_detail->sum('gross_price'), 2) }}</th>
                                        <th></th>
                                        <th>{{ number_format($list_detail->sum('diskon_nominal'), 2) }}</th>
                                        <th>{{ number_format($list_detail->sum('net_price'), 2) }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <hr>
                        <table class="table" style="width: 100%">
                            <tr>
                                <th width="20%"></th>
                                <th width="15%">Choose Tax :</th>
                                <td width="15%">
                                    <div id="tax_select">
                                        <select name="choose_tax" id="choose_tax" class="form-control choose_tax">
                                            @if($quotelist->choose_tax == 0 || $quotelist->choose_tax == '')
                                                <option value="0" selected="">Don't use tax</option>
                                                <option value="1">Using tax</option>
                                            @elseif($quotelist->choose_tax == 1)
                                                <option value="1" selected="">Using tax</option>
                                                <option value="0">Don't use tax</option>
                                            @endif
                                        </select>
                                    </div>
                                </td>
                                <th width="10%">Tax (%) : </th>
                                <td width="10%">
                                    @if($quotelist->choose_tax == 0 || $quotelist->choose_tax == '')
                                        <div id="input_tax"> 
                                            <input type="text" name="tax" id="tax" value="0" class="form-control" maxlength="4" style="width: 60px;" disabled="">
                                        </div>
                                    @elseif($quotelist->choose_tax == 1)
                                        <div id="input_tax">
                                            <input type="text" name="tax" id="tax" value="{!! $quotelist->tax !!}" class="form-control" maxlength="4" style="width: 60px;">
                                        </div>
                                    @endif
                                </td>
                                <th width="15%">Tax (Rp.) : </th>
                                <th width="15%">
                                    <div id="tex_rupiah">{{ number_format($quotelist->tax_price, 2, '.', ',') }}</div>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="5"></th>
                                <th>After Tax (Rp.) :</th>
                                <th>
                                    <input type="hidden" name="after_tax" id="after_tax" value="{!! $quotelist->after_tax !!}" class="form-control">
                                    <div class="after_tax">{!! number_format($quotelist->after_tax,2) !!}</div>
                                </th>
                            </tr>
                        </table>
                        <hr>
                        <div class="form-group text-center">
                            @if(count($list_detail) > 0)
                                {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create Quote', ['class' => 'btn bg-green btn-sm btn-block waves-effect', 'style' => 'font-size:19px;']) !!}
                            @else
                                <h4><div style="color: red;"><i>*Quote detail tidak boleh kosong</i></div></h4>
                                <input type="button" disabled="disabled" value="Create Quote" class="btn bg-green btn-sm btn-block waves-effect" style="font-size:19px;">
                            @endif
                        </div>

                        {!! Form::close() !!}
                        @else
                        <hr>
                        <h4><div style="color: red;"><i>*Please select the Prospect first</i></div></h4>
                        <hr>
                        <div class="form-group text-center">
                            <input type="button" disabled="disabled" value="Create Quote" class="btn bg-green btn-sm btn-block waves-effect" style="font-size:19px;">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
        function getTemplate(quote_template_id){
            var quote_list_id = "{{ $quotelist->id }}";
            $.ajax({
                   url : '{{ url("admin/quote-list/getTemplate") }}',
                   method : "post",
                   data : {
                        quote_list_id     :quote_list_id,
                        quote_template_id :quote_template_id,
                        _token            :"{{csrf_token()}}"
                   },
                   success : function (data)
                   {
                        // console.log(data);
                   }
                });
        }
        
        function getTermCondition(term_condition_id){
            var quote_list_id = "{{ $quotelist->id }}";
            $.ajax({
                   url : '{{ url("admin/quote-list/getTermCondition") }}',
                   method : "post",
                   data : {
                        quote_list_id     :quote_list_id,
                        term_condition_id :term_condition_id,
                        _token            :"{{csrf_token()}}"
                   },
                   success : function (data)
                   {
                        // console.log(data);
                   }
                });
        }

        $('#btn_create').on('click',function() {
                
            $('#form-create').submit();
        });

        $('#prospect_sales_id').on('change', function(){
            var prospect_sales_id = $(this).val();
            var quote_list_id = "{{ $quotelist->id }}";
            var quote_list_code = $('#quote_list_code').val();
            var date_out = $('#date_out').val();
            var note = $('#note').val();

            @if($quotelist->prospect_sales_id == '0' || $quotelist->prospect_sales_id == '')
                $.ajax({
                   url : '{{ url("admin/quote-list/sales") }}',
                   method : "POST",
                   data : {
                        quote_list_id:quote_list_id,
                        prospect_sales_id:prospect_sales_id,
                        quote_list_code:quote_list_code,
                        date_out:date_out,
                        note:note,
                        _token:"{{csrf_token()}}"
                   },
                   dataType : "text",
                   success : function (data)
                   {
                    // console.log(data);
                    window.location.reload();
                   }
                });
            @else
                swal({
                    title: "Selecting a different Prospect will change the Data of Quote Detail Data bellow accordingly",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, Change it!",
                    cancelButtonText: "No, cancel!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                           url : '{{ url("admin/quote-list/sales") }}',
                           method : "POST",
                           data : {
                                quote_list_id:quote_list_id,
                                prospect_sales_id:prospect_sales_id,
                                quote_list_code:quote_list_code,
                                date_out:date_out,
                                note:note,
                                _token:"{{csrf_token()}}"
                           },
                           dataType : "text",
                           success : function (data)
                           {
                            // console.log(data);
                            window.location.reload();
                           }
                       });
                    } else {
                        swal("Cancelled", "Your data is safe :)", "error");
                        window.location.reload();
                    }

                });
            @endif
        });

        $(document).on('click', '#btn_create_detail', function(){
            var quote_list_id = '{{ $quotelist->id }}';
            var qty = $('#qty_sum').val();
            var gross_price = $('#gross_price_sum').val();
            var diskon_total = $('#diskon_total_sum').val();
            var total_price = $('#total_price_sum').val();
            $.ajax({
                url : '{{ url("admin/quote-list/update_data_sum/") }}' + "/" + quote_list_id,
                method : "POST",
                data : {
                    "quote_list_id":quote_list_id,
                    "qty":qty,
                    "gross_price":gross_price,
                    "diskon_total":diskon_total,
                    "total_price":total_price,
                    _token:"{{csrf_token()}}"
                },
                dataType : "json",
                success : function (data)
                {
                    // console.log(data.data);
                    swal({
                        position: 'center-end',
                        type: 'success',
                        title: 'Your data already created',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.href = "{{ URL('admin/quote-list') }}";
                },
                error: function(){
                    // console.log(data);
                    swal({
                        position: 'center-end',
                        type: 'error',
                        title: 'Create data is Failed',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });

        $(document).ready(function(){
            @if(count($list_detail) > 0)
                function Show_data_detail() {
                    var product_id = $('#product_idd').val();
                    var quote_list_id = "{{ $quotelist->id }}";
                    var prospect_sales_id = "{{ $quotelist->prospect_sales_id }}";
                    $.ajax({
                        url : '{{ url("admin/quote-list/get_data_detail") }}',
                        method : "POST",
                        data : {
                                "quote_list_id":quote_list_id,
                                "product_id":product_id,
                                "prospect_sales_id":prospect_sales_id,
                                _token:"{{csrf_token()}}" },
                        dataType : "text",
                        success : function (data)
                        {
                            // console.log(data);
                           if(data != '')
                           {
                                $('#detail-table').html(data);
                                
                           }
                        }
                    });
                }
                Show_data_detail();
            @endif

            function New_data_detail() {
                var quote_list_id = "{{ $quotelist->id }}";
                var prospect_sales_id = "{{ $quotelist->prospect_sales_id }}";
                $.ajax({
                    url : '{{ url("admin/quote-list/new_data_detail") }}',
                    method : "POST",
                    data : {
                            "quote_list_id":quote_list_id,
                            "prospect_sales_id":prospect_sales_id,
                            _token:"{{csrf_token()}}" },
                    dataType : "text",
                    success : function (data)
                    {
                        // console.log(data);
                       if(data != '')
                       {
                            $('#detail-table-tfoot').html(data);
                            
                       }
                    }
                });
            }
            New_data_detail();

            function Refresh_data() {
                var quote_list_id = "{{ $quotelist->id }}";
                var prospect_sales_id = "{{ $quotelist->prospect_sales_id }}";
                $.ajax({
                    url : '{{ url("admin/quote-list/refresh_data") }}',
                    method : "POST",
                    data : {
                            "quote_list_id":quote_list_id,
                            "prospect_sales_id":prospect_sales_id,
                            _token:"{{csrf_token()}}" },
                    dataType : "text",
                    success : function (data)
                    {
                        // console.log(data);
                       if(data != '')
                       {
                            $('#refresh_data').html(data);
                            
                       }
                    }
                });
            }
            Refresh_data();
                
            $(document).on('click', '#btn_add', function(){
                var quote_list_id = '{{ $quotelist->id }}';
                var quote_list_code = $('#quote_list_code').val();
                var date_out = $('#date_out').val();
                var note = $('#note').val();
                var quote_template_id   = $('#quote_template_id').val();
                var term_condition_id   = $('#term_condition_id').val();
                var prospect_sales_id = '{{ $quotelist->prospect_sales_id }}';
                var brand = $('#brand').val();
                var product_id = $('#product_idd').val();
                var product_name = $('#product_name').val();
                var qty = $('#qty').val();
                var quality = $('#quality').val();
                var price = $('#price').val();
                var gross_price = $('#gross_price').text();
                var diskon = $('#diskon').val();
                var diskon_nominal = $('#diskon_nominal').val();
                var net_price = $('#net_price').text();
                $.ajax({
                    url : '{{ url("admin/quote-list/save_data_detail") }}',
                    method : "POST",
                    data : {
                        "quote_list_id"     :quote_list_id,
                        "quote_list_code"   :quote_list_code,
                        "date_out"          :date_out,
                        "note"              :note,
                        "quote_template_id" :quote_template_id,
                        "term_condition_id" :term_condition_id,
                        "prospect_sales_id" :prospect_sales_id,
                        "product_id"        :product_id,
                        "product_name"      :product_name,
                        "brand"             :brand,
                        "qty"               :qty,
                        "quality"           :quality,
                        "price"             :price,
                        "gross_price"       :gross_price,
                        "diskon"            :diskon,
                        "diskon_nominal"    :diskon_nominal,
                        "net_price"         :net_price,
                        _token              :"{{csrf_token()}}"
                    },
                    dataType : "text",
                    success : function (data)
                    {
                        // console.log(data);
                        swal({
                            position: 'center-end',
                            type: 'success',
                            title: 'Your data already created',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.location.reload();
                    },
                    error: function(data)
                    {
                        swal({
                            position: 'center-end',
                            type: 'error',
                            title: 'Your data is Required',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            });

            

            $(document).on('input', '#qty', function(){
                var qty = $(this).val();
                var price = $('#price').val();
                var diskon = $('#diskon').val();
                var gross_price = qty * price;
                var diskon_nominal = (diskon / 100) * gross_price;
                var net_price = gross_price - diskon_nominal;

                $('#diskon_nominal').val(diskon_nominal);
                $('#gross_price').html(insertCommas(gross_price+'.00' ));
                $('#net_price').html(insertCommas(net_price+'.00' ));
            });

            $(document).on('input', '#diskon', function(){
                var diskon = $(this).val();
                var grosscom = $('#gross_price').text();
                var grosscom2 = grosscom.replace(",", "");
                var grosscom3 = grosscom2.replace(",", "");
                var grossdot = grosscom3.replace(",", "");
                var gros = grossdot.replace(".", "");
                var gross = gros.length;
                var grossa = gross - 2;
                var gross_price = gros.substr(0,grossa);

                var diskon_nominal = (diskon / 100) * gross_price;
                var net_price = gross_price - diskon_nominal;
                $('#diskon_nominal').val(diskon_nominal);
                $('#net_price').html(insertCommas(net_price+'.00' ));
            });

            $(document).on('input', '#diskon_nominal', function(){
                var diskon_nominal = $(this).val();
                var grosscom = $('#gross_price').text();
                var grosscom2 = grosscom.replace(",", "");
                var grosscom3 = grosscom2.replace(",", "");
                var grossdot = grosscom3.replace(",", "");
                var gros = grossdot.replace(".", "");
                var gross = gros.length;
                var grossa = gross - 2;
                var gross_price = gros.substr(0,grossa);
                var diskon = (diskon_nominal / gross_price) * 100;
                var net_price = gross_price - diskon_nominal;
                $('#diskon').val(diskon);
                $('#net_price').html(insertCommas(net_price+'.00' ));
            });

            $(document).on('change', '#product_idd', function(){
                var product_id = $(this).val();
                var quote_list_id = '{{ $quotelist->id }}';
                var prospect_sales_id = '{{ $quotelist->prospect_sales_id }}';
                $.ajax({
                   url : '{{ url("admin/quote-list/getproduct") }}',
                   method : "POST",
                   data : {
                        "quote_list_id":quote_list_id,
                        "product_id":product_id,
                        "prospect_sales_id":prospect_sales_id,
                        _token:"{{csrf_token()}}" },
                   dataType : "text",
                   success : function (data)
                   {
                        // console.log(data);
                        $('#detail-table-tfoot').html(data);
                   },
                    error: function(){
                        $('#price').val('');
                        $('#gross_price').val('');
                    }
                });
            }).change();

            @php $no=1; @endphp
            @foreach($list_detail as $detail)
                $(document).on('input', '.qty_{{ $no }}', function(){
                    var qty = $(this).val();

                    var p = $('.price_{{ $no }}').text();
                    var pr = p.replace(",", "");
                    var pri = pr.replace(",", "");
                    var pric = pri.replace(",", "");
                    var ppric = pric.replace(".", "");
                    var pprric = ppric.length;
                    var pprriic = pprric - 2;
                    var price = ppric.substr(0,pprriic);

                    var diskon = $('.diskon_{{ $no }}').val();
                    var gross_price = qty * price;
                    var diskon_nominal = (diskon / 100) * gross_price;
                    var net_price = gross_price - diskon_nominal;

                    $('.diskon_nominal_{{ $no }}').val(diskon_nominal);
                    $('.gross_price_{{ $no }}').html(insertCommas(gross_price+'.00' ));
                    $('.net_price_{{ $no }}').html(insertCommas(net_price+'.00' ));
                });
                
                $(document).on('input', '.diskon_{{ $no }}', function(){
                    var diskon = $(this).val();
                    var grosscom = $('.gross_price_{{ $no }}').text();
                    var grosscom2 = grosscom.replace(",", "");
                    var grosscom3 = grosscom2.replace(",", "");
                    var grossdot = grosscom3.replace(",", "");
                    var gros = grossdot.replace(".", "");
                    var gross = gros.length;
                    var grossa = gross - 2;
                    var gross_price = gros.substr(0,grossa);

                    var diskon_nominal = (diskon / 100) * gross_price;
                    var net_price = gross_price - diskon_nominal;
                    $('.diskon_nominal_{{ $no }}').val(diskon_nominal);
                    $('.net_price_{{ $no }}').html(insertCommas(net_price+'.00' ));
                });
                
                $(document).on('input', '.diskon_nominal_{{ $no }}', function(){
                    var diskon_nominal  = $(this).val();
                    var grosscom    = $('.gross_price_{{ $no }}').text();
                    var grosscom2   = grosscom.replace(",", "");
                    var grosscom3   = grosscom2.replace(",", "");
                    var grossdot    = grosscom3.replace(",", "");
                    var gros        = grossdot.replace(".", "");
                    var gross       = gros.length;
                    var grossa      = gross - 2;
                    var gross_price     = gros.substr(0,grossa);
                    var diskon          = (diskon_nominal / gross_price) * 100;
                    var net_price       = gross_price - diskon_nominal;
                    $('.diskon_{{ $no }}').val(diskon);
                    $('.net_price_{{ $no }}').html(insertCommas(net_price+'.00' ));
                });
                
                $(document).on('click', '#btn_update_{{ $no }}', function(){
                    var id                  = $('#id_detail_{{ $no }}').val();
                    var quote_list_code     = $('#quote_list_code').val();
                    var date_out            = $('#date_out').val();
                    var note                = $('#note').val();
                    var quote_template_id   = $('#quote_template_id').val();
                    var term_condition_id   = $('#term_condition_id').val();
                    var brand               = $('.brand_{{ $no }}').val();
                    var product_id          = $('#product_id_{{ $no }}').val();
                    var qty                 = $('.qty_{{ $no }}').val();
                    var quality             = $('.quality_{{ $no }}').val();
                    var p           = $('.price_{{ $no }}').text();
                    var pr          = p.replace(",", "");
                    var pri         = pr.replace(",", "");
                    var pric        = pri.replace(",", "");
                    var ppric       = pric.replace(".", "");
                    var pprric      = ppric.length;
                    var pprriic     = pprric - 2;
                    var price               = ppric.substr(0,pprriic);
                    var gross_price         = qty * price;
                    var diskon              = $('.diskon_{{ $no }}').val();
                    var diskon_nominal      = $('.diskon_nominal_{{ $no }}').val();
                    var net_price           = gross_price - diskon_nominal;
                    var choose_tax          = $('#choose_tax').val();
                    var tax                 = $('#tax').val();

                    $.ajax({
                        url     : '{{ URL("admin/quote-list/do_btn_update") }}' + "/" + id,
                        method  :"POST",
                        data    :{
                            "product_id"        :product_id,
                            "quote_list_id"     :{{ $quotelist->id }},
                            "quote_list_code"   :quote_list_code,
                            "date_out"          :date_out,
                            "note"              :note,
                            "quote_template_id" :quote_template_id,
                            "term_condition_id" :term_condition_id,
                            "prospect_sales_id" :'{{ $quotelist->prospect_sales_id }}',
                            "brand"             : brand,
                            "qty"               :qty,
                            "gross_price"       :gross_price,
                            "diskon"            :diskon,
                            "diskon_nominal"    :diskon_nominal,
                            "net_price"         :net_price,
                            "price"             :price,
                            "choose_tax"        :choose_tax,
                            "tax"               :tax,
                            "_token"            :"{{csrf_token()}}"},
                        dataType:"json",
                        success:function(data){
                            // console.log(data);
                            swal({
                                position            : 'center-end',
                                type                : 'success',
                                title               : 'Your data already updated',
                                showConfirmButton   : false,
                                timer               : 1500
                            });
                            $('#tex_rupiah').html(insertCommas(data.data.tax_price+'.00'));
                            $('.after_tax').html(insertCommas(data.data.after_tax+'.00'));
                            Show_data_detail();
                            New_data_detail();
                            Refresh_data();
                        },
                        error: function(){
                            swal({
                                position: 'center-end',
                                type: 'error',
                                title: 'Your data failed to updated',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            window.location.reload();
                        }
                    });
                });
                

            function edit_data(id, product_id, qty)
            {
                $.ajax({
                    url: '{{ URL("admin/quote-list/do_update_product") }}' + "/" + id,
                    method:"POST",
                    data:{
                        "product_id":product_id,
                        "quote_list_id":'{{ $quotelist->id }}',
                        "prospect_sales_id":'{{ $quotelist->prospect_sales_id }}',
                        "qty":qty,
                        "_token":"{{csrf_token()}}"},
                    dataType:"text",
                    success:function(data){
                        // swal({
                        //     position: 'center-end',
                        //     type: 'success',
                        //     title: 'Your data already updated',
                        //     showConfirmButton: false,
                        //     timer: 1500
                        // });
                        Show_data_detail();
                        New_data_detail();
                        Refresh_data();
                    },
                    error: function(){
                        swal({
                            position: 'center-end',
                            type: 'error',
                            title: 'Your data failed to updated',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.location.reload();
                    }
                });
            }
           
                $(document).on('change', '#product_id_{{ $no }}', function(){
                    var id = $('#id_detail_{{ $no }}').val();
                    var qty = $('.qty_{{ $no }}').val();
                    var product_id = $('#product_id_{{ $no }}').val();

                    edit_data(id, product_id, qty);
                });

                $(document).on('click', '#btn_delete_{{ $no }}', function(){
                    var id = $('#id_detail_{{ $no }}').val();
                    swal({
                        title: "Are you sure?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '{{URL("admin/quote-list/delete_data_detail")}}' + "/" + id,
                                method:"POST",
                                data:{
                                    "quote_list_id":'{{ $quotelist->id }}',
                                    "prospect_sales_id":'{{ $quotelist->prospect_sales_id }}',
                                    _token:"{{csrf_token()}}"},
                                dataType:"text",
                                success:function(data){
                                    swal({
                                        position: 'center-end',
                                        type: 'success',
                                        title: 'Your data already deleted !',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });

                                    window.location.reload();
                                    Show_data_detail();
                                    New_data_detail();
                                    Refresh_data();
                                }
                            });
                        } else {
                            swal("Cancelled", "Your data is safe :)", "error");
                        }

                    });
                });
                @php $no++ @endphp
            @endforeach


        $('#choose_tax').on('change', function(){
            var choose_tax = $(this).val();
            var prospect_sales_id = $('#prospect_sales_id').val();
            var quote_list_id = "{{ $quotelist->id }}";
            var quote_list_code = $('#quote_list_code').val();
            var date_out = $('#date_out').val();
            var note = $('#note').val();

            $.ajax({
               url : '{{ url("admin/quote-list/choose_tax") }}',
               method : "POST",
               data : {
                    choose_tax:choose_tax,
                    prospect_sales_id:prospect_sales_id,
                    quote_list_id:quote_list_id,
                    quote_list_code:quote_list_code,
                    date_out:date_out,
                    note:note,
                    _token:"{{csrf_token()}}"
               },
               dataType : "text",
               success : function (data)
               {
                var hasil = jQuery.parseJSON(data);
                // console.log(hasil.data);
                if(hasil.data.choose_tax == 1){
                    html = '<input type="text" name="tax" id="tax" value="'+ hasil.data.tax +'" class="form-control" maxlength="4" style="width: 60px;">';
                }else{
                    html = '<input type="text" name="tax" value="0" class="form-control" maxlength="4" style="width: 60px;" disabled="">';
                }
                $('#tex_rupiah').html(insertCommas(hasil.data.tax_price+'.00'));
                $('#input_tax').html(html);
                $('.after_tax').html(insertCommas(hasil.data.after_tax+'.00'));
                $('#after_tax').val(hasil.data.after_tax);
                var html = '<div id="total_amount"><b>Total Outstanding Amount : Rp. '+insertCommas(hasil.data.after_tax+'.00')+'</b></div>';
                $('#total_amount').html(html);

                
                // window.location.reload();
               }
           });
        });

        $(document).on('input', '#tax', function(){
            var tax = $(this).val();
            var quote_list_id = '{{ $quotelist->id }}';
            var prospect_sales_id = '{{ $quotelist->prospect_sales_id }}';

            $.ajax({
                url : '{{ url("admin/quote-list/tax") }}',
                method : "POST",
                data : {
                    "quote_list_id":quote_list_id,
                    "prospect_sales_id":prospect_sales_id,
                    "tax":tax,
                    _token:"{{csrf_token()}}"
                },
                dataType : "json",
                success : function (data)
                {
                    if(data.data.choose_tax == 1){
                    }else{
                        htmll = '<input type="text" name="tax" id="tax" value="" class="form-control" maxlength="4" style="width: 60px;">';
                        $('#input_tax').html(htmll);
                        // $(".choose_tax option[value="+data.data.choose_tax+"]").attr('selected','selected');
                    }
                    $('#tex_rupiah').html(insertCommas(data.data.tax_price+'.00' ));
                    $('.after_tax').html(insertCommas(data.data.after_tax+'.00' ));
                    $('#after_tax').val(data.data.after_tax);
                    var html = '<div id="total_amount"><b>Total Outstanding Amount : Rp. '+insertCommas(data.data.after_tax+'.00' )+'</b></div>';
                    $('#total_amount').html(html);
                    
                    // window.location.reload();
                },
                error: function(data)
                {
                    swal({
                        position: 'center-end',
                        type: 'error',
                        title: 'Must be Number',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });

        });

        });
        function qty(value, min, max) {
            if(parseInt(value) < min || isNaN(parseInt(value))) 
                return value; 
            else if(parseInt(value) > max) 
                return 9999; 
            else return value;
        }

        function diskon(value, min, max) {
            if(parseInt(value) < min || isNaN(parseInt(value))) 
                return value; 
            else if(parseInt(value) > max) 
                return 1000; 
            else return value;
        }

        function diskon_nominal(value, min, max) {
            if(parseInt(value) < min || isNaN(parseInt(value))) 
                return value; 
            else if(parseInt(value) > max) 
                return 1000000000; 
            else return value;
        }
    </script>
    <script>
        function insertCommas(s) {

            // get stuff before the dot
            var d = s.indexOf('.');
            var s2 = d === -1 ? s : s.slice(0, d);

            // insert commas every 3 digits from the right
            for (var i = s2.length - 3; i > 0; i -= 3)
              s2 = s2.slice(0, i) + ',' + s2.slice(i);

            // append fractional part
            if (d !== -1)
              s2 += s.slice(d);

            return s2;

        }
        //Datetimepicker plugin
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format : 'DD-MM-YYYY HH:mm:ss',
            clearButton: true,
            weekStart: 1,
        });
    </script>
@endpush