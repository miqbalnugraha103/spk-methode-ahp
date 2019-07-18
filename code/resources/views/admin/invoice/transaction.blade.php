@extends('layouts.admin.frame')

@section('title', 'Edit Invoice Lists')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/quote-list') }}">Invoice List</a></li>
    <li class="active">Invoice Transaction</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Invoice<span class="pull-right"><a href="{{ url('/admin/invoice-list') }}" class="btn bg-green waves-effect" title="Back">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                {!! Form::model($InvoiceLists, [
                    'method' => 'PATCH',
                    'url' => ['/admin/invoice-list/do_transaction', $InvoiceLists->id],
                    'class' => 'form-horizontal',
                    'id' => 'form-invoice',
                    'files' => true,
                ]) !!}
                <div class="body">
                    <div class="row">
                        
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-float {{ $errors->has('purchase_order_list_code_id') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Purchase Order Name <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                <select name="purchase_order_list_code_id" id="purchase_order_list_code_id" class="form-control show-tick">
                                    <option value=""> -- Select --</option>
                                    @foreach($POCode as $qc)
                                        @if($InvoiceLists->purchase_order_list_code_id == $qc->id)
                                            <option value="{!! $qc->id !!}" selected="selected">{!! $qc->purchase_order_list_code !!}</option>
                                        @endif
                                    @endforeach
                                    
                                    @foreach($POCodeArray as $poa)
                                        <option value="{!! $poa->id !!}">{!! $poa->purchase_order_list_code !!}</option>
                                    @endforeach
                                </select>
                                {!! $errors->first('purchase_order_list_code_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <label class="form-label" style="font-weight: 100; color: #aaa;">Purchase Order Prospect Sales</label>&nbsp;:&nbsp;
                            <h4><span class="label label-default">{{ $Prospect }}&nbsp;-&nbsp;{{ $salesPerson }}</span></h4>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <label class="form-label" style="font-weight: 100; color: #aaa;">Prospect Sales</label>&nbsp;:&nbsp;
                            <h4><span class="label label-default">{{ $invoiceSales }}</span></h4>
                        </div>
                        <div class="col-sm-5 col-xs-8">
                            <hr>
                            <div class="form-float {{ $errors->has('invoice_list_code') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Invoice Name</label>
                                @if($InvoiceLists->invoice_list_code != '')
                                <h4><span class="label label-default">{{ $InvoiceLists->invoice_list_code }}</span></h4>
                                <input type="hidden" name="invoice_list_code" id="invoice_list_code" class="form-control" value="@if(old('invoice_list_code') != ''){{ old('invoice_list_code') }}@else{{ $InvoiceLists->invoice_list_code }}@endif">
                                @else
                                <input type="text" name="invoice_list_code" id="invoice_list_code" class="form-control" value="{{ old('invoice_list_code').$InvoiceLists->invoice_list_code }}" placeholder="">
                                {!! $errors->first('invoice_list_code', '<p class="help-block">:message</p>') !!}
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-4">
                            <hr>
                            <div class="form-float {{ $errors->has('invoice_code') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Number of Downpayment : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                @if($InvoiceLists->purchase_order_list_code_id != '')
                                <select name="invoice_code" id="invoice_code" class="form-control show-tick">
                                    <option value="">-- Select --</option>
                                    <option value="1" @if($InvoiceLists->invoice_code == '1') selected="selected" @endif>1</option>
                                    <option value="2" @if($InvoiceLists->invoice_code == '2') selected="selected" @endif>2</option>
                                    <option value="3" @if($InvoiceLists->invoice_code == '3') selected="selected" @endif>3</option>
                                    <option value="4" @if($InvoiceLists->invoice_code == '4') selected="selected" @endif>4</option>
                                    <option value="5" @if($InvoiceLists->invoice_code == '5') selected="selected" @endif>5</option>
                                    <option value="6" @if($InvoiceLists->invoice_code == '6') selected="selected" @endif>6</option>
                                    <option value="7" @if($InvoiceLists->invoice_code == '7') selected="selected" @endif>7</option>
                                    <option value="8" @if($InvoiceLists->invoice_code == '8') selected="selected" @endif>8</option>
                                    <option value="9" @if($InvoiceLists->invoice_code == '9') selected="selected" @endif>9</option>
                                    <option value="10" @if($InvoiceLists->invoice_code == '10') selected="selected" @endif>10</option>
                                </select>
                                {!! $errors->first('invoice_code', '<p class="help-block">:message</p>') !!}
                                 @else
                                <select name="invoice_code" id="invoice_code" class="form-control show-tick" disabled="">
                                    <option value=""> -- Select --</option>
                                </select>
                                <p><div style="color: red;"><i>*Please Select the Purchase Order Name First</i></div></p>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <hr>
                            <div class="form-float {{ $errors->has('files') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Invoice File :</label>
                                {!! Form::file('files', null, ['class' => 'form-control']) !!}
                                @if(isset($InvoiceLists->file) != '')
                                    <a href="{{ url('/') }}/files/invoice/{{ $InvoiceLists->file }}" download="{{ $InvoiceLists->invoice_list_code }}" target="_blank">{{ $InvoiceLists->file }}</a>

                                @endif
                                {!! $errors->first('files', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-3 col-xs-5">
                            <hr>
                            <label class="form-label" style="font-weight: 100; color: #aaa;">Total QTY</label>&nbsp;:&nbsp;
                            <h4><span class="label label-default">{{ $list_detail->sum('qty') }}</span></h4>
                        </div>
                        <div class="col-sm-3 col-xs-7">
                            <hr>
                            <label class="form-label" style="font-weight: 100; color: #aaa;">Total Gross Price</label>&nbsp;:&nbsp;
                            <h4><span class="label label-default">Rp. {{ number_format($list_detail->sum('gross_price'), 2) }}</span></h4>
                        </div>
                        <div class="col-sm-3 col-xs-5">
                            <hr>
                            <label class="form-label" style="font-weight: 100; color: #aaa;">Total Discount</label>&nbsp;:&nbsp;
                            <h4><span class="label label-default">Rp. {{ number_format($list_detail->sum('diskon_nominal'), 2) }}</span></h4>
                        </div>
                        <div class="col-sm-3 col-xs-7">
                            <hr>
                            <label class="form-label" style="font-weight: 100; color: #aaa;">Total Price</label>&nbsp;:&nbsp;
                            <h4><span class="label label-default">Rp. {{ number_format($list_detail->sum('net_price'), 2) }}</span></h4>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <hr>
                            <div class="form-float {{ $errors->has('date_out') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Invoice Date : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                <input name="date_out" type="text" id="date_out" class="datetimepicker form-control" value="{{ isset($InvoiceLists->date_out) ? $InvoiceLists->date_out : '' }}" placeholder="Please choose date & time..." style="margin-top: -4px;">
                                {!! $errors->first('date_out', '<p class="help-block">:message</p>') !!}
                            </div>                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <hr>
                            <div class="form-float">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Note :</label>
                                <textarea name="note" id="note" class="form-control" rows="3">@if(old('note') != ''){{ old('note') }}@else{{ $InvoiceLists->note }}@endif</textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <input type="hidden" name="prospect_sales_id" id="prospect_sales_id" value="{{ $InvoiceLists->prospect_sales_id }}">
                    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Update', ['class' => 'btn bg-green btn-sm btn-block waves-effect', 'id' => 'btn_update', 'style' => 'font-size:19px;']) !!}

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Purchase Order (Transaction)
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">#</th><th width="30%">Name Product</th><th width="5%">Qty</th><th width="20%">Price (Rp.)</th><th width="15%">Gross Price (Rp.)</th><th width="5%">Disc (%)</th><th width="15%">Disc (Rp.)</th><th width="10%">Net Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=1 @endphp
                                @foreach($list_detail as $detail)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $detail->product_name }}</td>
                                        <td>{{ $detail->qty }}</td>
                                        <td>{{ number_format($detail->price, 2) }}</td>
                                        <td>{{ number_format($detail->gross_price, 2) }}</td>
                                        <td>{{ $detail->diskon }}</td>
                                        <td>{{ $detail->diskon_nominal }}</td>
                                        <td>{{ number_format($detail->net_price, 2) }}</td>
                                    </tr>
                                    @php $no++ @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                              <th></th>
                              <th class="text-right"></th>
                              <th>{{ $list_detail->sum('qty') }}</th>
                              <th></th>
                              <th>{{ number_format($list_detail->sum('gross_price'), 2) }}</th>
                              <th></th>
                              <th>{{ number_format($list_detail->sum('diskon_nominal'), 2) }}</th>
                              <th>{{ number_format($list_detail->sum('net_price'), 2) }}</th>
                            </tr>
                          </tfoot>
                        </table>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Invoice Payment</h2>
                        </div>
                    </div>
                </div>
                <div class="body">
                    @if($InvoiceLists->invoice_code != '')
                    <div class="text-left"><b>Total Outstanding Amount : Rp. {{ number_format($list_detail->sum('net_price'), 2) }}</b></div>
                <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th><th width="30%"></th><th width="10%">Date Payment</th><th width="20%">Amount (Rp.)</th><th width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody id="payment-table">
                                </tbody>
                            </table>
                        </div>
                    <hr>
                    <div class="text-left">
                        <b>Total Debt/ Total Credit :
                        @if($RestBill == 0)
                            Paid
                        @else
                            Rp. {{ number_format($RestBill, 2) }}
                        @endif
                        </b>
                    </div>
                    <hr>
                    <div class="form-group text-center">
                        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Update Invoice All', ['class' => 'btn bg-green btn-sm btn-block waves-effect', 'id' => 'btn_create_all_data', 'style' => 'font-size:19px;']) !!}
                    </div>
                    @else
                    <h4><div style="color: red;"><i>*Please Select the Purchase Order Name & Number of Downpayment First</i></div></h4>
                        <hr>
                        <div class="form-group text-center">
                            <input type="button" disabled="disabled" value="Update Invoice All" class="btn bg-green btn-sm btn-block waves-effect" style="font-size:19px;">
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

        $('#btn_create_all_data').on('click',function() {
            var invoice_id = "{{ $InvoiceLists->id }}";
            var invoice_list_code = $('#invoice_list_code').val();
            var date_out = $('#date_out').val();
            var note = $('#note').val();

            $.ajax({
                url : '{{ url("admin/invoice/create_all_invoice_transaction") }}',
                method : "POST",
                data : {
                        "id":invoice_id,
                        "invoice_list_code":invoice_list_code,
                        "date_out":date_out,
                        "note":note,
                        _token:"{{csrf_token()}}" },
                dataType : "text",
                success : function (data)
                {
                    // console.log(data);
                   if(data != '')
                   {
                        swal({
                            position: 'center-end',
                            type: 'success',
                            title: 'Data Invoice already Updated',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.location.reload();
                   }
                }
            });       
        });

        $('#purchase_order_list_code_id').on('change', function(){
            var invoice_id = "{{ $InvoiceLists->id }}";
            var purchase_order_list_code_id = $(this).val();
            var invoice_list_code = $('#invoice_list_code').val();
            var date_out = $('#date_out').val();
            var note = $('#note').val();

            swal({
                title: "Selecting a different Purchase Order Name will change the Data of Purchase Order (Transaction) Data bellow accordingly",
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
                        url : '{{ url("admin/invoice-list/getPurchaseOrder") }}',
                        method : "POST",
                        data : {
                            "invoice_list_id":invoice_id,
                            "purchase_order_list_code_id":purchase_order_list_code_id,
                            "invoice_list_code":invoice_list_code,
                            "date_out":date_out,
                            "note":note,
                            _token:"{{csrf_token()}}"
                        },
                        dataType : "text",
                        success : function (data)
                        {
                            console.log(data);
                            swal({
                                    position: 'center-end',
                                    type: 'success',
                                    title: 'Purchase Order is changed successfully',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                window.location.reload();
                       }
                    });
                } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                    window.location.reload();
                }

            });
        });

        $('#invoice_code').on('change', function(){
            var invoice_id = "{{ $InvoiceLists->id }}";
            var invoice_code = $(this).val();
            var prospect_sales_id =  $('#prospect_sales_id').val();
            var invoice_list_code = $('#invoice_list_code').val();
            var date_out = $('#date_out').val();
            var note = $('#note').val();

            @if($InvoiceLists->invoice_code != '')
            swal({
                title: "All data in The Invoice Payment Section bellow will be removed. Please confirm ",
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
                        url : '{{ url("admin/invoice-list/create_payment") }}',
                        method : "POST",
                        data : {
                            "invoice_list_id":invoice_id,
                            "invoice_code":invoice_code,
                            "prospect_sales_id":prospect_sales_id,
                            "invoice_list_code":invoice_list_code,
                            "date_out":date_out,
                            "note":note,
                            _token:"{{csrf_token()}}"
                        },
                        dataType : "text",
                        success : function (data)
                        {
                            console.log(data);
                            swal({
                                    position: 'center-end',
                                    type: 'success',
                                    title: 'Data Invoice Payment is changed successfully',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                window.location.reload();
                       }
                    });
                } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                    window.location.reload();
                }

            });
            @else
                $.ajax({
                    url : '{{ url("admin/invoice-list/create_payment") }}',
                    method : "POST",
                    data : {
                        "invoice_list_id":invoice_id,
                        "invoice_code":invoice_code,
                        "prospect_sales_id":prospect_sales_id,
                        "invoice_list_code":invoice_list_code,
                        "date_out":date_out,
                        "note":note,
                        _token:"{{csrf_token()}}"
                    },
                    dataType : "text",
                    success : function (data)
                    {
                        // console.log(data);
                        swal({
                                position: 'center-end',
                                type: 'success',
                                title: 'Creating a new Data Invoice Payment successfully',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            window.location.reload();
                   }
                });
            @endif
        });

        $('#prospect_sales_id').on('change', function(){
            var prospect_sales_id = $(this).val();
            var po_list_id = "{{ $InvoiceLists->id }}";

            swal({
                title: "Are you sure edit prospect ?",
                text: "If you edit, detail quote will be deleted all!",
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
                       url : '{{ url("admin/po/sales") }}',
                       method : "POST",
                       data : {
                            "po_list_id":po_list_id,
                            "prospect_sales_id":prospect_sales_id,
                            "_token":"{{csrf_token()}}"
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
                }

            });
        });

        $(document).ready(function(){
            function Show_data_detail() {
                var po_list_id = "{{ $InvoiceLists->purchase_order_list_code_id }}";
                var invoice_list_id = "{{ $InvoiceLists->id }}";
                var prospect_sales_id = "{{ $InvoiceLists->prospect_sales_id }}";
                $.ajax({
                    url : '{{ url("admin/invoice/get_data_payment") }}',
                    method : "POST",
                    data : {
                            "po_list_id":po_list_id,
                            "invoice_list_id":invoice_list_id,
                            "prospect_sales_id":prospect_sales_id,
                            _token:"{{csrf_token()}}" },
                    dataType : "text",
                    success : function (data)
                    {
                        // console.log(data);
                       if(data != '')
                       {
                            $('#payment-table').html(data);
                            $('.datetimepicker').bootstrapMaterialDatePicker({
                                format : 'DD/MM/YYYY',
                                weekStart : 0,
                                time: false,
                                minDate : new Date()
                            }); 
                       }
                    }
                });
            }
            Show_data_detail();
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

</script>
<script type="text/javascript">
    function tandaPemisahTitik(b){
        var _minus = false;
        if (b<0) _minus = true;
        b = b.toString();
        b=b.replace(",","");
        b=b.replace("-","");
        c = "";
        panjang = b.length;
        j = 0;
        for (i = panjang; i > 0; i--){
            j = j + 1;
            if (((j % 3) == 1) && (j != 1)){
                c = b.substr(i-1,1) + "," + c;
            } else {
                c = b.substr(i-1,1) + c;
            }
        }
        if (_minus) c = "-" + c ;
        return c;
    }

    function numbersonly(ini, e){
        if (e.keyCode>=49){
            if(e.keyCode<=57){
                a = ini.value.toString().replace(",","");
                b = a.replace(/[^\d]/g,"");
                b = (b=="0")?String.fromCharCode(e.keyCode):b + String.fromCharCode(e.keyCode);
                ini.value = tandaPemisahTitik(b);
                return false;
            }
            else if(e.keyCode<=105){
                if(e.keyCode>=96){
                    //e.keycode = e.keycode - 47;
                    a = ini.value.toString().replace(",","");
                    b = a.replace(/[^\d]/g,"");
                    b = (b=="0")?String.fromCharCode(e.keyCode-48):b + String.fromCharCode(e.keyCode-48);
                    ini.value = tandaPemisahTitik(b);
                    //alert(e.keycode);
                    return false;
                }
                else {return false;}
            }
            else {
            return false; }
        }else if (e.keyCode==48){
            a = ini.value.replace(",","") + String.fromCharCode(e.keyCode);
            b = a.replace(/[^\d]/g,"");
            if (parseFloat(b)!=0){
                ini.value = tandaPemisahTitik(b);
                return false;
            } else {
                return false;
            }
        }else if (e.keyCode==95){
            a = ini.value.replace(",","") + String.fromCharCode(e.keyCode-48);
            b = a.replace(/[^\d]/g,"");
            if (parseFloat(b)!=0){
                ini.value = tandaPemisahTitik(b);
                return false;
            } else {
                return false;
            }
        }else if (e.keyCode==8 || e.keycode==46){
            a = ini.value.replace(",","");
            b = a.replace(/[^\d]/g,"");
            b = b.substr(0,b.length -1);
            if (tandaPemisahTitik(b)!=""){
                ini.value = tandaPemisahTitik(b);
            } else {
                ini.value = "";
            }

            return false;
        } else if (e.keyCode==9){
            return true;
        } else if (e.keyCode==17){
            return true;
        } else if (e.keyCode==37){
            return true;
        } else if (e.keyCode==39){
            return true;
        } else {
            //alert (e.keyCode);
            return false;
        }

    }
</script>
<script>
    //Datetimepicker plugin
    $('.datetimepicker').bootstrapMaterialDatePicker({
        format : 'DD/MM/YYYY',
        weekStart : 0,
        time: false,
        clearButton: true
    });

    $('.datepickerstart').bootstrapMaterialDatePicker({
        format : 'DD/MM/YYYY HH:mm',
        weekStart : 0,
        time: false,
        minDate : new Date()
    });
</script>
@endpush