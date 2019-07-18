@extends('layouts.admin.frame')

@section('title', 'Quote Lists Detail')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/quote-list') }}">Quote Lists</a></li>
    <li class="active">Quote Lists Details</li>
</ol>
<div class="container">
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">Quote Lists Details <b>({!! $quotelist->quote_list_code !!})</b></div>
                <div class="panel-body">

                    <p><a href="{{ url('/admin/quote-list') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></p>

                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>Quote Name</th><td>:</td><td> {!! $quotelist->quote_list_code !!} </td>
                                    <th>Requote Name</th><td>:</td>
                                    <td>
                                        @if($quotelist->requote_list_code != '')
                                            @foreach($quotecode as $quote)
                                                @if($quotelist->requote_list_code == $quote->id)
                                                    {!! $quote->quote_list_code !!}
                                                @endif
                                            @endforeach
                                        @else <b>-</b> @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">Prospect Company</th><td width="1%">:</td>
                                    <td>
                                        {{$prospectData->company_name}}
                                    </td>
                                    <th width="15%">Prospect Sales</th><td width="1%">:</td>
                                    <td>
                                        @foreach($salesHistory as $sales)
                                            @if($quotelist->sales_person_id == $sales->user_id)
                                                {!! $sales->name_sales !!}
                                            @endif
                                        @endforeach
                                    </td>
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
                                    <th>PPN (%)</th> <td>:</td>
                                    <td>@if($quotelist->tax == 0)0
                                            @else {{ $quotelist->tax }}
                                        @endif
                                    </td>
                                    <th>PPN (Rp.)</th> <td>:</td>
                                    <td>@if($quotelist->tax_price == 0)0
                                            @else  {{ number_format($quotelist->tax_price, 2) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Quote Date</th>
                                    <td>:</td>
                                    <td>{{ $quotelist->date_out }}</td>
                                    <th>After PPN (Rp.)</th> <td>:</td>
                                    <td>@if($quotelist->after_tax == 0)0
                                            @else {{ number_format($quotelist->after_tax, 2) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Note</th>
                                    <td>:</td>
                                    <td colspan="4">{{ $quotelist->note }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <hr>
                        <h4 style="font-weight: 500">Quote Detail</h4>
                    <hr>
                    <div class="table-responsive">
                        @foreach($getTemplate as $quoteTemplate)
                            @if($quoteTemplate->id == $quotelist->quote_template_id)
                                {!! $quoteTemplate->header !!}
                            @endif
                        @endforeach
                        <table class="table table-bordered table-striped table-hover" id="prospect-sales-history-table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th align="center" width="5%">No</th>
                                    <th align="center" width="20%">ITEM</th>
                                    <th align="center" width="10%">GAMBAR</th>
                                    <th align="center" width="10%">HARGA (Rp.)</th>
                                    <th align="center" width="5%">QTY</th>
                                    {{--<th align="center" width="15%">Harga Kotor (Rp.)</th>--}}
                                    <th align="center" width="15%">JUMLAH (Rp.)</th>
                                    {{--<th align="center" width="5%">Diskon (%)</th>--}}
                                    {{--<th align="center" width="15%">Diskon (Rp.)</th>--}}
                                    {{--<th align="center" width="15%">Harga Bersih (Rp.)</th>--}}
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=1 @endphp
                                @foreach($list_detail as $detail)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $detail->product_name }}</td>
                                        <td>
                                            @if($detail->product_image == '')
                                                No Picture
                                            @else
                                                <img src="{{ url('/') }}/files/product/{{ $detail->product_image }}" alt="{{ $detail->product_image }}" width="120">
                                            @endif
                                        </td>
                                        <td align="right">{{ number_format($detail->price,0) }}</td>
                                        <td align="center"><div style="text-align: left;">{{ $detail->qty }}</div><div style="text-align: right; margin-top: -20px;"> {{ $detail->quality }}</div></td>
                                        <td align="right">{{ number_format($detail->gross_price,0) }}</td>
                                        {{--<td>{{ $detail->diskon }}</td>--}}
                                        {{--<td align="right">{{ number_format($detail->diskon_nominal,0) }}</td>--}}
                                        {{--<td align="right">{{ number_format($detail->net_price,0) }}</td>--}}
                                    </tr>
                                    @php $no++ @endphp
                                @endforeach
                            </tbody>
                            <tfoot style="border-left:1px solid transparent;border-bottom:1px solid transparent;">
                                <tr>
                                    {{--<th colspan="5" class="text-right" style="border:0px solid white; border-bottom: 0 solid white;">TOTAL HARGA KOTOR</th>--}}
                                    <th colspan="5" class="text-right" style="border:0px solid white; border-bottom: 0 solid white;">TOTAL</th>
                                    <td><div class="text-left">Rp</div><div class="text-right" style="margin-top: -20px;">{{ number_format($list_detail->sum('gross_price'), 0) }}</div></td>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right" style="border:0px solid white; border-bottom: 0 solid white;">
                                        @if(substr(number_format($total_diskon,2, '.', ''), -3) == '.00')
                                            DISCOUNT {{ $total_diskon }} %
                                        @else
                                            DISCOUNT {{ number_format($total_diskon,2, '.', '') }} %
                                        @endif</th>
                                    <td><div class="text-left">Rp</div><div class="text-right" style="margin-top: -20px;">{{ number_format($list_detail->sum('diskon_nominal'), 0) }}</div></td>
                                </tr>
                                <tr>
                                    {{--<th colspan="5" class="text-right" style="border:0px solid white; border-bottom: 0 solid white;">TOTAL HARGA BERSIH</th>--}}
                                    <th colspan="5" class="text-right" style="border:0px solid white; border-bottom: 0 solid white;">SUB TOTAL</th>
                                    <td><div class="text-left">Rp</div><div class="text-right" style="margin-top: -20px;">{{ number_format($list_detail->sum('net_price'), 0) }}</div></td>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right" style="border:0px solid white; border-bottom: 0 solid white;">PPN ({{ $quotelist->tax }} %)</th>
                                    <td><div class="text-left">Rp</div><div class="text-right" style="margin-top: -20px;">{{ number_format($quotelist->tax_price, 0) }}</div></td>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right" style="border:0px solid white; border-bottom: 0 solid white;">GRAND TOTAL</th>
                                    <td><div class="text-left">Rp</div><div class="text-right" style="margin-top: -20px;">{{ number_format($quotelist->after_tax, 0) }}</div></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @foreach($getTemplate as $quoteTemplate)
                        @if($quoteTemplate->id == $quotelist->quote_template_id)
                            {!! $quoteTemplate->footer !!}
                        @endif
                    @endforeach

                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                @foreach($getTerm as $term)
                                    @if($term->id == $quotelist->term_condition_id)
                                    <tr>
                                        <td colspan="3">
                                            {!! $term->content !!}
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection