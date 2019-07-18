@extends('layouts.admin.frame')

@section('title', 'Purchase Order Lists View')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/purchase-order-list') }}">Purchase Order Lists</a></li>
    <li class="active">Purchase Order List Details</li>
</ol>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">Purchase Order List Details <b>({!! $POLists->purchase_order_list_code !!})</b></div>
                <div class="panel-body">

                    <p><a href="{{ url('/admin/purchase-order-list') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></p>

                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>Quote Name</th><td>:</td>
                                    <td>
                                        @if($POLists->quote_list_code_id != '')
                                            @foreach($QuoteCode as $qc)
                                                @if($POLists->quote_list_code_id == $qc->id)
                                                    {!! $qc->quote_list_code !!}
                                                @endif
                                            @endforeach
                                        @else <b>-</b> @endif
                                    </td>
                                    <th width="15%">Quote Prospect Sales</th><td width="1%">:</td>
                                    <td>
                                        {{ $Prospect }}&nbsp;-&nbsp;{{ $salesPerson }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Purchase Order Name</th><td>:</td><td>{!! $POLists->purchase_order_list_code !!} </td>
                                    <th width="15%">Prospect Sales</th><td width="1%">:</td>
                                    <td>
                                        @foreach($getProspectSales as $ProspectSales)
                                            
                                            @if($POLists->po_prospect_sales_id == $ProspectSales->sales_person_id)
                                                {!! $ProspectSales->name_sales !!}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Purchase Order File</th><td>:</td>
                                    <td>
                                        @if(isset($POLists->file) != '')
                                            <a href="{{ url('/') }}/files/purchase-order/{{ $POLists->file }}" download="{{ $POLists->purchase_order_list_code }}" target="_blank">{{ $POLists->file }}</a>

                                        @else <b>-</b> @endif
                                    </td>
                                    <th>Purchase Order Date</th><td>:</td><td>{!! $POLists->date_out !!} </td>
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
                                    <th>Note</th>
                                    <td>:</td>
                                    <td>{{ $POLists->note }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <hr>
                        <h4 style="font-weight: 500">Quote Detail</h4>
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

                </div>
            </div>
        </div>
    </div>
</div>
@endsection