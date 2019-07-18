@extends('layouts.admin.frame')

@section('title', 'Quote Lists Detail')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/report/quote-list') }}">Report Quote Lists</a></li>
    <li class="active">Quote Lists Detail</li>
</ol>

<div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">.
                                <h2>Quote Lists Detail<span class="pull-right"><a href="{{ url('/admin/report/quote-list') }}" class="btn bg-green waves-effect" title="Back">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td><span class="pull-right"><a href="{{ url('/admin/report/quote-list-detail/'.$quotelist->id.'/generate-pdf') }}" target="_blank" class="btn bg-blue-grey waves-effect" title="Back">
                                    <i class="fa fa-file" aria-hidden="true"></i> PDF</a></span></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
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
                                        @foreach($prospect_sales as $ps)
                                            @if($quotelist->prospect_sales_id == $ps->id)
                                                {{$ps->company_name}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <th width="15%">Prospect Sales</th><td width="1%">:</td>
                                    <td>
                                        @foreach($salesHistory as $sales)
                                        @if($quotelist->sales_person_id == $sales->sales_person_id)
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
                                    <th>Quote Date</th>
                                    <td>:</td>
                                    <td>{{ $quotelist->date_out }}</td>
                                    <th>Note</th>
                                    <td>:</td>
                                    <td>{{ $quotelist->note }}</td>
                                </tr>

                            </tbody>
                        </table>
                        <hr>
                            <h4 style="font-weight: 500">Quote Detail</h4>
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