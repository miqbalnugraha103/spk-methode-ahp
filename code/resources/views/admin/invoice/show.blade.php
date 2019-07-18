@extends('layouts.admin.frame')

@section('title', 'Invoice Lists Details')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/invoice-list') }}">Invoice Lists</a></li>
    <li class="active">Invoice List Details</li>
</ol>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">Invoice List Details <b>({!! $InvoiceLists->invoice_list_code !!})</b></div>
                <div class="panel-body">

                    <p><a href="{{ url('/admin/invoice-list') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></p>

                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>Invoice Name</th><td>:</td><td>{!! $InvoiceLists->invoice_list_code !!}</td>
                                    <th>Purchase Order Name</th><td>:</td>
                                    <td>
                                        @foreach($POCode as $qc)
                                            @if($InvoiceLists->purchase_order_list_code_id == $qc->id)
                                                {!! $qc->purchase_order_list_code !!}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">Purchase Order Prospect Sales</th><td width="1%">:</td>
                                    <td>{{ $Prospect }}&nbsp;-&nbsp;{{ $salesPerson }}</td>
                                    <th width="15%">Prospect Sales</th><td width="1%">:</td><td>{{ $invoiceSales }}</td>
                                </tr>
                                <tr>
                                    <th>Invoice File</th><td>:</td>
                                    <td>
                                        @if(isset($InvoiceLists->file) != '')
                                            <a href="{{ url('/') }}/files/invoice/{{ $InvoiceLists->file }}" download="{{ $InvoiceLists->invoice_list_code }}" target="_blank">{{ $InvoiceLists->file }}</a>

                                        @else <b>-</b> @endif
                                    </td>
                                    <th>Berapa Kali Pembayaran</th><td>:</td>
                                    <td>
                                        @if($InvoiceLists->invoice_code == '1') 1 @endif
                                        @if($InvoiceLists->invoice_code == '2') 2 @endif
                                        @if($InvoiceLists->invoice_code == '3') 3 @endif
                                        @if($InvoiceLists->invoice_code == '4') 4 @endif
                                        @if($InvoiceLists->invoice_code == '5') 5 @endif
                                        @if($InvoiceLists->invoice_code == '6') 6 @endif
                                        @if($InvoiceLists->invoice_code == '7') 7 @endif
                                        @if($InvoiceLists->invoice_code == '8') 8 @endif
                                        @if($InvoiceLists->invoice_code == '9') 9 @endif
                                        @if($InvoiceLists->invoice_code == '10') 10 @endif
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
                                    <th>Invoice Date</th>
                                    <td>:</td>
                                    <td>{{ $InvoiceLists->date_out }}</td>
                                    <th>Note</th>
                                    <td>:</td>
                                    <td>{{ $InvoiceLists->note }}</td>
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
                        <h4 style="font-weight: 500">Invoice Payment</h4>
                    <hr>
                    <div class="text-left"><b>Total yang harus di bayar : Rp. {{ number_format($list_detail->sum('net_price'), 2) }}</b></div><hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="prospect-sales-history-table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th width="5%">#</th><th width="30%"></th><th width="10%">Date Payment</th><th width="20%">Amount (Rp.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=1 @endphp
                                @foreach($InvoicePayment as $payment)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>
                                            @if(isset($payment->file_payment) != '')
                                                <a href="{{ url('/') }}/files/invoice-payment/{{ $payment->file_payment }}" download="{{ date('Y-m-d') }}" target="_blank">{{ $payment->file_payment }}</a>
                                            @else <b>-</b> @endif</td>
                                        <td align="center">{{ $payment->date_payment }}</td>
                                        <td align="right">{{ number_format($payment->amount,2) }}</td>
                                    </tr>
                                    @php $no++ @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="text-left">
                        <b>Total Debt/ Total Hutang :
                        @if($RestBill == 0)
                            Lunas
                        @else
                            Rp. {{ number_format($RestBill, 2) }}
                        @endif
                        </b>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection