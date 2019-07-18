@extends('layouts.admin.frame')

@section('title', 'Prospect History')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li><a href="{{ url('/admin/prospect') }}">Prospect Sales</a></li>
        <li class="active">Prospect History</li>
    </ol>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Prospect History</div>
                    <div class="panel-body">

                        <p><a href="{{ url('/admin/prospect') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></p>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
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
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="prospect-sales-history-table" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>#</th><th>Sales Person</th><th>Action Date</th><th>Notes</th><th width="15%">Action Update</th><th>Status Update</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                @foreach($getTerm as $term)
                                    @if($term->id == $prospectsales->term_condition_id)
                                        <tr>
                                            <th width="30%">Term & Condition Name</th><td width="1%">:</td><td>{{ $term->name }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                {!! $term->content !!}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
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
            ajax: '{!! route('prospect-sales-history.data', ['id' => $id]) !!}',
            columns: [
                { data: "rownum", name: "rownum" },
                { data: 'name_sales', name: 'name_sales' },
                { data: 'assignment_date', name: 'assignment_date' },
                { data: 'notes', name: 'notes', className: 'notes_warp'},
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
