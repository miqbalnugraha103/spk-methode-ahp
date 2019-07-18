@extends('layouts.admin.frame')

@section('title', 'Purchase Order Lists Detail')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/report/quote-list') }}">Report Purchase Order Lists</a></li>
    <li class="active">Purchase Order Lists Detail</li>
</ol>

<div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">.
                                <h2>Purchase Order Lists Detail<span class="pull-right"><a href="{{ url('/admin/report/purchase-order-list') }}" class="btn bg-green waves-effect" title="Back">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td><span class="pull-right"><a href="{{ url('/admin/report/purchase-order-list-detail/'.$POLists->id.'/generate-pdf') }}" target="_blank" class="btn bg-blue-grey waves-effect" title="Back">
                                    <i class="fa fa-file" aria-hidden="true"></i> PDF</a></span></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
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
                                    <th width="15%">Purchase Order Prospect Sales</th><td width="1%">:</td>
                                    <td>
                                        @foreach($prospect_sales as $ps)
                                            @if($POLists->po_prospect_sales_id == $ps->id)
                                                {!! $ps->company_name !!} - {!! $ps->name_sales !!}
                                            @endif
                                        @endforeach
                                    </td>
                                    <th>Purchase Order Name</th><td>:</td><td>{!! $POLists->purchase_order_list_code !!} </td>
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