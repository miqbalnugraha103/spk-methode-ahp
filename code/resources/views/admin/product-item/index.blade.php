@extends('layouts.admin.frame')

@section('title', 'Product Item')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">Product Item</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Product Item<span class="pull-right"><a href="{{ url('/admin/product/create') }}" class="btn bg-green waves-effect" title="Add New Product">
                                    <i class="fa fa-plus"></i> Add New</a></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="product-item-table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th><th width="30%">Brand</th><th width="30%">Item</th><th width="10%">Price (Rp.)</th><th width="5%">Diskon (%)</th><th width="20%">Images</th><th width="15%">Actions</th>
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
        oTable = $('#product-item-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            scrollX : false,
//            dom : 'Bfrtip',
//            buttons: [
//                'copy', 'csv', 'excel', 'pdf', 'print'
//            ],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            displayLength: 50,
            ajax: '{!! route('product.data') !!}',
            columns: [
                { data: "rownum", name: "rownum" },
                { data: 'brand', name: 'brand' },
                { data: 'item', mane: 'item'},
                { data: 'price', mane: 'price', className: 'text-right' },
                { data: 'diskon', mane: 'diskon', className: 'text-center' },
                { data: 'image', name: 'image' },
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
                        url: '{{route("product.index")}}' + "/" + id + '?' + $.param({"_token" : '{{ csrf_token() }}' }),
                        data: {_method: 'delete'},
                         complete: function (msg) {
                            oTable.draw();
                             swal({
                                 title: "Success!",
                                 text: "Your data already deleted",
                                 type: "success",
                                 timer: 2000,
                                 showCancelButton: false,
                                 closeOnConfirm: false,
                                 closeOnCancel: false
                             });
                        }
                    });
                } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                }
            });
        }
    </script>
@endpush
