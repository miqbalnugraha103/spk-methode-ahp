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
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Invoice Name : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                <input type="text" name="invoice_list_code" id="invoice_list_code" class="form-control" value="@if(old('invoice_list_code') != ''){{ old('invoice_list_code') }}@else{{ $InvoiceLists->invoice_list_code }}@endif" placeholder="">
                                {!! $errors->first('invoice_list_code', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="col-sm-3 col-xs-4">
                            <hr>
                            <div class="form-float {{ $errors->has('invoice_code') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Number of Downpayment : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                @if($InvoiceLists->purchase_order_list_code_id != '')
                                <select name="invoice_code" id="invoice_code" class="form-control show-tick">
                                    <option value=""> -- Select --</option>
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
                            </div>
                        </div>
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
                                    <th width="5%">#</th><th width="30%">Product Name</th><th width="5%">Qty</th><th width="20%">Price (Rp.)</th><th width="15%">Gross Price (Rp.)</th><th width="5%">Disc (%)</th><th width="15%">Disc (Rp.)</th><th width="10%">Net Price</th>
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
                    <table class="table" style="width: 100%">
                        <tr>
                            <th width="20%"></th>
                            <th width="15%">Choose Tax :</th>
                            <td width="15%">
                                <select name="choose_tax" id="choose_tax" class="form-control">
                                    @if($InvoiceLists->choose_tax == 0 || $InvoiceLists->choose_tax == '')
                                        <option value="0" selected="">Don't use tax</option>
                                        <option value="1">Using tax</option>
                                    @elseif($InvoiceLists->choose_tax == 1)
                                        <option value="1" selected="">Using tax</option>
                                        <option value="0">Don't use tax</option>
                                    @endif
                                </select>
                            </td>
                            <th width="10%">Tax (%) : </th>
                            <td width="10%">
                                @if($InvoiceLists->choose_tax == 0 || $InvoiceLists->choose_tax == '')
                                    <div id="input_tax"> 
                                        <input type="text" name="tax" value="0" class="form-control" maxlength="4" style="width: 60px;" disabled="">
                                    </div>
                                @elseif($InvoiceLists->choose_tax == 1)
                                    <div id="input_tax">
                                        <input type="text" name="tax" id="tax" value="{!! $InvoiceLists->tax !!}" class="form-control" maxlength="4" style="width: 60px;">
                                    </div>
                                @endif
                            </td>
                            <th width="15%">Tax (Rp.) : </th>
                            <th width="15%">
                                <div id="tex_rupiah">{{ number_format($InvoiceLists->tax_price, 2, '.', ',') }}</div>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="5"></th>
                            <th>After Tax (Rp.) :</th>
                            <th>
                                <input type="hidden" name="after_tax" value="{!! $InvoiceLists->after_tax !!}" class="form-control">
                                <div class="after_tax">{!! number_format($InvoiceLists->after_tax,2) !!}</div>
                            </th>
                        </tr>
                    </table>
                    <!-- <div class="pull-right" style="margin-top: 10px;">
                        <b><div id="tex_rupiah">{{ number_format($list_detail->sum('net_price'), 2) }}</div></b>
                    </div>
                    <div class="pull-right" style="margin-top: 10px; width: 150px;"><b>&nbsp;&nbsp;&nbsp;&nbsp; Tax&nbsp;(Rp.) :</b>&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    <div class="pull-right">
                        @if($InvoiceLists->choose_tax == 0 || $InvoiceLists->choose_tax == '')
                            <div id="input_tax"> 
                                <input type="text" name="tax" value="0" class="form-control" maxlength="4" style="width: 60px;" disabled="">
                            </div>
                        @elseif($InvoiceLists->choose_tax == 1)
                            <div id="input_tax">
                                <input type="text" name="tax" id="tax" value="{!! $InvoiceLists->tax !!}" class="form-control" maxlength="4" style="width: 60px;">
                            </div>
                        @endif
                    </div>
                    <div class="pull-right" style="margin-top: 10px;"><b>&nbsp;&nbsp;&nbsp;&nbsp; Tax&nbsp;(%) :</b>&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    <div class="pull-right" style="width: 150px;">
                        <select name="choose_tax" id="choose_tax" class="form-control">
                            @if($InvoiceLists->choose_tax == 0 || $InvoiceLists->choose_tax == '')
                                <option value="0" selected="">Don't use tax</option>
                                <option value="1">Using tax</option>
                            @elseif($InvoiceLists->choose_tax == 1)
                                <option value="1" selected="">Using tax</option>
                                <option value="0">Don't use tax</option>
                            @endif
                        </select>
                    </div>
                    <div class="pull-right"><b>Choose Tax :</b>&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    <br>
                    <hr> -->
                    <!-- <div class="pull-right" style="margin-top: 10px;"><input type="hidden" name="after_tax" value="{!! $InvoiceLists->after_tax !!}" class="form-control"><b><div class="after_tax">{!! number_format($InvoiceLists->after_tax,2) !!}</div></b></div>
                    <div class="pull-right" style="margin-top: 10px;"><b>After Tax&nbsp;(Rp.) :</b>&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    <br>
                    <hr> -->
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
                    @if($InvoiceLists->invoice_code != '' && $InvoiceLists->purchase_order_list_code_id != '')
                    <div class="text-left"><div id="total_amount"><b>Total Outstanding Amount : Rp. {!! number_format($InvoiceLists->after_tax,2) !!}</b></div></div>
                    <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th><th width="30%">File Payment</th><th width="10%">Date Payment</th><th width="20%">Amount (Rp.)</th><th width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php $no=1 @endphp
                                    @foreach($invoicePayment as $invoice)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" form="form_edit_{{ $no }}">
                                            <input type="hidden" name="invoice_list_id" value="{{ $invoice->invoice_list_id }}" form="form_edit_{{ $no }}">
                                            <input type="hidden" value="{{ $invoice->id }}" class="id_'{{ $no }}" data-id1="{{ $invoice->id }}" form="form_edit_{{ $no }}">
                                            <input type="file" name="file_payment" value="" class="file_payment_{{ $no }}" data-id2="{{ $invoice->id }}" form="form_edit_{{ $no }}">
                                            @if(isset($invoice->file_payment) != '')
                                                <a href="{{ url('/files/invoice-payment/').'/'.$invoice->file_payment }}" download="{{ date('Y-m-d') }}" target="_blank">{{ $invoice->file_payment }}</a>

                                                <form action="{{ URL('/admin/invoice/delete_file_payment/').'/'.$invoice->id }}" id="form_file_{{ $no }}" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" form="form_file_{{ $no }}">
                                                    <input type="hidden" name="invoice_list_id" value="{{ $invoice->invoice_list_id }}" form="form_file_{{ $no }}">
                                                    <button type="submit" name="btn_file" class="btn btn-warning btn-xs"><i class="fa fa-file" aria-hidden="true"></i> Delete File</button>
                                                </form>

                                            @endif
                                        </td>
                                        <td>
                                            <input type="text" name="date_payment" data-id3="{{ $invoice->id  }}" class="datetimepicker form-control date_payment_{{ $no }}" value="{{  (isset($invoice->date_payment) ? $invoice->date_payment : "") }}" placeholder="Please choose date & time..." style="margin-top: -4px; width:160px;" form="form_edit_{{ $no }}">
                                            <p class="help-block error_date_payment_{{ $no }}" style="color: #a94442;display: none;">The date payment field is required</p>

                                        </td>
                                        <td>
                                            <input type="text" name="amount" class="form-control text-right amount_{{ $no }}" data-id4="{{ $invoice->id }}" value="{{ number_format($invoice->amount, 0) }}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" form="form_edit_{{ $no }}">

                                            <p class="help-block error_amount_{{ $no }}" style="color: #a94442;display: none;">The amount can&#39;t be 0 / null</p>
                                        </td>
                                        <td align="center">
                                            <p>

                                            <form action="{{ URL('/admin/invoice/do_btn_update/').'/'.$invoice->id  }}" method="POST" id="form_edit_{{ $no }}" enctype="multipart/form-data">
                                                <input type="button" value="Update" class="btn btn-success btn-xs" id="update_payment_{{ $no }}" data-id5="{{ $invoice->id }}">
                                                </div>
                                            </form>

                                            </p>
                                        </td>
                                    </tr>
                                    @php $no++ @endphp
                                    @endforeach
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
                        <div class="form-group text-center">
                            <input type="button" id="btn_create_all_data" value="Create Invoice All" class="btn bg-green btn-sm btn-block waves-effect" style="font-size:19px;">
                        </div>
                    </div>
                    @else
                    <h4><div style="color: red;"><i>*Please Select the Purchase Order Name & Number of Downpayment First</i></div></h4>
                        <hr>
                        <div class="form-group text-center">
                            <input type="button" disabled="disabled" value="Create Invoice All" class="btn bg-green btn-sm btn-block waves-effect" style="font-size:19px;">
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
                }
            });       
        });

        $('#purchase_order_list_code_id').on('change', function(){
            var invoice_id = "{{ $InvoiceLists->id }}";
            var purchase_order_list_code_id = $(this).val();
            var invoice_list_code = $('#invoice_list_code').val();
            var date_out = $('#date_out').val();
            var note = $('#note').val();


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
                    title: "Selecting a different Purchase Order Name will change the Data of Purchase Order (Transaction) Data bellow accordingly ",
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

        $('#choose_tax').on('change', function(){
            var choose_tax = $(this).val();
            var prospect_sales_id = $('#prospect_sales_id').val();
            var invoice_list_id = "{{ $InvoiceLists->id }}";
            var invoice_list_code = $('#invoice_list_code').val();
            var date_out = $('#date_out').val();
            var note = $('#note').val();

            $.ajax({
               url : '{{ url("admin/invoice-list/choose_tax") }}',
               method : "POST",
               data : {
                    choose_tax:choose_tax,
                    invoice_list_id:invoice_list_id,
                    prospect_sales_id:prospect_sales_id,
                    invoice_list_code:invoice_list_code,
                    date_out:date_out,
                    note:note,
                    _token:"{{csrf_token()}}"
               },
               dataType : "text",
               success : function (data)
               {
                var hasil = jQuery.parseJSON(data);
                console.log(hasil.data);
                if(hasil.data.choose_tax == 1){
                    html = '<input type="text" name="tax" id="tax" value="'+ hasil.data.tax +'" class="form-control" maxlength="4" style="width: 60px;">';
                }else{
                    html = '<input type="text" name="tax" value="0" class="form-control" maxlength="4" style="width: 60px;" disabled="">';
                }
                $('#tex_rupiah').html(insertCommas(hasil.data.tax_price+'.00' ));
                $('#input_tax').html(html);
                $('.after_tax').html(insertCommas(hasil.data.after_tax+'.00' ));
                var html = '<div id="total_amount"><b>Total Outstanding Amount : Rp. '+insertCommas(hasil.data.after_tax+'.00' )+'</b></div>';
                $('#total_amount').html(html);

                
                // window.location.reload();
               }
           });
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

        $(document).on('input', '#tax', function(){
                var tax = $(this).val();
                var invoice_list_id = '{{ $InvoiceLists->id }}';
                var prospect_sales_id = '{{ $InvoiceLists->prospect_sales_id }}';

                $.ajax({
                    url : '{{ url("admin/invoice-list/tax") }}',
                    method : "POST",
                    data : {
                        "invoice_list_id":invoice_list_id,
                        "prospect_sales_id":prospect_sales_id,
                        "tax":tax,
                        _token:"{{csrf_token()}}"
                    },
                    dataType : "text",
                    success : function (data)
                    {
                        // console.log(data.after_tax);
                        var hasil = jQuery.parseJSON(data);
                        console.log(hasil.data);

                        $('#tex_rupiah').html(insertCommas(hasil.data.tax_price+'.00' ));
                        $('.after_tax').html(insertCommas(hasil.data.after_tax+'.00' ));
                        var html = '<div id="total_amount"><b>Total Outstanding Amount : Rp. '+insertCommas(hasil.data.after_tax+'.00' )+'</b></div>';
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