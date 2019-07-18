@extends('layouts.admin.frame')

@section('title', 'Sales Person')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">Sales Person</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box">
                    <div class="icon bg-red">
                        <i class="material-icons">face</i>
                    </div>
                    <div class="content">
                        <div class="text">ALL SALES</div>
                        <div class="number count-to" data-from="0" data-to="{{ $allSalesCount }}" data-speed="1000" data-fresh-interval="20">{{ $allSalesCount }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box">
                    <div class="icon bg-indigo">
                        <i class="material-icons">face</i>
                    </div>
                    <div class="content">
                        <div class="text">NEW SALES</div>
                        <div class="number count-to" data-from="0" data-to="{{ $newSalesCount }}" data-speed="1000" data-fresh-interval="20">{{ $newSalesCount }}</div>
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
                                <h2>Sales Person <span class="pull-right"><a href="{{ url('/admin/sales/create') }}" class="btn bg-green waves-effect" title="Add New Sale">
                                  <i class="fa fa-plus" aria-hidden="true"></i> Add New</a></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="sales-table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th><th>Full Name</th><th>Username</th><th>Email</th><th width="20%">Actions</th>
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
        $(function () {
            initCounters();
        });

        //Widgets count plugin
        function initCounters() {
            $('.count-to').countTo();
        }
    </script>
    <script>
        var oTable;
        oTable = $('#sales-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            scrollX : false,
//            dom : 'Bfrtip',
//            buttons: [
//                'copy', 'csv', 'excel', 'pdf', 'print'
//            ],
            ajax: '{!! route('sales.data') !!}',
            columns: [
                { data: "rownum", name: "rownum" },
                { data: 'name', name: 'name' },
                { data: 'username', name: 'username' },
                { data: 'email', name: 'email' },
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        function deleteData(id) {
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: "POST",
                        url: '{{route("sales.index")}}' + "/" + id + '?' + $.param({"_token" : '{{ csrf_token() }}' }),
                        data: {_method: 'delete'},
                         complete: function (msg) {
                            oTable.draw();
                            swal("Success", "Your data already deleted", "success");
                        }
                    });
                } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                }
            });
        }
    </script>
@endpush
