@extends('layouts.admin.frame')

@section('title', 'Report Prospect Sales History')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li><a href="{{ url('/admin/report/prospect-sales') }}">Report Prospect Sales</a></li>
        <li class="active">Report Prospect Sales Detail</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Report Prospect Sales Detail<span class="pull-right"><a href="{{ url('/admin/report/prospect-sales') }}" class="btn bg-green waves-effect" title="Back">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr><td><span class="pull-right"><a href="{{ url('/admin/report/prospect-sales-detail/'.$prospectsales->id.'/generate-pdf') }}" target="_blank" class="btn bg-blue-grey waves-effect" title="Back">
                                    <i class="fa fa-file" aria-hidden="true"></i> PDF</a></span></td><td></td><td></td></tr>
                                <tr>
                                    <th width="15%">Company Name</th><td width="1%">:</td><td>{{ $prospectsales->company_name }}</td>
                                </tr>
                                <tr>
                                    <th width="15%">Company Address</th><td width="1%">:</td><td>{{ $prospectsales->company_address }}</td>
                                </tr>
                                <tr>
                                    <th>Company Phone</th><td>:</td><td>{{ $prospectsales->company_phone }} </td>
                                </tr>
                                <tr>
                                    <th>CP Name</th><td>:</td><td>{{ $prospectsales->name_pic }} </td>
                                </tr>
                                <tr>
                                    <th>Progress Notes</th><td>:</td><td>{!! $prospectsales->progress_notes !!} </td>
                                </tr>
                                <tr>
                                    <th>Brand</th>
                                    <td>:</td>
                                    <td>
                                        @foreach($brandData as $td)
                                        {{ $td->brand }}<br>

                                        @endforeach
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="prospect-sales-history-table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Sales Person</th><th>Action Date</th><th>Notes</th><th width="15%">Action Update</th><th>Status Update</th>
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
        oTable = $('#prospect-sales-history-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: false,
            paging:   false,
            ordering: false,
            info:     false,
            scrollX : false,
//            dom : 'Bfrtip',
//            buttons: [
//                'copy', 'csv', 'excel', 'pdf', 'print'
//            ],
            ajax: '{!! route('report.prospect-sales-history.data', ['id' => $id]) !!}',
            columns: [
                { data: "rownum", name: "rownum" },
                { data: 'name_sales', name: 'name_sales' },
                { data: 'assignment_date', name: 'assignment_date' },
                { data: 'progress_notes', name: 'progress_notes', className: 'notes_warp'},
                { data: 'status', name: 'status' },
                {
                    data: 'created_at',
                    type: 'num',
                    render: {
                        _: 'display',
                        sort: 'timestamp'
                    }
                }
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
