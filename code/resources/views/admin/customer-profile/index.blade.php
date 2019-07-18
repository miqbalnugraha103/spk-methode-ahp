@extends('layouts.admin.frame')

@section('title', 'Customer Profile')

@section('content')

    <ol class="breadcrumb breadcrumb-col-deep-purple">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">Customer Profile</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Customer Profile <span class="pull-right"><a href="{{ url('/admin/customer-profile/create') }}" class="btn bg-green waves-effect" title="Add New customer-profile">
                                  <i class="fa fa-plus" aria-hidden="true"></i> Add New</a></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="customer-profile-table" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th><th width="40%">Company Name</th><th width="20%">Name PIC</th><th width="20%">Phone</th><th width="15%">Actions</th>
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
        oTable = $('#customer-profile-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            scrollX : false,
//          dom : 'Bfrtip',
//          buttons: [
//              'copy', 'csv', 'excel', 'pdf', 'print'
//          ],
            ajax: '{!! route('customer-profile.data') !!}',
            columns: [
                { data: 'rownum', name: 'rownum' },
                { data: 'company_name', name: 'company_name' },
                { data: 'name_pic', name: 'name_pic' },
                { data: 'company_phone', name: 'company_phone' },
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        function deleteData(id) {
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel !",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: "POST",
                        url: '{{route("customer-profile.index")}}' + "/" + id + '?' + $.param({"_token" : '{{ csrf_token() }}' }),
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
