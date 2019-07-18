<html>
<head>
    <style>
        @page { margin: 10px 50px; }
        #header {left: 0px; top: -100px; margin-top: 55px;background-color: white; font-family:Arial, Helvetica, sans-serif;}
        #footer { position: fixed; left: 0px; bottom: -200px; right: 0px; height: 10px; background-color: white; }
        #footerto { margin-top:100px; margin-left: 50px;}
        #footer .page:after {content: counter(page, upper-roman); }
        #content { margin-top: 30px; font-family:Arial, Helvetica, sans-serif; font-size: 12px; }
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
                <th width="2%">#</th><th>Sales Person</th><th>Company Name</th><th>Transaction Status</th><th>Last Activity Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotelist as $no => $quote)
            <tr>
                <td>{!! ++$no !!}</td>
                <td>{!! $quote->name_sales !!}</td>
                <td>{!! $quote->company_name !!}</td>
                <td>
                    @if($quote->fix_po == 0 && $quote->fix_invoice == 0 && $quote->fix_do == 0)
                        <p>Quote already created&nbsp;-&nbsp; {{ $quote->quote_code }}&nbsp;-&nbsp;{{ date('d-F-Y H:s:i', strtotime($quote->date_quote)) }}</p>

                    @elseif($quote->fix_po == 1 && $quote->fix_invoice == 0 && $quote->fix_do == 0)

                        <p>Quote already created&nbsp;-&nbsp;{{ $quote->quote_code }}&nbsp;-&nbsp;  {{ date('d-F-Y H:s:i', strtotime($quote->date_quote)) }}</p>
                        <p>PO already created&nbsp;-&nbsp; {{  $quote->po_code }}&nbsp;-&nbsp; {{ date('d-F-Y H:s:i', strtotime($quote->date_po)) }}</p>

                    @elseif($quote->fix_po == 1 && $quote->fix_invoice == 1 && $quote->fix_do == 0)

                        <p>Quote already created&nbsp;-&nbsp;{{ $quote->quote_code }}&nbsp;-&nbsp;{{ date('d-F-Y H:s:i', strtotime($quote->date_quote)) }}</p>
                        <p>PO already created&nbsp;-&nbsp;{{ $quote->po_code }}&nbsp;-&nbsp;{{ date('d-F-Y H:s:i', strtotime($quote->date_po)) }}</p>
                        <p>Invoice already created&nbsp;-&nbsp; {{ $quote->invoice_code }}&nbsp;-&nbsp; {{ date('d-F-Y H:s:i', strtotime($quote->date_invoice)) }}</p>

                    @elseif($quote->fix_po == 1 && $quote->fix_invoice == 1 && $quote->fix_do == 1)

                        <p>Quote already created&nbsp;-&nbsp;{{ $quote->quote_code }}&nbsp;-&nbsp;{{ date('d-F-Y H:s:i', strtotime($quote->date_quote)) }}</p>
                        <p>PO already created&nbsp;-&nbsp;{{ $quote->po_code }}&nbsp;-&nbsp;{{ date('d-F-Y H:s:i', strtotime($quote->date_po)) }}</p>
                        <p>Invoice already created&nbsp;-&nbsp;{{ $quote->invoice_code }}&nbsp;-&nbsp;{{ date('d-F-Y H:s:i', strtotime($quote->date_invoice)) }}</p>
                        <p>DO already created&nbsp;-&nbsp;{{ $quote->do_code }}&nbsp;-&nbsp;{{ date('d-F-Y H:s:i', strtotime($quote->date_do)) }}</p>
                    @endif
                </td>
                <td>{!! $quote->name_progress !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
<div id="footer">
</div>
</body>
</html>