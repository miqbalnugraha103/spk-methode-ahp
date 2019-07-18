@extends('layouts.admin.frame')

@section('title', 'Edit Invoice Lists')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/quote-list') }}">Invoice List</a></li>
    <li class="active">Create Invoice</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Create Invoice<span class="pull-right"><a href="{{ url('/admin/invoice-list') }}" class="btn bg-green waves-effect" title="Back">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                {!! Form::model($InvoiceLists, [
                    'method' => 'PATCH',
                    'url' => ['/admin/invoice-list/do_create', $InvoiceLists->id],
                    'class' => 'form-horizontal',
                    'id' => 'form-invoice',
                    'files' => true,
                ]) !!}
                <div class="body">
                    <div class="row">
                        
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-float {{ $errors->has('purchase_order_list_code_id') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Purchase Order Name : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                <select name="purchase_order_list_code_id" id="purchase_order_list_code_id" class="form-control show-tick">
                                    <option value=""> -- Select --</option>
                                    @foreach($POCode as $qc)
                                            <option value="{!! $qc->id !!}"@if($InvoiceLists->purchase_order_list_code_id == $qc->id) selected="selected" @endif>{!! $qc->purchase_order_list_code !!}</option>
                                    @endforeach
                                    
                                    <!-- @foreach($POCodeArray as $poa)
                                        <option value="{!! $poa->id !!}">{!! $poa->purchase_order_list_code !!}</option>
                                    @endforeach -->
                                </select>
                                {!! $errors->first('purchase_order_list_code_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <!-- <div class="col-sm-4 col-xs-12">
                            <label class="form-label" style="font-weight: 100; color: #aaa;">Purchase Order Prospect Sales</label>&nbsp;:&nbsp;
                            <h4><span class="label label-default">{{ $Prospect }}&nbsp;-&nbsp;{{ $salesPerson }}</span></h4>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <label class="form-label" style="font-weight: 100; color: #aaa;">Prospect Sales</label>&nbsp;:&nbsp;
                            <h4><span class="label label-default">{{ $invoiceSales }}</span></h4>
                        </div> -->
                        
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-float {{ $errors->has('invoice_list_code') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Invoice Name : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                <input type="text" name="invoice_list_code" id="invoice_list_code" class="form-control" value="@if(old('invoice_list_code') != ''){{ old('invoice_list_code') }}@else{{ $InvoiceLists->invoice_list_code }}@endif" placeholder="">
                                {!! $errors->first('invoice_list_code', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
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
                    
<!--                     <div class="row">
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
                    </div> -->
                    <hr>
                    <input type="hidden" name="prospect_sales_id" id="prospect_sales_id" value="{{ $InvoiceLists->prospect_sales_id }}">
                    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create Invoice All', ['class' => 'btn bg-green btn-sm btn-block waves-effect', 'id' => 'btn_update', 'style' => 'font-size:19px;']) !!}

                    {!! Form::close() !!}
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
            var date_out = '';
            var note = '';

            $.ajax({
                url : '{{ url("admin/invoice/create_all_invoice") }}',
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
                            title: 'Creating a new Data Invoice successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.location.href = "{{ URL('/admin/invoice-list') }}";
                   }
                },error: function(data){
                    swal({
                        position: 'center-end',
                        type: 'warning',
                        title: 'The field is required',
                        showConfirmButton: false,
                        timer: 1500
                    });
                  }
            });       
        });

        $('#purchase_order_list_code_id').on('change', function(){
            var invoice_id = "{{ $InvoiceLists->id }}";
            var purchase_order_list_code_id = $(this).val();
            var invoice_list_code = $('#invoice_list_code').val();
            var date_out = '';
            var note = '';


            @if($InvoiceLists->purchase_order_list_code_id == '0' || $InvoiceLists->purchase_order_list_code_id == '')
                $.ajax({
                    url : '{{ url("admin/invoice-list/getPurchaseOrderCreate") }}',
                    method : "POST",
                    data : {
                        "invoice_list_id":invoice_id,
                        "purchase_order_list_code_id":purchase_order_list_code_id,
                        "invoice_list_code":invoice_list_code,
                        "date_out":date_out,
                        "note":note,
                        _token:"{{csrf_token()}}"
                    },
                    success : function (data)
                    {
                        console.log(data);
                        swal({
                                position: 'center-end',
                                type: 'success',
                                title: 'Creating a new Purchase Order successfully',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            window.location.reload();
                   }
                });
            @else
                swal({
                    title: "Attention !",
                    text: "Selecting a different Purchase Order Name will change the Data of Purchase Order Details",
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
            @endif
        });

        $('#invoice_code').on('change', function(){
            var invoice_id = "{{ $InvoiceLists->id }}";
            var invoice_code = $(this).val();
            var prospect_sales_id =  $('#prospect_sales_id').val();
            var invoice_list_code = $('#invoice_list_code').val();
            var date_out = '';
            var note = '';

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
                                // console.log(data);
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

        $(function(){
        @php $no=1 @endphp
        @foreach($invoicePayment as $invoice)
        
            $('#update_payment_{{ $no }}').on('click',function(){
                // console.log('jsj');
                $('#update_payment_{{ $no }}').val('Processing..');

                $(".error_date_payment_{{ $no }}").css("display","none");
                $(".error_amount_{{ $no }}").css("display","none");

                var date_payment = $(".date_payment_{{ $no }}").val();
                var amount = $(".amount_{{ $no }}").val();
                var error_flag = 0;

                if (date_payment.trim() == ""){
                    $(".error_date_payment_{{ $no }}").css("display","inline-block");
                    error_flag = 1;
                }
                if (amount.trim() == "" || amount.trim() == "0"){
                    $(".error_amount_{{ $no }}").css("display","inline-block");
                    error_flag = 1;
                }

                if (error_flag == 0){
                    $('#form_edit_{{ $no }}').submit();
                }else{
                    if (error_flag == 1){
                        $(".update_errorinfo").text('Please complete the required field');
                        $('#update_payment_{{ $no }}').val('Update');
                    }
                }
            });

        @php $no++ @endphp
        @endforeach
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
        format : 'DD-MM-YYYY HH:mm',
        weekStart : 1,
        clearButton: true
    });

    $('.datetimepickerpayment').bootstrapMaterialDatePicker({
        format : 'DD-MM-YYYY',
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