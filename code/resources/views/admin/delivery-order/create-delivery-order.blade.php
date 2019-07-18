@extends('layouts.admin.frame')

@section('title', 'Transaction Delivery Order Lists')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/delivery-order-list') }}">Delivery Order Lists</a></li>
    <li class="active">Delivery Order Create</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Create Delivery Order<span class="pull-right"><a href="{{ url('/admin/delivery-order-list') }}" class="btn bg-green waves-effect" title="Back">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                {!! Form::model($DOLists, [
                    'method' => 'PATCH',
                    'url' => ['/admin/delivery-order-list/do_create', $DOLists->id],
                    'class' => 'form-horizontal',
                    'id' => 'form-delivery-order',
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
                                        @if($DOLists->purchase_order_list_code_id == $qc->id)
                                            <option value="{!! $qc->id !!}"  selected="selected">{!! $qc->purchase_order_list_code !!}</option>
                                        @endif
                                    @endforeach

                                    @foreach($InvoiceCodeArray as $poa)
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
                            <h4><span class="label label-default">{{ $DOSales }}</span></h4>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <hr>
                            <div class="form-float {{ $errors->has('delivery_order_list_code') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Delivery Order Name : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                <input type="text" name="delivery_order_list_code" id="delivery_order_list_code" class="form-control" value="@if(old('delivery_order_list_code') != ''){{ old('delivery_order_list_code') }}@else{{ $DOLists->delivery_order_list_code }}@endif" placeholder="">
                                {!! $errors->first('delivery_order_list_code', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <hr>
                            <div class="form-float {{ $errors->has('date_out') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Delivery Order Date : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                <input name="date_out" type="text" id="date_out" class="datetimepicker form-control" value="{{ isset($DOLists->date_out) ? $DOLists->date_out : '' }}" placeholder="Please choose date & time..." style="margin-top: -4px;">
                                {!! $errors->first('date_out', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <hr>
                            <div class="form-float">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Invoice List Name :</label>
                                <h4><span class="label label-default">
                                    {{ $invoiceListPO }}
                                </span></h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-3 col-xs-5">
                            <hr>
                            <label class="form-label" style="font-weight: 100; color: #aaa;">Total QTY</label>&nbsp;:&nbsp;
                            <h4><span class="label label-default">{{ $list_detail->sum('qty') }}</span></h4>
                            <input type="hidden" name="qty" id="qty_sum" value="{{ $list_detail->sum('qty') }}">
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
                        <div class="col-sm-4 col-xs-12">
                            <hr>
                            <div class="form-float {{ $errors->has('files') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Delivery Order File</label>
                                {!! Form::file('files', null, ['class' => 'form-control']) !!}
                                @if(isset($DOLists->file) != '')
                                    <a href="{{ url('/') }}/files/delivery-order/{{ $DOLists->file }}" download="{{ $DOLists->delivery_order_list_code }}" target="_blank">{{ $DOLists->file }}</a>

                                @endif
                                {!! $errors->first('files', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <hr>
                            <label class="form-label" style="font-weight: 100; color: #aaa;">Total of Downpayment</label>&nbsp;:&nbsp;
                            <h4><span class="label label-default">{{ $DOLists->invoice_code }}</span></h4>
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <hr>
                            <label class="form-label" style="font-weight: 100; color: #aaa;">Total Invoice</label>&nbsp;:&nbsp;
                            <h4><span class="label label-default">{{ number_format($DOLists->total_invoice,2) }}</span></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label class="form-label" style="font-weight: 100; color: #aaa;">PIC Sales</label>&nbsp;:&nbsp;
                            <h4><span class="label label-default">{{ $DOLists->pic_sales }}</span></h4>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <hr>
                            <div class="form-float {{ $errors->has('pic_client') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">PIC Client : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                <input type="text" name="pic_client" id="pic_client" class="form-control" value="@if(old('pic_client') != ''){{ old('pic_client') }}@else{{ $DOLists->pic_client }}@endif" placeholder="">
                                {!! $errors->first('pic_client', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <hr>
                            <div class="form-float {{ $errors->has('files_pic') ? 'has-error' : ''}}">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">PIC File</label>
                                {!! Form::file('files_pic', null, ['class' => 'form-control']) !!}
                                @if(isset($DOLists->file_pic) != '')
                                    <a href="{{ url('/') }}/files/delivery-order-pic/{{ $DOLists->file_pic }}" download="{{ date('d-m-Y') }}" target="_blank">{{ $DOLists->file_pic }}</a>

                                @endif
                                {!! $errors->first('files_pic', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <hr>
                            <div class="form-float">
                                <label class="form-label" style="font-weight: 100; color: #aaa;">Note :</label>
                                <textarea name="note" id="note" class="form-control" rows="3">@if(old('note') != ''){{ old('note') }}@else{{ $DOLists->note }}@endif</textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Update', ['class' => 'btn bg-green btn-sm btn-block waves-effect', 'style' => 'font-size:19px;']) !!}
                </div>
                {!! Form::close() !!}
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
                            <h2>Delivery Order (Transaction)</h2>
                        </div>
                    </div>
                </div>
                <div class="body">
                    @if($DOLists->purchase_order_list_code_id == '0' || $DOLists->purchase_order_list_code_id == '')
                    <h4><div style="color: red;"><i>*Please Select the Purchase Order Name First</i></div></h4>
                        <hr>
                        <div class="form-group text-center">
                            <input type="button" disabled="disabled" value="Create All" class="btn bg-green btn-sm btn-block waves-effect" style="font-size:19px;">
                        </div>
                    @else
                        <div class="text-left"><b>Total Number of Product Delivery : {{ $list_detail->sum('qty') }}</b></div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th><th width="50%">Product Name</th><th width="20%">QTY</th><th width="25%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no=1 @endphp
                                    @foreach($list_transaction as $list)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}" form="my_form_{{ $no }}">
                                                <input type="hidden" name="delivery_order_list_id" value="{{ $list->delivery_order_list_id }}" form="my_form_{{ $no }}">
                                                <input type="hidden" name="product_id" class="form-control" value="{{ $list->product_id }}" form="my_form_{{ $no }}">
                                                    
                                                @foreach($productDO as $product)
                                                    @if($list->product_id == $product->product_id)
                                                        {{ $product->product_name }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td><input type="text" name="qty" value="{{ $list->qty }}" class="form-control" placeholder="input qty ..." form="my_form_{{ $no }}"></td>
                                            <td align="center">
                                                <form action="{{ URL('/admin/delivery-order-list/update_data_qty_create').'/'. $list->id  }}" method="POST" id="my_form_{{ $no }}">
                                                    <button type="submit" name="btn_update" class="btn btn-success btn-xs"><i class="fa fa-edit" aria-hidden="true"></i> Update</button>
                                                    <a onclick="deleteData({{ $list->id }})" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Delete </a>
                                                </form>
                                            </td>
                                        </tr>

                                        @php $no++ @endphp
                                    @endforeach
                                </tbody>
                                @if($list_transaction->sum('qty') != $list_detail->sum('qty'))
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" form="form_add">
                                            <input type="hidden" name="delivery_order_list_id" value="{{ $DOLists->id }}" form="form_add">
                                            <select name="product_name" class="form-control" form="form_add">
                                                <option value="">-- Choose Product --</option>
                                                @foreach($productDO as $product)
                                                    <option value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                                                @endforeach
                                            </select>

                                            {!! $errors->first('product_name', '<p class="help-block" style="color: #a94442;">:message</p>') !!}
                                        </td>
                                        <td><input type="text" name="qty_transaction" value="" class="form-control" placeholder="" form="form_add"></td>
                                        <td align="center">
                                            <form action="{{ URL('/admin/delivery-order-list/do_save_qty_create')  }}" method="POST" id="form_add">
                                                <button type="submit" name="btn_add" class="btn btn-info btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
                                            </form>
                                        </td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                        <div class="text-left">
                            <b>Total number of the remaining product to be deliverd : @if($count_item == 0)
                                    All products have been deliverd
                                @else
                                    {{ $count_item }}
                                @endif
                            </b>
                        </div>
                        <hr>
                        <div class="form-group text-center">
                            <input type="button" id="btn_create_all_data" value="Create All" class="btn bg-green btn-sm btn-block waves-effect" style="font-size:19px;">
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
            var do_id = "{{ $DOLists->id }}";
            var delivery_order_list_code = $('#delivery_order_list_code').val();
            var date_out = $('#date_out').val();
            var pic_sales = $('#pic_sales').val();
            var pic_client = $('#pic_client').val();
            var note = $('#note').val();

            $.ajax({
                url : '{{ url("admin/delivery-order-list/create_all_do") }}',
                method : "POST",
                data : {
                        "id":do_id,
                        "delivery_order_list_code":delivery_order_list_code,
                        "date_out":date_out,
                        "pic_sales":pic_sales,
                        "pic_client":pic_client,
                        "note":note,
                        _token:"{{csrf_token()}}" },
                dataType : "text",
                success : function (data)
                {
                    console.log(data);
                   if(data != '')
                   {
                        swal({
                            position: 'center-end',
                            type: 'success',
                            title: 'Delivery Order data already Created',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.location.href = "{{ URL('/admin/delivery-order-list') }}";
                   }
                }
            });
        });

        $('#purchase_order_list_code_id').on('change', function(){
            var do_id = "{{ $DOLists->id }}";
            var purchase_order_list_code_id = $(this).val();
            var delivery_order_list_code = $('#delivery_order_list_code').val();
            var date_out = $('#date_out').val();
            var pic_sales = $('#pic_sales').val();
            var pic_client = $('#pic_client').val();
            var note = $('#note').val();

            @if($DOLists->purchase_order_list_code_id == '0' || $DOLists->purchase_order_list_code_id == '')
                $.ajax({
                    url : '{{ url("admin/delivery-order-list/getPurchaseOrderCreate") }}',
                    method : "POST",
                    data : {
                        do_list_id:do_id,
                        purchase_order_list_code_id:purchase_order_list_code_id,
                        delivery_order_list_code:delivery_order_list_code,
                        date_out:date_out,
                        pic_sales:pic_sales,
                        pic_client:pic_client,
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
                                title: 'Purchase Order data already created',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            window.location.reload();
                   }
                });
            @else
                swal({
                    title: "Are you sure choice data Purchase Order ?",
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
                            url : '{{ url("admin/delivery-order-list/getPurchaseOrder") }}',
                            method : "POST",
                            data : {
                                do_list_id:do_id,
                                purchase_order_list_code_id:purchase_order_list_code_id,
                                delivery_order_list_code:delivery_order_list_code,
                                date_out:date_out,
                                pic_sales:pic_sales,
                                pic_client:pic_client,
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
                                        title: 'Purchase Order data already created',
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

        // $('#prospect_sales_id').on('change', function(){
        //     var prospect_sales_id = $(this).val();
        //     var po_list_id = "{{ $DOLists->id }}";

        //     swal({
        //         title: "Are you sure edit prospect ?",
        //         text: "If you edit, detail quote will be deleted all!",
        //         type: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: "#DD6B55",
        //         confirmButtonText: "Yes, Change it!",
        //         cancelButtonText: "No, cancel!",
        //         closeOnConfirm: false,
        //         closeOnCancel: false
        //     }, function (isConfirm) {
        //         if (isConfirm) {
        //             $.ajax({
        //                url : '{{ url("admin/po/sales") }}',
        //                method : "POST",
        //                data : {
        //                     "po_list_id":po_list_id,
        //                     "prospect_sales_id":prospect_sales_id,
        //                     "_token":"{{csrf_token()}}"
        //                },
        //                dataType : "text",
        //                success : function (data)
        //                {
        //                 // console.log(data);
        //                 window.location.reload();
        //                }
        //            });
        //         } else {
        //             swal("Cancelled", "Your data is safe :)", "error");
        //         }

        //     });
        // });

        function deleteData(id) {
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
                        type: "POST",
                        url: '{{URL("admin/delivery-order-list/delete_qty")}}' + "/" + id,
                        data: {
                                '_token' : '{{ csrf_token() }}',
                                'do_id': {{ $DOLists->id }}
                            },
                        complete: function (msg) {
                            console.log(msg);
                            window.location.reload();
                        }
                    });
                } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                }
            });
        }
        
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