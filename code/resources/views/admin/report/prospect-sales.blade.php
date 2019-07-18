@extends('layouts.admin.frame')

@section('title', 'Laporan Prospect Sales')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">Report Prospect Sales</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Report Prospect Sales </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="prospect-sales-table">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th><th width="25%">Sales Person</th><th width="25%">Company Name</th><th width="20%">Company Phone</th><th width="15%">Action Date</th><th width="10%">Actions</th>
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
        oTable = $('#prospect-sales-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            scrollX : false,
            dom : 'Bfrtip',
            buttons: [
                'pdf',{
                    extend: 'print',
                    text: 'Print',
                    autoPrint: true,
                    exportOptions: {
                        columns: ':visible',
                    },
                    customize: function (win) {
                        $(win.document.body).find('table').addClass('display').css('font-size', '9px');
                        $(win.document.body).find('tr:nth-child(odd) td').each(function(index){
                            $(this).css('background-color','#D0D0D0');
                        });
//                        $(win.document.body)
//                                .css( 'font-size', '10pt' )
//                                .prepend(
//                                    '<img src="{{ asset("images/user.png") }}" align="middle" style="position:absolute; top:0; left:0;" />'
//                                );

                        $(win.document.body).find('h1').css('text-align','left').css('font-size','12px').css('font-size','12px');
                    }
                }
            ],
            ajax: '{!! route('report.prospect-sales.data') !!}',
            columns: [
                { data: "rownum", name: "rownum" },
                { data: 'name_sales', name: 'name_sales'},
                { data: 'company_name', name: 'company_name' },
                { data: 'company_phone', name: 'company_phone' },
                { data: 'assignment_date', name: 'assignment_date' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
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
                        url: '{{route("brand.index")}}' + "/" + id + '?' + $.param({"_token" : '{{ csrf_token() }}' }),
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
