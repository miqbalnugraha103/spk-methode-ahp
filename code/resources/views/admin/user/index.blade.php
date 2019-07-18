@extends('layouts.admin.frame')

@section('title', 'Users')

@section('content')
    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">User</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Users <span class="pull-right"><a href="{{ url('/admin/users/create') }}" class="btn bg-green waves-effect" title="Add New Test">
                                  <i class="fa fa-plus" aria-hidden="true"></i> Add New</a></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="users-table">
                                <thead>
                                    <tr>
                                        <th width="10%">#</th><th>Name</th><th>Username</th><th>Email</th><th width="20%">Action</th>
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
        oTable = $('#users-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
//            dom: 'Bfrtip',
//            buttons: [
//                'copy', 'csv', 'excel', 'pdf', 'print'
//            ],
            ajax: '{!! route('users.data') !!}',
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
                        url: '{{ route("users.index") }}/' + id + '?' + $.param({"_token" : '{{ csrf_token() }}' }),
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
