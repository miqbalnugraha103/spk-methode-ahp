@extends('layouts.admin.frame')

@section('title', 'Prospect Sales')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">Prospect Sales</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Prospect Sales
                                    @if(Auth::user()->role ==  \App\User::ROLE_SUPERADMIN || Auth::user()->role ==  \App\User::ROLE_ADMIN)
                                        <span class="pull-right">
                                            <a href="{{ url('/admin/prospect/create') }}" class="btn bg-green waves-effect" title="Add New Sales">
                                                <i class="fa fa-plus"></i> Add New
                                            </a>
                                        </span>
                                    @endif
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="prospect-sales-table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th><th>Company Name</th><th>Company Phone</th><th>Sales Person</th><th>Action Date</th><th width="20%">Actions</th>
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
        oTable = $('#prospect-sales-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            scrollX : false,
//            dom : 'Bfrtip',
//            buttons: [
//                'copy', 'csv', 'excel', 'pdf', 'print'
//            ],
            "iDisplayLength": 100,
            ajax: '{!! route('prospect.data') !!}',
            columns: [
                { data: "rownum", name: "rownum" },
                { data: 'company_name', name: 'company_name' },
                { data: 'company_phone', name: 'company_phone' },
                { data: 'name_sales', name: 'name_sales' },
                { data: 'assignment_date', name: 'assignment_date' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
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
                        url: '{{route("prospect.index")}}' + "/" + id + '?' + $.param({"_token" : '{{ csrf_token() }}' }),
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
