<html>
<head>
    <style>
        @page { margin: 180px 50px; }
        #header { position: fixed; left: 0px; top: -180px; right: 0px;background-color: white; }
        #footer { position: fixed; left: 0px; bottom: -150px; right: 0px; height: 150px; background-color: white; }
        #footerto { margin-top: 540px;}
        #footer .page:after { content: counter(page, upper-roman); }
        #content { margin-top: -80px;}
    </style>
    
<!-- Bootstrap Core Css -->
<link href="{{ url('/') }}/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="{{ url('/') }}/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href="{{ url('/') }}/font-awesome/css/font-awesome-fa.css" rel="stylesheet" />
</head>
<body>
<div id="header">
    <p><h3>Report Invoice Detail</h3></p>
    <p>{!! Date('Y/m/d') !!}</p>
    
    <hr>
</div>
    
<div id="content">
    <table class="table table-borderless">
        <tbody>
            <tr>
                <th>Purchase Order Name</th><td>:</td>
                <td>
                    @if($InvoiceLists->purchase_order_list_code_id != '')
                        @foreach($POCode as $qc)
                            @if($InvoiceLists->purchase_order_list_code_id == $qc->id) 
                                {!! $qc->purchase_order_list_code !!}
                            @endif
                        @endforeach
                    @else <b>-</b> @endif
                </td>
                <th width="15%">Purchase Order Prospect Sales</th><td width="1%">:</td>
                <td>{{ $Prospect }}&nbsp;-&nbsp;{{ $salesPerson }}</td>
            </tr>
            <tr>
                <th>Invoice File</th><td>:</td>
                <td>
                @if(isset($InvoiceLists->file) != '')
                        <a href="{{ url('/') }}/files/invoice/{{ $InvoiceLists->file }}" download="{{ $InvoiceLists->invoice_list_code }}" target="_blank">{{ $InvoiceLists->file }}</a>

                    @else <b>-</b> @endif
                </td>
                <th>Invoice Name</th><td>:</td><td>{!! $InvoiceLists->invoice_list_code !!} </td>
            </tr>
            <tr>    
                <th>Invoice Step #</th>
                <td>:</td>
                <td>{{ $InvoiceLists->invoice_code }}</td>
                <th>Invoice Date</th><td>:</td><td>{!! $InvoiceLists->date_out !!} </td>
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
                <th>Invoice Amount (Rp.)</th>
                <td>:</td>
                <td>{{ number_format($InvoiceLists->amount_payment, 2) }}</td>
                <th>Total All Invoice Amount (Rp.)</th>
                <td>:</td>
                <td>{{ number_format($invoiceByPO->sum('amount_payment'), 2) }}</td>
            </tr>
            <tr>
                <th>Total UnInvoice (Rp.)</th>
                <td>:</td>
                <td>{{ number_format($list_detail->sum('net_price') - $invoiceByPO->sum('amount_payment'), 2) }}</td>
                <th>Note</th>
                <td>:</td>
                <td>{{ $InvoiceLists->note }}</td>
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
        <h4 style="font-weight: 500">Invoice Payment</h4>
    <hr>
    <div class="text-left"><b>Total Invoice (Rp.) : {{ number_format($InvoiceLists->amount_payment, 2) }}</b></div>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th width="5%">#</th><th width="20%">File</th><th width="30%">Date Payment</th><th width="45%">Amount (Rp.)</th>
                </tr>
            </thead>
            <tbody>
                @php $no=1 @endphp
                @foreach ($data_payment as $payment)
                    <tr>
                        <td>{{ $no }}</td>
                        <td>
                            @if(isset($payment->file_payment) != '')
                                <a href="{{ url('/').'/files/invoice-payment/'.$payment->file_payment  }}" download="{{ date('Y-m-d')  }}" target="_blank">{{ $payment->file_payment }}</a>
                            @else - @endif
                        </td>
                        <td>{{ $payment->date_payment }}</td>
                        <td class="text-right">{{ number_format($payment->amount, 2) }}</td>
                        @php $no++ @endphp
                    </tr>
                @endforeach
            </tbody>
            <tfoot id="payment-table-tfoot">
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <th class="text-right">Total Payment (Rp.) : {{ number_format($data_detail->sum('amount'),2) }}</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <hr>
        <div class="text-left"><b>Total Debt (Rp.): {{ number_format($RestBill, 2) }}</b></div>
    <hr>

    
</div>
<div id="footer">
    <p class="page">Page </p>
</div>
</body>
</html>