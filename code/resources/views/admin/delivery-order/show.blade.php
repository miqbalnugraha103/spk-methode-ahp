@extends('layouts.admin.frame')

@section('title', 'Delivery Order Lists Details')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/delivery-order-list') }}">Delivery Order Lists</a></li>
    <li class="active">Delivery Order List Details</li>
</ol>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">Delivery Order List Details <b>({!! $DOLists->delivery_order_list_code !!})</b></div>
                <div class="panel-body">

                    <p><a href="{{ url('/admin/delivery-order-list') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></p>

                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>Delivery Order Name</th><td>:</td><td>{!! $DOLists->delivery_order_list_code !!}</td>
                                    <th>Purchase Order Name</th><td>:</td>
                                    <td>
                                        @foreach($POCode as $qc)
                                            @if($DOLists->purchase_order_list_code_id == $qc->id)
                                                {!! $qc->purchase_order_list_code !!}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">Purchase Order Prospect Sales</th><td width="1%">:</td>
                                    <td>{{ $Prospect }}&nbsp;-&nbsp;{{ $salesPerson }}</td>

                                    <th width="15%">Prospect Sales</th><td width="1%">:</td><td>{{ $DOSales }}</td>
                                </tr>
                                <tr>
                                    <th>Delivery Order File</th><td>:</td>
                                    <td>
                                        @if(isset($DOLists->file) != '')
                                            <a href="{{ url('/') }}/files/delivery-order/{{ $DOLists->file }}" download="{{ $DOLists->delivery_order_list_code }}" target="_blank">{{ $DOLists->file }}</a>

                                        @else <b>-</b> @endif
                                    </td>
                                    <th>Invoice List Name</th><td>:</td>
                                    <td>{{ $invoiceListPO }}</td>
                                </tr>
                                <tr>
                                    <th>Invoice X(Pemayaran)</th><td>:</td><td>{{ $DOLists->invoice_code }}</td>
                                    <th>Total Invoice (Rp.)</th><td>:</td><td>{{ number_format($DOLists->total_invoice,2) }}</td>
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
                                    <th>Total Disc (Rp.)</th> <td>:</td>
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
                                    <th>PIC Sales</th> <td>:</td>
                                    <td>{{ $DOLists->pic_sales }}</td>
                                    <th>PIC Client</th> <td>:</td>
                                    <td>{{ $DOLists->pic_client }}</td>
                                </tr>
                                <tr>
                                    <th>PIC File</th>
                                    <td>:</td>
                                    <td>@if(isset($DOLists->files_pic) != '')
                                            <a href="{{ url('/') }}/files/delivery-order-pic/{{ $DOLists->files_pic }}" download="{{ date('d-m-Y') }}" target="_blank">{{ $DOLists->files_pic }}</a>

                                        @else <b>-</b> @endif</td>
                                    <th>Delivery Order Date</th><td>:</td><td>{{ $DOLists->date_out }}</td>
                                </tr>
                                <tr>
                                    <th>Note</th>
                                    <td>:</td>
                                    <td>{{ $DOLists->note }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <hr>
                        <h4 style="font-weight: 500">Purchase Order (Transaction)</h4>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="prospect-sales-history-table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th><th>Name Product</th><th>QTY</th><th>Price (Rp.)</th><th width="15%">Gross Price (Rp.)</th><th>Disc (%)</th><th>Disc (Rp.)</th><th>Net Price (Rp.)</th>
                                </tr>
                            </thead>
                            <tbody>
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
                            </tbody>
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
                    <div class="text-left"><b>Total Pengiriman Barang : {{ $list_detail->sum('qty') }}</b></div><hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="prospect-sales-history-table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th width="5%">#</th><th width="50%">Product Name</th><th width="20%">QTY</th>
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
                    <div class="text-left">
                        <b>Total Barang Belum Terkirim: @if($count_item == 0)
                                Terkirim semua
                            @else
                                {{ $count_item }}
                            @endif
                        </b>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection