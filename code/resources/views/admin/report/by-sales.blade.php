@extends('layouts.admin.frame')

@section('title', 'Report By Sales')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">Report By Sales</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <!-- Line Chart -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Chart By Sales</h2>
                        <div class="body">
                            <canvas id="bar_chart" height="100" style="display: block; width: 528px; height: 264px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Report By Sales
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <p><a href="{{ URL('admin/report/pdf-sales-transaction') }}" id="btnpdf" class="btn bg-primary" target="_blank" style="">PDF</a>
                            <a href="{{ URL('admin/report/print-sales-transaction') }}" id="btnprint" class="btn bg-primary" target="_blank">Print</a>&nbsp;&nbsp;&nbsp;
                        </p>
                        <div class="table-responsive" style="width: 100%;">
                            <table class="table table-bordered table-striped table-hover" id="invoice-list-table">
                                <thead>
                                <tr>
                                    <th width="2%">No</th><th>Sales Person</th><th>Company Name</th><th>Transaction Status</th><th>Last Activity Status</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection@

@push('script')
    <script>
        $(function () {
            new Chart(document.getElementById("bar_chart").getContext("2d"), getChartJs('bar'));
        });

        function getChartJs(type) {
            var config = null;

            if (type === 'bar') {
                config = {
                    type: 'bar',
                    data: {
                        labels: ["September", "October", "November", "December", "January"],
                        datasets: [{
                            label: "Quotation",
                            data: [4, 7, 2, 1, 2],
                            backgroundColor: 'rgba(156, 39, 176, 0.8)'
                        },
                            {
                            label: "Purchase Order",
                            data: [1, 3, 3, 2, 1],
                            backgroundColor: 'rgba(91, 192, 222, 0.8)'
                        },
                            {
                            label: "Invoice",
                            data: [1, 0, 0, 2, 1],
                            backgroundColor: 'rgba(51, 122, 183, 0.8)'
                        },
                            {
                            label: "Delivery Order",
                            data: [1, 0, 1, 1, 1],
                            backgroundColor: 'rgba(92, 184, 92, 0.8)'
                        }]
                    },
                    options: {
                        responsive: true,
                    }
                }
            }
            return config;
        }
    </script>
    <script>
        var oTable;
        oTable = $('#invoice-list-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            scrollX : false,
            pageLength:-1,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            dom : 'Bfrtip',
              buttons: [
                  {
                      extend: 'excel',
                      filename: 'by-sales',
                      text: 'Excel'
                  },
            ],
            ajax: '{!! route('report.by-sales.data') !!}',
            columns: [
                { data: "rownum", name: "rownum" ,searchable: false},
                { data: "name_sales", name: "name_sales" },
                { data: "company_name", name: "company_name" },
                { data: "fix_po", name: "fix_po" },
                { data: "name_progress", name: "name_progress" },
            ]
        });
    </script>
    <script type="text/javascript">
        $('.input-sm').on('keyup',function(){
            $value=$(this).val();

            if($value == ''){
                $("#btnprint").prop("href", "{{ URL('admin/report/print-sales-transaction') }}");
                $("#btnpdf").prop("href", "{{ URL('admin/report/pdf-sales-transaction') }}");
            }else{
                $("#btnprint").prop("href", "print-sales-transaction?search="+$value);
                $("#btnpdf").prop("href", "pdf-sales-transaction?search="+$value);
            }
        })
    </script>
    <script type="text/javascript">

        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });

    </script>
@endpush
