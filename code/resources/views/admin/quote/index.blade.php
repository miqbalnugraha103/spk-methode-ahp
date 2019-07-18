@extends('layouts.admin.frame')

@section('title', 'Quote Lists')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">Quote Lists</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box">
                    <div class="icon bg-green">
                        <i class="material-icons">label</i>
                    </div>
                    <div class="content">
                        <div class="text">ALL QUOTATION</div>
                        <div class="number count-to" id="all_quotation" data-from="0" data-to="{{ $allQuoteCount }}" data-speed="1000" data-fresh-interval="20" style="cursor:pointer;">{{ $allQuoteCount }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box">
                    <div class="icon bg-indigo">
                        <i class="material-icons">fiber_new</i>
                    </div>
                    <div class="content">
                        <div class="text">NEW QUOTATION</div>
                        <div class="number count-to" id="new_quotation" data-from="0" data-to="{{ $newQuoteCount }}" data-speed="1000" data-fresh-interval="20" style="cursor:pointer;">{{ $newQuoteCount }}</div>
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
                                <h2>Quote Lists<span class="pull-right">
                                    @if(Auth::user()->role ==  \App\User::ROLE_SUPERADMIN || Auth::user()->role ==  \App\User::ROLE_ADMIN)
                                        {!! Form::open(['url' => '/admin/quote-list/createNewQuote', 'class' => 'form-horizontal']) !!}
                                            <input type="hidden" name="prospect_sales_id" value="0">
                                        <button type="submit" class="btn bg-green waves-effect"><i class="fa fa-plus" aria-hidden="true"></i> Add New</button>
                                            <!-- <a href="{{ url('/admin/quote-list/create') }}" class="btn bg-green waves-effect" title="Add New"> <i class="fa fa-plus" aria-hidden="true"></i> Add New</a></span>-->
                                        {!! Form::close() !!}
                                    @endif
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="quote-list-table" width="100%">
                                <thead>
                                <tr>
                                    <th width="5%">#</th><th>Quote Name</th><th>Requote Name</th><th>Status</th><th>Created At</th><th width="10%">Actions</th>
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
        oTable = $('#quote-list-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            scrollX : false,
//            dom : 'Bfrtip',
//            buttons: [
//                'copy', 'csv', 'excel', 'pdf', 'print'
//            ],
            ajax: '{!! route('quote-list.data') !!}',
            columns: [
                { data: "rownum", name: "rownum" ,searchable: false},
                { data: "quote_list_code", name: "quote_list_code" },
                { data: "quote_code", name: "quote_code" },
                { data: "fix_po", name: "fix_po" },
                { data: "created_at", name: "created_at" },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            columnDefs: [
                {
                    "targets": [4],
                    "visible": false,
                    "searchable": true
                }
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
                        url: '{{route("quote-list.index")}}' + "/" + id + '?' + $.param({"_token" : '{{ csrf_token() }}' }),
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
        $('#all_quotation').click(function(){
           oTable.columns(4).search("").draw();
        });
        $('#new_quotation').click(function(){
            // oTable.fnSort([1,'asc']);
            oTable.columns(4).search("{{ date('Y-m-d') }}").draw();

        });
    </script>
@endpush
