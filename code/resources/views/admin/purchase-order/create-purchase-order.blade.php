@extends('layouts.admin.frame')

@section('title', 'Edit PO Lists')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/quote-list') }}">Purchase Order Lists</a></li>
    <li class="active">Purchase Order Transaction</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Purchase Order<span class="pull-right"><a href="{{ url('/admin/purchase-order-list') }}" class="btn bg-green waves-effect" title="Back">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                {!! Form::model($POLists, [
                    'method' => 'PATCH',
                    'url' => ['/admin/purchase-order-list/do_create', $POLists->id],
                    'class' => 'form-horizontal',
                    'files' => true,
                ]) !!}
                <div class="body">
                    <div class="row">
                        
                        <div class="col-sm-3 col-xs-12">
                            <div class="form-float {{ $errors->has('quote_list_code_id') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Quote Name : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                <select name="quote_list_code_id" id="quote_list_code_id" class="form-control show-tick">
                                    <option value=""> -- Select --</option>
                                    @foreach($QuoteCode as $qc)
                                    <option value="{!! $qc->id !!}" @if($POLists->quote_list_code_id == $qc->id) selected="selected" @endif>{!! $qc->quote_list_code !!}</option>
                                    @endforeach
                                </select>
                                {!! $errors->first('quote_list_code_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12">
                            <label class="form-label" style="font-weight: 100; color: #aaa;">Quote Prospect Sales</label>&nbsp;:&nbsp;
                            <h4><span class="label label-default">{{ $Prospect }}&nbsp;-&nbsp;{{ $salesPerson }}</span></h4>
                        </div>
                        <div class="col-sm-3 col-xs-12">
                            <div class="form-float {{ $errors->has('purchase_order_list_code') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Purchase Order Name : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                <input type="text" name="purchase_order_list_code" id="purchase_order_list_code" class="form-control" value="@if(old('purchase_order_list_code') != ''){{ old('purchase_order_list_code') }}@else{{ $POLists->purchase_order_list_code }}@endif" placeholder="">
                                {!! $errors->first('purchase_order_list_code', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12">
                            <div class="form-float {{ $errors->has('po_prospect_sales_id') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Prospect Sales : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                @if($POLists->quote_list_code_id != '')
                                    <select name="po_prospect_sales_id" id="po_prospect_sales_id" class="form-control show-tick">
                                        <option value=""> -- Select --</option>
                                        @foreach($getProspectSales as $ProspectSales)
                                        <option value="{!! $ProspectSales->sales_person_id !!}" @if($POLists->po_prospect_sales_id == $ProspectSales->sales_person_id) selected="selected" @endif>{!! $ProspectSales->name_sales !!}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('po_prospect_sales_id', '<p class="help-block">:message</p>') !!}
                                @else
                                    <select name="po_prospect_sales_id" class="form-control show-tick" disabled="">
                                        <option value=""> -- Select --</option>
                                        @foreach($getProspectSales as $ProspectSales)
                                        <option value="{!! $ProspectSales->sales_person_id !!}" @if($POLists->po_prospect_sales_id == $ProspectSales->sales_person_id) selected="selected" @endif>{!! $ProspectSales->name_sales !!}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('po_prospect_sales_id', '<p class="help-block">:message</p>') !!}
                                    <p><div style="color: red;"><i>*Please Select the Quote Name First</i></div></p>
                                @endif
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
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Purchase Order Date : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                <input name="date_out" type="text" id="date_out" class="datetimepicker form-control" value="{{ isset($POLists->date_out) ? $POLists->date_out : '' }}" placeholder="Please choose date & time..." style="margin-top: -4px;">
                                {!! $errors->first('date_out', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <hr>
                            <div class="form-float">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Note :</label>
                                <textarea name="note" id="note" class="form-control" rows="3">{{ $POLists->note }}</textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Purchase Order (Transaction) <span style="font-size: 15px;color: red;line-height:15px;">*</span></h2>
                        </div>
                    </div>
                </div>
                <div class="body">
                    @if($POLists->quote_list_code_id != '')
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">#</th><th width="30%">Name Product</th><th width="5%">Qty</th><th width="20%">Price (Rp.)</th><th width="15%">Gross Price (Rp.)</th><th width="5%">Disc (%)</th><th width="15%">Disc (Rp.)</th><th width="10%">Net Price</th><th width="5%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="detail-table">
                            </tbody>
                            <tfoot id="detail-table-tfoot">
                            <tr class="table-secondary">
                                <td></td>
                                <td>
                                    <select name="product_id" id="product_idd" class="form-control">
                                    <option value="">-- Choose Product --</option>
                                    @foreach($selectlist as $list)
                                        <option value="{{ $list->id }}">{{ $list->name }}</option>';
                                    @endforeach
                                    </select>
                                </td>
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
                    <div class="form-float {{ $errors->has('files') ? 'has-error' : ''}}">
                        <label class="form-label" style="font-weight: 100; color: #aaa;">Purchase Order File</label>
                        {!! Form::file('files', null, ['class' => 'form-control']) !!}
                        @if(isset($POLists->file) != '')
                            <a href="{{ url('/') }}/files/purchase-order/{{ $POLists->file }}" download="{{ $POLists->purchase_order_list_code }}" target="_blank">{{ $POLists->file }}</a>

                        @endif
                        {!! $errors->first('files', '<p class="help-block">:message</p>') !!}
                    </div>
                    <hr>
                    <div class="form-group text-center">
                        @if(count($list_detail) > 0)    
                            {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create Purchase Order', ['class' => 'btn bg-green btn-sm btn-block waves-effect', 'style' => 'font-size:19px;']) !!}
                        @else
                            <h4><div style="color: red;"><i>*Purchase Order (Transaction) cant't be empty</i></div></h4>
                            <input type="button" disabled="disabled" value="Create Purchase Order" class="btn bg-green btn-sm btn-block waves-effect" style="font-size:19px;">
                        @endif
                    </div>
                    {!! Form::close() !!}
                    @else
                    <h4><div style="color: red;"><i>*Please Select the Quote Name First</i></div></h4>
                        <hr>
                        <div class="form-group text-center">
                            <input type="button" disabled="disabled" value="Create Purchase Order" class="btn bg-green btn-sm btn-block waves-effect" style="font-size:19px;">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>

        $('#quote_list_code_id').on('change', function(){
            var po_id = "{{ $POLists->id }}";
            var quote_list_code_id = $(this).val();
            var purchase_order_list_code = $('#purchase_order_list_code').val();
            var date_out = $('#date_out').val();
            var note = $('#note').val();

            @if($POLists->quote_list_code_id == '' || $POLists->quote_list_code_id == '0')
                $.ajax({
                    url : '{{ url("admin/po/getquote") }}',
                    method : "POST",
                    data : {
                        po_list_id:po_id,
                        quote_list_code_id:quote_list_code_id,
                        purchase_order_list_code:purchase_order_list_code,
                        date_out:date_out,
                        note:note,
                        _token:"{{csrf_token()}}"
                    },
                    dataType : "text",
                    success : function (data)
                    {
                        // console.log(data);
                        swal({
                                position: 'center-end',
                                type: 'success',
                                title: 'Creating a new Quote Name successfully',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            window.location.reload();
                   }
                });
            @else
                swal({
                    title: "Selecting a different Quote Name will change the Data of Purchase Order (Transaction) Data bellow accordingly",
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
                            url : '{{ url("admin/po/getquote") }}',
                            method : "POST",
                            data : {
                                po_list_id:po_id,
                                quote_list_code_id:quote_list_code_id,
                                purchase_order_list_code:purchase_order_list_code,
                                date_out:date_out,
                                note:note,
                                _token:"{{csrf_token()}}"
                            },
                            dataType : "text",
                            success : function (data)
                            {
                                // console.log(data);
                                swal({
                                        position: 'center-end',
                                        type: 'success',
                                        title: 'Quote Name is changed successfully',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    window.location.reload();
                           }
                        });
                    } else {
                        swal("Cancelled", "Your data is safe.", "error");
                        window.location.reload();
                    }

                });
            @endif
        });

        $(document).ready(function(){
            function Show_data_detail() {
                var product_id = $('#product_idd').val();
                var po_list_id = "{{ $POLists->id }}";
                var quote_prospect_sales_id = "{{ $POLists->quote_prospect_sales_id }}";
                $.ajax({
                    url : '{{ url("admin/po/get_data_detail") }}',
                    method : "POST",
                    data : {
                            "po_list_id":po_list_id,
                            "product_id":product_id,
                            "quote_prospect_sales_id":quote_prospect_sales_id,
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

            function New_data_detail() {
                var po_list_id = "{{ $POLists->id }}";
                var quote_prospect_sales_id = "{{ $POLists->quote_prospect_sales_id }}";
                $.ajax({
                    url : '{{ url("admin/po/new_data_detail") }}',
                    method : "POST",
                    data : {
                            "po_list_id":po_list_id,
                            "quote_prospect_sales_id":quote_prospect_sales_id,
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
                var po_list_id = "{{ $POLists->id }}";
                var quote_prospect_sales_id = "{{ $POLists->quote_prospect_sales_id }}";
                $.ajax({
                    url : '{{ url("admin/po/refresh_data") }}',
                    method : "POST",
                    data : {
                            "po_list_id":po_list_id,
                            "quote_prospect_sales_id":quote_prospect_sales_id,
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
                var po_list_id = {{ $POLists->id }};
                var quote_prospect_sales_id = {{ $POLists->quote_prospect_sales_id }};
                var purchase_order_list_code = $('#purchase_order_list_code').val();
                var po_prospect_sales_id = $('#po_prospect_sales_id').val();
                var date_out = $('#date_out').val();
                var note = $('#note').val();
                var product_id = $('#product_idd').val();
                var product_name = $('#product_name').val();
                var qty = $('#qty').val();
                var price = $('#price').val();
                var gross_price = $('#gross_price').text();
                var diskon = $('#diskon').val();
                var diskon_nominal = $('#diskon_nominal').val();
                var net_price = $('#net_price').text();
                $.ajax({
                    url : '{{ url("admin/po/save_data_detail") }}',
                    method : "POST",
                    data : {
                        "po_list_id":po_list_id,
                        "quote_prospect_sales_id":quote_prospect_sales_id,
                        "purchase_order_list_code":purchase_order_list_code,
                        "po_prospect_sales_id":po_prospect_sales_id,
                        "date_out":date_out,
                        "note":note,
                        "product_id":product_id,
                        "product_name":product_name,
                        "qty":qty,
                        "price":price,
                        "gross_price":gross_price,
                        "diskon":diskon,
                        "diskon_nominal":diskon_nominal,
                        "net_price":net_price,
                        _token:"{{csrf_token()}}"
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
                        // Show_data_detail();
                        // New_data_detail();
                        // Refresh_data();
                        window.location.reload();
                    },
                    error: function(){
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
                var po_list_id = "{{ $POLists->id }}";
                var quote_prospect_sales_id = "{{ $POLists->quote_prospect_sales_id }}";
                $.ajax({
                   url : '{{ url("admin/po/getproduct") }}',
                   method : "POST",
                   data : {
                        "po_list_id":po_list_id,
                        "product_id":product_id,
                        "quote_prospect_sales_id":quote_prospect_sales_id,
                        _token:"{{csrf_token()}}" },
                   dataType : "text",
                   success : function (data)
                   {
                        // console.log(data);
                        $('#detail-table-tfoot').html(data);
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
                    var diskon_nominal = $(this).val();
                    var grosscom = $('.gross_price_{{ $no }}').text();
                    var grosscom2 = grosscom.replace(",", "");
                    var grosscom3 = grosscom2.replace(",", "");
                    var grossdot = grosscom3.replace(",", "");
                    var gros = grossdot.replace(".", "");
                    var gross = gros.length;
                    var grossa = gross - 2;
                    var gross_price = gros.substr(0,grossa);
                    var diskon = (diskon_nominal / gross_price) * 100;
                    var net_price = gross_price - diskon_nominal;
                    $('.diskon_{{ $no }}').val(diskon);
                    $('.net_price_{{ $no }}').html(insertCommas(net_price+'.00' ));
                });
                
                $(document).on('click', '#btn_update_{{ $no }}', function(){
                    var id = $('#id_detail_{{ $no }}').val();
                    var purchase_order_list_code = $('#purchase_order_list_code').val();
                    var po_prospect_sales_id = $('#po_prospect_sales_id').val();
                    var date_out = $('#date_out').val();
                    var note = $('#note').val();
                    var product_id = $('#product_id_{{ $no }}').val();
                    var qty = $('.qty_{{ $no }}').val();

                    var p = $('.price_{{ $no }}').text();
                    var pr = p.replace(",", "");
                    var pri = pr.replace(",", "");
                    var pric = pri.replace(",", "");
                    var ppric = pric.replace(".", "");
                    var pprric = ppric.length;
                    var pprriic = pprric - 2;
                    var price = ppric.substr(0,pprriic);

                    var gross_price = qty * price;
                    var diskon = $('.diskon_{{ $no }}').val();
                    var diskon_nominal = $('.diskon_nominal_{{ $no }}').val();
                    var net_price = gross_price - diskon_nominal;
                

                    $.ajax({
                        url: '{{ URL("admin/po/do_btn_update") }}' + "/" + id,
                        method:"POST",
                        data:{
                            "product_id":product_id,
                            "po_list_id":{{ $POLists->id }},
                            "purchase_order_list_code":purchase_order_list_code,
                            "po_prospect_sales_id":po_prospect_sales_id,
                            "date_out":date_out,
                            "note":note,
                            "quote_prospect_sales_id":{{ $POLists->quote_prospect_sales_id }},
                            "qty":qty,
                            "gross_price":gross_price,
                            "diskon":diskon,
                            "diskon_nominal":diskon_nominal,
                            "net_price":net_price,
                            "price":price,
                            "_token":"{{csrf_token()}}"},
                        dataType:"text",
                        success:function(data){
                            // console.log(data);
                            swal({
                                position: 'center-end',
                                type: 'success',
                                title: 'Your data already updated',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            Show_data_detail();
                            New_data_detail();
                            Refresh_data();
                        },
                        error: function(){
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
                

            function edit_data(id, product_id, qty)
            {
                $.ajax({
                    url: '{{ URL("admin/po/do_update_product") }}' + "/" + id,
                    method:"POST",
                    data:{
                        "product_id":product_id,
                        "po_list_id":{{ $POLists->id }},
                        "quote_prospect_sales_id":{{ $POLists->quote_prospect_sales_id }},
                        "qty":qty,
                        "_token":"{{csrf_token()}}"},
                    dataType:"text",
                    success:function(data){
                        // console.log(data);
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
                        // console.log(data);
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
                                url: '{{URL("admin/po/delete_data_detail")}}' + "/" + id,
                                method:"POST",
                                data:{
                                    "po_list_id":{{ $POLists->id }},
                                    "quote_prospect_sales_id":{{ $POLists->quote_prospect_sales_id }},
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
                return 100; 
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
        //Datetimepicker plugin
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format : 'DD/MM/YYYY HH:mm',
            clearButton: true
        });
    </script>
@endpush