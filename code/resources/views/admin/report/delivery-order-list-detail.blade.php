@extends('layouts.admin.frame')

@section('title', 'Delivery Order Lists Detail')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/report/delivery-order-list') }}">Report Delivery Order Lists</a></li>
    <li class="active">Delivery Order Lists Detail</li>
</ol>

<div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">.
                                <h2>Delivery Order Lists Detail<span class="pull-right"><a href="{{ url('/admin/report/delivery-order-list') }}" class="btn bg-green waves-effect" title="Back">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td><span class="pull-right"><a href="{{ url('/admin/report/delivery-order-list-detail/'.$DOLists->id.'/generate-pdf') }}" target="_blank" class="btn bg-blue-grey waves-effect" title="Back">
                                    <i class="fa fa-file" aria-hidden="true"></i> PDF</a></span></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Purchase Order Name</th><td>:</td>
                                    <td>
                                        @if($DOLists->purchase_order_list_code_id != '')
                                            @foreach($POCode as $qc)
                                                @if($DOLists->purchase_order_list_code_id == $qc->id) 
                                                    {!! $qc->purchase_order_list_code !!}
                                                @endif
                                            @endforeach
                                        @else <b>-</b> @endif
                                    </td>
                                    <th width="15%">Purchase Order Prospect Sales</th><td width="1%">:</td>
                                    <td>{{ $Prospect }}&nbsp;-&nbsp;{{ $salesPerson }}</td>
                                </tr>
                                <tr>
                                    <th>Delivery Order File</th><td>:</td>
                                    <td>
                                    @if(isset($DOLists->file) != '')
                                            <a href="{{ url('/') }}/files/delivery-order/{{ $DOLists->file }}" download="{{ $DOLists->delivery_order_list_code }}" target="_blank">{{ $DOLists->file }}</a>

                                        @else <b>-</b> @endif
                                    </td>
                                    <th>Delivery Order Name</th><td>:</td><td>{!! $DOLists->delivery_order_list_code !!} </td>
                                </tr>
                                <tr>
                                    <th>Delivery Order Date</th><td>:</td><td>{!! $DOLists->date_out !!} </td>
                                    <th>Invoice Step #</th>
                                    <td>:</td>
                                    <td>{{ $DOLists->invoice_code }}</td>
                                </tr>
                                <tr>
                                    <th>Total QTY</th> <td>:</td> <td> {{ $list_detail->sum('qty') }} </td>
                                    <th>Total Gross Price (Rp.)</th> <td>:</td>
                                    <td>@if($list_detail->sum('price') == 0)0
                                            @else {{ number_format($list_detail->sum('price'), 2) }}
                                            @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Discount (Rp.)</th> <td>:</td>
                                    <td>@if($list_detail->sum('diskon_nominal') == 0)0
                                            @else {{ number_format($list_detail->sum('diskon_nominal'), 2) }}
                                            @endif
                                    </td>
                                    <th>Total Price (Rp.)</th> <td>:</td>
                                    <td>@if($list_detail->sum('net_price') == 0)0
                                            @else {{ number_format($list_detail->sum('net_price'), 2) }}
                                            @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Invoice (Optional)</th>
                                    <td>:</td>
                                    <td>
                                        @foreach($invoiceListPO as $ipo)
                                            @if($ipo->id == $DOLists->invoice_list_code_id)
                                                {!! $ipo->invoice_list_code !!}
                                            @endif
                                        @endforeach
                                    </td>
                                    <th>Invoice Step #</th>
                                    <td>:</td>
                                    <td>{{ $DOLists->invoice_code }}</td>
                                    
                                </tr>
                                <tr>
                                    <th>Total Invoice (Rp.)</th>
                                    <td>:</td>
                                    <td>{{ number_format($DOLists->total_invoice,2) }}</td>
                                    <th>Total Payment</th>
                                    <td>:</td>
                                    <td>{{ number_format($DOLists->total_payment,2) }}</td>
                                </tr>
                                <tr>
                                    <th>PIC Sales</th>
                                    <td>:</td>
                                    <td>{{ $DOLists->pic_sales }}</td>
                                    <th>PIC Client</th>
                                    <td>:</td>
                                    <td>{{ $DOLists->pic_client }}</td>
                                </tr>
                                <tr>
                                    <th>PIC File</th>
                                    <td>:</td>
                                    <td>
                                    @if(isset($DOLists->file_pic) != '')
                                        <a href="{{ url('/') }}/files/delivery-order-pic/{{ $DOLists->file_pic }}" download="{{ date('d-m-Y') }}" target="_blank">{{ $DOLists->file_pic }}</a>
                                    @else - @endif
                                    </td>
                                    <th>Note</th>
                                    <td>:</td>
                                    <td>{{ $DOLists->note }}</td>
                                </tr>

                            </tbody>
                        </table>
                        <hr>
                        <h4 style="font-weight: 500">Purchase Order (Transaction)</h4>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Name Product</th><th>QTY</th><th>Price (Rp.)</th><th width="15%">Gross Price (Rp.)</th><th>Disc (%)</th><th>Disc (Rp.)</th><th>Net Price (Rp.)</th>
                                    </tr>
                                    @php $no=1 @endphp
                                    @foreach($list_detail as $detail)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>
                                                @foreach($selectlist as $product)
                                                    @if($product->id == $detail->product_id)
                                                    {{ $product->name }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td align="center">{{ $detail->qty }}</td>
                                            <td align="right">{{ number_format($detail->price,2) }}</td>
                                            <td align="right">{{ number_format($detail->gross_price,2) }}</td>
                                            <td>{{ $detail->diskon }}</td>
                                            <td align="right">{{ number_format($detail->diskon_nominal,2) }}</td>
                                            <td align="right">{{ number_format($detail->net_price,2) }}</td>
                                        </tr>
                                        @php $no++ @endphp
                                    @endforeach
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th class="text-center">{{ $list_detail->sum('qty') }}</th>
                                        <th></th>
                                        <th class="text-right" >{{ number_format($list_detail->sum('gross_price'), 2) }}</th>
                                        <th></th>
                                        <th class="text-right" >{{ number_format($list_detail->sum('diskon_nominal'), 2) }}</th>
                                        <th class="text-right" >{{ number_format($list_detail->sum('net_price'), 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <hr>
                            <h4 style="font-weight: 500">Delivery Order (Transaction)</h4>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th><th width="20%">Product Name</th><th width="30%">QTY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no=1 @endphp
                                    @foreach($list_transaction as $list)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>
                                                @foreach($productDO as $product)
                                                    @if($list->product_id == $product->product_id)
                                                        {{ $product->product_name }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $list->qty }}</td>
                                        </tr>

                                        @php $no++ @endphp
                                    @endforeach
                                    
                                </tbody>
                            </table>
                        </div>
                        <hr>

                        <hr>
                    </div>
                    <div class="body">
                        <hr>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        //Datetimepicker plugin
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format : 'DD/MM/YYYY HH:mm',
            clearButton: true,
            weekStart: 1,
            minDate : new Date()
        });
    </script>
@endpush