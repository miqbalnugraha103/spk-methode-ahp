@extends('layouts.admin.frame')

@section('title', 'Report Delivery Order Lists')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">Report Delivery Order Lists</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Report Delivery Order Lists </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="delivery-order-list-table">
                                <thead>
                                <tr>
                                    <th width="5%">#</th><th>Delivery Order Name</th><th>Purchase Order Name</th><th>Company Name</th><th>Sales Person</th><th>Delivery Order Date</th><th width="10%">Actions</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        var oTable;
        oTable = $('#delivery-order-list-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            scrollX : false,
            dom : 'Bfrtip',
            buttons: [
                'pdf',{
                    extend: 'print',
                    text: 'Print',
                    autoPrint: true,
                    exportOptions: {
                        columns: ':visible',
                    },
                    customize: function (win) {
                        $(win.document.body).find('table').addClass('display').css('font-size', '9px');
                        $(win.document.body).find('tr:nth-child(odd) td').each(function(index){
                            $(this).css('background-color','#D0D0D0');
                        });
//                        $(win.document.body)
//                                .css( 'font-size', '10pt' )
//                                .prepend(
//                                    '<img src="{{ asset("images/user.png") }}" align="middle" style="position:absolute; top:0; left:0;" />'
//                                );

                        $(win.document.body).find('h1').css('text-align','left').css('font-size','12px').css('font-size','12px');
                    }
                }
            ],
            ajax: '{!! route('report.delivery-order-list.data') !!}',
            columns: [
                { data: "rownum", name: "rownum" ,searchable: false},
                { data: "delivery_order_list_code", name: "delivery_order_list_code" },
                { data: "purchase_order_list_code", name: "purchase_order_list_code" },
                { data: "company_name", name: "company_name" },
                { data: "name_sales", name: "name_sales" },
                { data: "date_out", name: "date_out" },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
        });
    </script>
@endpush
