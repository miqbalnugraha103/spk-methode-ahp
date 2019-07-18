<html>
<head>
    <style>
        /* @page { margin: 180px 50px; } */
        #header {left: 0px; top: -100px;margin-top: -20px; right: 0px;background-color: white; font-family:Arial, Helvetica, sans-serif;}
        #footer { position: fixed; left: 0px; bottom: -200px; right: 0px; height: 10px; background-color: white; }
        #footerto { margin-top:100px; margin-left: 50px;}
        #footer .page:after {content: counter(page, upper-roman); }
        #content { margin-top: 30px; font-family:Arial, Helvetica, sans-serif; font-size: 11px; }
        #term { margin-top: 20px;}
        table{
            border-collapse: collapse;
        }
        table, th, td{
            border: 1px solid black;
        }
        th{
            padding: 4px;
        }
        td{
            padding: 5px;
        }
    </style>
<body>
<div id="header">
    <img src="{{ URL('/') }}/images/icon-ajd.jpg" width="90" style="margin-top: -10px; float:right;">
    <div style="margin-right:20px;margin-top: -30px; font-family: Tahoma, Geneva, sans-serif;"><h3>PT. Adika Jaya Dewata</h3></div>
    <div style="width: 400px; margin-top: -30px; font-size: 12px; ">
        <h4 style="font-weight: 600; font-family: Arial, Helvetica, sans-serif">A Distributor Company</h4>
        <div style="margin-top:-15px;">Jl. Teuku Umar No.200 Denpasar 80114 Bali, Indonesia</div>
        <div style="">T : +62 361 264 410 F : +62 361 246 939</div>
        <div style="">E : info@ajd.co.id W : www.ajd.co.id</div>
    </div>
</div>
<div id="content">
    @foreach($getTemplate as $quoteTemplate)
    @if($quoteTemplate->id == $template->quote_template_id)
    {!! str_replace("__ADDRESS__", $prospectData->company_address, str_replace("__COMPANY__", $prospectData->company_name, str_replace("__PHONE__", $prospectData->company_phone, str_replace("__PIC__", $getsales->name_sales, str_replace("__QUOTE__", $template->quote_list_code, str_replace( "__DATE__", date('d F Y'), $quoteTemplate->header)))))) !!}
    @endif
    @endforeach
    <table width="100%">
        <thead>
            <tr>
                <th width="5%" align="center">NO</th>
                <th width="30%" align="center">ITEM</th>
                <th width="10%" align="center">IMAGE</th>
                <th width="20%" align="center">HARGA</th>
                <th width="10%" align="center">QTY</th>
                <th width="25%" align="center">JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list_detail as $no=>$list)
            <tr>
                <td align="center">{{ ++$no }}</td>
                <td>{{ $list->slug }} {{ $list->description }}</td>
                <td>@if($list->product_image == '')
                        No Picture
                    @else
                        <img src="{{ url('/') }}/files/product/{{ $list->product_image }}" alt="{{ $list->product_name }}" width="120">
                    @endif
                </td>
                <td align="right"><div style="text-align: left;">Rp.</div><div style="text-align: right; margin-top: -20px;">{{ number_format($list->price, 0) }}</div></td>
                <td align="center"><div style="text-align: left;">{{ $list->qty }}</div><div style="text-align: right; margin-top: -20px;"> {{ $list->quality }}</div></td>
                <td align="right"><div style="text-align: left;">Rp.</div><div style="text-align: right; margin-top: -20px;">{{ number_format($list->gross_price, 0) }}</div></td>
            </tr>
            @endforeach
            <!-- <tr>
                <td align="center">3</td>
                <td>Keranjang</td>
                <td align="center">1</td>
                <td align="right">Rp. 2,000,000.00</td>
                <td align="right">0</td>
                <td align="right">Rp. 2,000,000.00</td>
            </tr> -->

        </tbody>
        <tfoot style="border-left:1px solid transparent;border-bottom:1px solid transparent;">
            <!-- <tr>
                <th colspan="4"></th>
                <th align="right">Rp. @if($list_detail->sum('diskon_nominal') == 0)
                                    0
                                @else
                                    {!! number_format($list_detail->sum('diskon_nominal'),2) !!}
                                @endif
                </th>
                <th align="right">Rp. @if($list_detail->sum('net_price') == 0)
                                    0
                                @else
                                    {!! number_format($list_detail->sum('net_price'),2) !!}
                                @endif</th>
            </tr> -->
            <tr>
                <td colspan="5" style="text-align: right; border:0px solid white; border-bottom: 0 solid white;">TOTAL</td>
                <td><div style="text-align: left;">Rp</div><div style="text-align: right; margin-top: -20px;">{{ number_format($list_detail->sum('gross_price'), 0) }}</div></td>
            </tr>
            <tr>
                @if(substr(number_format($total_diskon,2, '.', ''), -3) == '.00')
                    <td colspan="5" style="text-align: right; border:0px solid white; border-bottom: 0 solid white;">DISCOUNT {{ $total_diskon }} %</td>
                @else
                    <td colspan="5" style="text-align: right; border:0px solid white; border-bottom: 0 solid white;">DISCOUNT {{ number_format($total_diskon,2, '.', '') }} %</td>
                @endif
                <td><div style="text-align: left;">Rp.</div><div style="text-align: right; margin-top: -20px;">{{ number_format($list_detail->sum('diskon_nominal'), 0) }}</div></td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right; border:0px solid white; border-bottom: 0 solid white;">SUB TOTAL</td>
                <td><div style="text-align: left;">Rp.</div><div style="text-align: right; margin-top: -20px;">{{ number_format($list_detail->sum('net_price'), 0) }}</div></td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right; border:0px solid white; border-bottom: 0 solid white;">PPN ({{ $template->tax }} %)</td>
                <td><div style="text-align: left;">Rp.</div><div style="text-align: right; margin-top: -20px;">{{ number_format($template->tax_price, 0) }}</div></td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right; border:0px solid white; border-bottom: 0 solid white;">GRAND TOTAL</td>
                <td><div style="text-align: left;">Rp.</div><div style="text-align: right; margin-top: -20px;">{{ number_format($template->after_tax, 0) }}</div></td>
            </tr>
        </tfoot>
    </table>
    @foreach($getTemplate as $quoteTemplate)
    @if($quoteTemplate->id == $template->quote_template_id)
        {!! str_replace("__SALES__", $salesQuote->name_sales, $quoteTemplate->footer) !!}
    @endif
    @endforeach
</div>
<div id="term">
    @foreach($getTerm as $term)
        @if($template->term_condition_id == $term->id)
        {!! $term->content !!}
        @endif
    @endforeach
</div>
<div id="footer">
    <p class="page">Halaman </p>
</div>
</body>
</html>