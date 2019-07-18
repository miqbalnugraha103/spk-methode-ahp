<html>
<head>
    <style>
         @page { margin: 180px 50px; }
        #header { position: fixed; left: 0px; top: -150px; right: 0px;background-color: white; }
        #footer { position: fixed; left: 0px; bottom: -200px; right: 0px; height: 150px; background-color: white; }
        #footerto { margin-top: 100px; margin-left: 50px;}
        #footer .page:after {content: counter(page, upper-roman); }
        #content { margin-top: -20px;}
    </style>
    
<!-- Bootstrap Core Css -->
<link href="{{ url('/') }}/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="{{ url('/') }}/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href="{{ url('/') }}/font-awesome/css/font-awesome-fa.css" rel="stylesheet" />
</head>
<body>
<div id="header">
    <img src="{{ URL('/') }}/images/icon-ajd.jpg" width="150" style="margin-top: 10px;">
    <div style="float: right; margin-right:170px;margin-top: -30px;"><h1>PT. Adika Jaya Dwata</h1></div>
    <div style="width: 500px; margin-left:170px;margin-top: -140px;">
        <h4>Supplier kebutuhan kamar mandi dan sanitari serta menyediakan berbagai macam kebutuhan lantai (Keramik, Granit dan Vinnyl)</h4>
        <div style="margin-top:0;">Alamat : Jl. Teuku Umar 200 Denpasar 80114</div>
        <div style="">Telepon/ Fax : 0361 264410 / 246939</div>
        <div style="">Email : adikajaya@yahoo.com</div>
    </div>
</div>
<hr>
<div id="content">
    <h2>Report Quote Detail</h2>
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
    <table class="table table-bordered table-striped table-condensed">
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
                <th class="text-right" >{{ number_format($list_detail->sum('net_price'), 2) }}</th>at($list_detail->sum('net_price'), 2) }}</th>
            </tr>
        </tfoot>
    </table>
    
</div>
<div id="footer">
    <p class="page">Page </p>
</div>
</body>
</html>