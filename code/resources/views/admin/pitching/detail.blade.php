@extends('layouts.admin.frame')

@section('title', 'Pitching Detail')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li><a href="{{ url('/sales-progress') }}">Pitching</a></li>
        <li class="active">{{ $prospectsales->company_name }} Detail</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2><b>{{ $prospectsales->company_name }}</b> Detail<span class="pull-right"><a class="btn bg-green waves-effect" title="Back" onclick="goBack()">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th width="15%">Address</th><td width="1%">:</td><td>{{ $prospectsales->company_address }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th><td>:</td><td>{{ $prospectsales->company_phone }} </td>
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
                                @foreach($getTerm as $term)
                                    @if($term->id == $prospectsales->term_condition_id)
                                    <tr>
                                        <th width="5%">Term & Condition Name</th><td width="1%">:</td><td>{{ $term->name }}</td>
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
                        <!-- <div class="box box-solid">
                            <div class="box-body">
                              <div class="box-group" id="accordion">
                                <div class="panel box box-danger">
                                  <div class="box-header with-border">
                                    <h4 class="box-title">
                                      <a data-toggle="collapse" href="#collapseOne" class="btn bg-blue-grey btn-lg waves-effect" style="width: 100%;">
                                        Prospect Progress<i class="fa fa-angle-down pull-right"></i>
                                      </a>
                                    </h4>
                                  </div>
                                  <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover" id="prospect-progress-table" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th width="2%">#</th><th>Sales Person</th><th>Progress Notes</th><th>Assignment Date</th><th width="10%">Status</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div> -->
                        <!-- /.box -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        var oTable;
        oTable = $('#prospect-progress-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            scrollX : false,
//            dom : 'Bfrtip',
//            buttons: [
//                'copy', 'csv', 'excel', 'pdf', 'print'
//            ],
            ajax: '{!! route('sales-progress-detail.data', ['id' => $id]) !!}',
            columns: [
                { data: "rownum", name: "rownum" },
                { data: 'name_sales', name: 'name_sales' },
                { data: 'progress_notes', name: 'progress_notes' },
                { data: 'assignment_date', name: 'assignment_date' },
                { data: 'status', name: 'status' }
//                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
        function goBack() {
            window.history.back();
        }

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
