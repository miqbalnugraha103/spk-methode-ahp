<html>
<head>
    <style>
        @page { margin: 10px 50px; }
        #header {left: 0px; top: -100px; margin-top: 55px;background-color: white; font-family:Arial, Helvetica, sans-serif;}
        #footer { position: fixed; left: 0px; bottom: -200px; right: 0px; height: 10px; background-color: white; }
        #footerto { margin-top:100px; margin-left: 50px;}
        /*#footer .page:after {content: counter(page, upper-roman); }*/
        #content { margin-top: 30px; font-family:Arial, Helvetica, sans-serif; font-size: 11px; }
        #term { margin-top: 20px;}
        table{
            border-collapse: collapse;
        }
        table, th, td{
            border: 1px solid black;
        }
        th{
            padding: 8px;
        }
        td{
            padding: 5px;
        }
    </style>
<body onload="window.print()">
<div id="header">
    <img src="{{ URL('/') }}/images/icon-ajd.jpg" width="90" style="margin-top: -10px; float:right;">
    <div style="margin-right:20px;margin-top: -30px; font-family: Tahoma, Geneva, sans-serif;"><h3>PT. Adika Jaya Dewata</h3></div>
    <div style="width: 400px; margin-top: -15px; font-size: 12px; ">
        <h4 style="font-weight: 600; font-family: Arial, Helvetica, sans-serif">A Distributor Company</h4>
        <div style="margin-top:-15px;">Jl. Teuku Umar No.200 Denpasar 80114 Bali, Indonesia</div>
        <div style="">T : +62 361 264 410 F : +62 361 246 939</div>
        <div style="">E : info@ajd.co.id W : www.ajd.co.id</div>
    </div>
</div>
<div id="content">                  

    <table width="100%">
        <thead>
            <tr>
                <th width="2%">#</th><th>Brand</th><th>Product</th><th>Qty</th><th>Price (Rp.)</th><th>Disc (Rp.)</th><th>Net Price (Rp.)</th><th>Create at</th>
            </tr>
        </thead>
        <tbody>
            @foreach($historybrand as $no => $brand)
                <tr>
                    <td>{!! ++$no !!}</td>
                    <td>{!! $brand->brand !!}</td>
                    <td>{!! $brand->name_product !!}</td>
                    <td align="center">{!! $brand->qty !!}</td>
                    <td align="right">{!! number_format($brand->price,2) !!}</td>
                    <td align="right">@if($brand->diskon_nominal == 0 || $brand->diskon_nominal == '') 0 @else {!! number_format($brand->diskon_nominal,2) !!} @endif</td>
                    <td align="right">{!! number_format($brand->net_price,2) !!}</td>
                    <td>{!! $brand->created_at !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
   
</div>
<div id="footer">
</div>
</body>
</html>