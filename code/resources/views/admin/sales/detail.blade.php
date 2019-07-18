@extends('layouts.admin.frame')

@section('title', 'Sales Person Detail')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li><a href="{{ url('/admin/sales') }}">Sales Person</a></li>
        <li class="active">Sales Person Detail</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Sales Person Detail <b>({{ $sales->name }})</b><span class="pull-right"><a class="btn bg-green waves-effect" title="Back" onclick="goBack()">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="sales-detail-table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="2%">#</th><th>Company Name</th><th>Status Date</th><th width="15%">Status Update</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
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
        oTable = $('#sales-detail-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            scrollX : false,
//            dom : 'Bfrtip',
//            buttons: [
//                'copy', 'csv', 'excel', 'pdf', 'print'
//            ],
            ajax: '{!! route('detail-sales.data', ['id' => $sales]) !!}',
            columns: [
                { data: "rownum", name: "rownum" },
                { data: 'company_name', name: 'company_name' },
                { data: 'assignment_date', name: 'assignment_date' },
                { data: 'name_progress', name: 'name_progress' }
//                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
        function goBack() {
            window.history.back();
        }
    </script>
@endpush
