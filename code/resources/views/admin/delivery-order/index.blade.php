@extends('layouts.admin.frame')

@section('title', 'Delivery Order Lists')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">Delivery Order Lists</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box">
                    <div class="icon bg-green">
                        <i class="material-icons">playlist_add_check</i>
                    </div>
                    <div class="content">
                        <div class="text">ALL DELIVERY ORDER</div>
                        <div class="number count-to" id="all_do" data-from="0" data-to="{{ $allDOCount }}" data-speed="1000" data-fresh-interval="20" style="cursor:pointer;">{{ $allDOCount }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box">
                    <div class="icon bg-indigo">
                        <i class="material-icons">fiber_new</i>
                    </div>
                    <div class="content">
                        <div class="text">NEW DELIVERY ORDER</div>
                        <div class="number count-to" id="new_do" data-from="0" data-to="{{ $newDOCount }}" data-speed="1000" data-fresh-interval="20" style="cursor:pointer;">{{ $newDOCount }}</div>
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
                                <h2>Delivery Order Lists<span class="pull-right">
                                        @if(Auth::user()->role ==  \App\User::ROLE_SUPERADMIN || Auth::user()->role ==  \App\User::ROLE_ADMIN)
                                            {!! Form::open(['url' => '/admin/delivery-order-list/createNewDO', 'class' => 'form-horizontal', 'files' => true]) !!}
                                                <!-- <input type="hidden" name="prospect_sales_id" value="0">
                                                <input type="submit" value="Add New" class="btn bg-green waves-effect" title="Add New"> -->
                                                <a href="{{ url('/admin/delivery-order-list/create') }}" class="btn bg-green waves-effect" title="Add New"> <i class="fa fa-plus" aria-hidden="true"></i> Add New</a></span>
                                            {!! Form::close() !!}
                                        @endif
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed table-hover" id="delivery-order-list-table" style="width: 100%">
                                <thead>
                                <tr>
                                    <th width="5%">#</th><th width="30%">Delivery Order Name</th><th width="20%">Invoice Name</th><th width="30%">Purchase Order Name</th><th width="20%">Quote Name</th><th>Created At</th><th width="15%">Actions</th>
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
        oTable = $('#delivery-order-list-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            scrollX : false,
//            dom : 'Bfrtip',
//            buttons: [
//                'copy', 'csv', 'excel', 'pdf', 'print'
//            ],
            ajax: '{!! route('delivery-order.data') !!}',
            columns: [
                { data: "rownum", name: "rownum" ,searchable: false},
                { data: "delivery_order_list_code", name: "delivery_order_list_code" },
                { data: "invoice_list_code", name: "invoice_list_code" },
                { data: "purchase_order_list_code", name: "purchase_order_list_code" },
                { data: "quote_list_code", name: "quote_list_code" },
                { data: "created_at", name: "created_at" },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            columnDefs: [
                {
                    "targets": [5],
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
                        url: '{{route("delivery-order-list.index")}}' + "/" + id + '?' + $.param({"_token" : '{{ csrf_token() }}' }),
                        data: {_method: 'delete'},
                        complete: function (msg) {
                            oTable.draw();
                            swal("Success", "Your data already canceled", "success");
                        }
                    });
                } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                }
            });
        }
        $('#all_do').click(function(){
            oTable.columns(5).search("").draw();
        });
        $('#new_do').click(function(){
            oTable.columns(5).search("{{ date('Y-m-d') }}").draw();

        });
    </script>
@endpush
