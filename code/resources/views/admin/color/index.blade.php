@extends('layouts.admin.frame')

@section('title', 'Color')

@section('content')

    <ol class="breadcrumb breadcrumb-col-deep-purple">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">Color</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Color <span class="pull-right"><a href="{{ url('/admin/color/create') }}" class="btn bg-green waves-effect" title="Add New Color">
                                  <i class="fa fa-plus" aria-hidden="true"></i> Add New</a></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="color_table" width="100%">
                                <thead>
                                    <tr>
                                        <th width="10%">#</th><th width="70%">Name</th><th width="20%">Actions</th>
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
        oTable = $('#color_table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            scrollX : false,
//          dom : 'Bfrtip',
//          buttons: [
//              'copy', 'csv', 'excel', 'pdf', 'print'
//          ],
            ajax: '{!! route('color.data') !!}',
            columns: [
                { data: "rownum", name: "rownum" },
                { data: 'color_name', name: 'color_name' },
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
                        url: '{{route("color.index")}}' + "/" + id + '?' + $.param({"_token" : '{{ csrf_token() }}' }),
                        data: {_method: 'delete'},
                         complete: function (msg) {
                            oTable.draw();
                            swal("Success", "Your data already deleted", "success");
                        }
                    });
                } else {
                    swal("Cancelled", "Your imaginary file is safe :)", "error");
                }
            });
        }
    </script>
@endpush
