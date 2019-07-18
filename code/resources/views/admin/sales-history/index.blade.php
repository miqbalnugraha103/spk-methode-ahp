@extends('layouts.admin.frame')

@section('title', 'Sales Assignment')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">Sales Assignment</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Sales Assignment<span class="pull-right"><a href="{{ url('/admin/sales') }}" class="btn bg-green waves-effect" title="Back">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="sales-history-table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Company Name</th><th width="10%">Status</th>
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
        oTable = $('#sales-history-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            scrollX : false,
//            dom : 'Bfrtip',
//            buttons: [
//                'copy', 'csv', 'excel', 'pdf', 'print'
//            ],
            ajax: '{!! route('sales-assignment.data', ['id' => $id]) !!}',
            columns: [
                { data: "rownum", name: "rownum" },
                { data: 'company_name', name: 'company_name' },
                { data: 'status', name: 'status' }
//                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        {{--function deleteData(id) {--}}
            {{--swal({--}}
                {{--title: "Are you sure?",--}}
                {{--text: "You will not be able to recover this imaginary file!",--}}
                {{--type: "warning",--}}
                {{--showCancelButton: true,--}}
                {{--confirmButtonColor: "#DD6B55",--}}
                {{--confirmButtonText: "Yes, delete it!",--}}
                {{--cancelButtonText: "No, cancel plx!",--}}
                {{--closeOnConfirm: false,--}}
                {{--closeOnCancel: false--}}
            {{--}, function (isConfirm) {--}}
                {{--if (isConfirm) {--}}
                    {{--$.ajax({--}}
                        {{--type: "POST",--}}
                        {{--url: '{{route("prospect-sales.index")}}' + "/" + id + '?' + $.param({"_token" : '{{ csrf_token() }}' }),--}}
                        {{--data: {_method: 'delete'},--}}
                         {{--complete: function (msg) {--}}
                            {{--oTable.draw();--}}
                            {{--swal("Success", "Your data already deleted", "success");--}}
                        {{--}--}}
                    {{--});--}}
                {{--} else {--}}
                    {{--swal("Cancelled", "Your imaginary file is safe :)", "error");--}}
                {{--}--}}
            {{--});--}}
        {{--}--}}
    </script>
@endpush
