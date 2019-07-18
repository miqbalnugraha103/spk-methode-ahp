@extends('layouts.admin.frame')

@section('title', 'Report By Brand')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">Report By Brand</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <!-- Line Chart -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Chart By Brand</h2>
                        <div class="body">
                            <canvas id="pie_chart" height="336" width="673" style="display: block; width: 673px; height: 336px;"></canvas>
                        </div>
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
                                <h2>Report By Brand
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <p><a href="{{ URL('admin/report/pdf-brand') }}" id="btnpdf" class="btn bg-primary" target="_blank">PDF</a>
                            <a href="{{ URL('admin/report/print-brand') }}" id="btnprint" class="btn bg-primary" target="_blank">Print</a>
                            <a href="{{ URL('admin/report/excel-brand/xlsx') }}" id="btnprint" class="btn bg-primary" target="_blank">Excel</a>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="form-label">Start Date: <input type="text" name="start_date" id="start_date" value="" class="form-control datetimepicker" placeholder="" style="width: 200px;"></label>&nbsp;&nbsp;&nbsp;To&nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="form-label">End Date: <input type="text" name="end_date" id="end_date" value="" class="form-control datetimepicker" placeholder="" style="width: 200px;"> </label>
                        </p>
                        <div class="pull-right" style="margin-top: -40px;">
                        <div class="form-group form-float"> 
                                <div class="form-line">
                                    <label class="form-label">Search: </label>
                                    <input type="text" name="search" id="search" value="" class="form-control" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div id="proccesing" style="display: none;">proccesing...</div>
                        <div class="table-responsive" style="width: 100%;">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th width="2%">#</th><th>Brand</th><th>Product</th><th>Qty</th><th>Price (Rp.)</th><th>Disc (Rp.)</th><th>Net Price (Rp.)</th><th>Product Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($historybrand as $no => $brand)
                                    <tr>
                                        <td>{!! ++$no !!}</td>
                                        <td>{!! $brand->brand !!}</td>
                                        <td>{!! $brand->name_product !!}</td>
                                        <td align="center">{!! $brand->qty !!}</td>
                                        <td align="right">{!! number_format($brand->price,2) !!}</td>
                                        <td align="right">@if($brand->diskon_nominal == 0 || $brand->diskon_nominal == '') 0 @else {!! number_format($brand->diskon_nominal,2) !!} @endif</td>
                                        <td align="right">{!! number_format($brand->net_price,2) !!}</td>
                                        <td>{!! $brand->created_at !!}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @if(count($historybrand) == 0)
                                    <tfoot>
                                    <tr>
                                        <th colspan="9">No data available in table</th>
                                    </tr>
                                    </tfoot>
                                @endif
                            </table>
                            <div id="paging">{{ $historybrand->links() }}</div>
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
        new Chart(document.getElementById("pie_chart").getContext("2d"), getChartJs('pie'));
    });

    function getChartJs(type) {
        var config = null;

        if (type === 'pie') {
            config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [26060500,
                            3400000,
                            48934000,
                            4290500,
                            8985900,
                            1340050,
                            1735000,
                            48790000,
                            35570000,
                            227000,
                            2855000,
                            46400000,
                            4054000,
                            11692000,
                            450000,
                        ],
                        backgroundColor: [
                            "rgb(233, 30, 99)",
                            "rgb(255, 193, 7)",
                            "rgb(0, 188, 212)",
                            "rgb(139, 195, 74)",
                            "rgb(229, 229, 229)",
                            "rgb(107, 48, 48)",
                            "rgb(253, 255, 134)",
                            "rgb(87, 144, 102)",
                            "rgb(133, 191, 175)",
                            "rgb(8, 122, 156)",
                            "rgb(142, 125, 234)",
                            "rgb(71, 73, 74)",
                            "rgb(101, 90, 91)",
                            "rgb(78, 55, 255)",
                            "rgb(0, 255, 126)",
                            "rgb(184, 234, 172)"
                        ],
                    }],
                    labels: [
                        "COTTO",
                        "KEND",
                        "TOTO",
                        "IKAD",
                        "LAIN- LAIN",
                        "NIRO",
                        "FINO",
                        "KALDEWEI",
                        "STIEBEL ELTRON",
                        "DK",
                        "VILLEROY & BOCH",
                        "POLARIS",
                        "KIG",
                        "VALLI",
                        "KENARI"
                    ]
                },
                options: {
                    responsive: true,
                }
            }
        }
        return config;
    }
</script>
<script type="text/javascript">
    $('#search').on('input', function() { 

        $("#proccesing").css("display","inline-block");

        $search = $(this).val();
        $start_date = $('#start_date').val();
        $end_date = $('#end_date').val();
        $.ajax({
            type : "get",
            url : "{{ URL('/admin/report/search-brand') }}",
            data:{"search":$search, "start_date":$start_date, "end_date":$end_date},
            success:function(data){

                $("#proccesing").css("display","none");

                if($start_date == '' && $end_date == '' && $search == '')
                {
                    $("#paging").show();
                    $("#btnprint").prop("href", "{{ URL('admin/report/print-brand') }}");
                    $("#btnpdf").prop("href", "{{ URL('admin/report/pdf-brand') }}");
                }
                else if($start_date != '' && $end_date == '' && $search == '')
                {
                    $("#paging").hide();
                    $("#btnprint").prop("href", "print-brand/"+$start_date);
                    $("#btnpdf").prop("href", "pdf-brand/"+$start_date);
                }
                else if($start_date != '' && $end_date != '' && $search == '')
                {
                    $("#paging").hide();
                    $("#btnprint").prop("href", "print-brand/"+$start_date+"/"+$end_date);
                    $("#btnpdf").prop("href", "pdf-brand/"+$start_date+"/"+$end_date);
                }
                else if($start_date != '' && $end_date != '' && $search != '')
                {
                    $("#paging").hide();
                    $("#btnprint").prop("href", "print-brand/"+$start_date+"/"+$end_date+"/"+$search);
                    $("#btnpdf").prop("href", "pdf-brand/"+$start_date+"/"+$end_date+"/"+$search);
                }
                else if($start_date == '' && $end_date == '' && $search != '')
                {
                    $("#paging").hide();
                    $("#btnprint").prop("href", "print-brand-search/"+$search);
                    $("#btnpdf").prop("href", "pdf-brand-search/"+$search);
                }
                else if($start_date == '' && $end_date != '' && $search != '')
                {
                    $("#paging").hide();
                    $("#btnprint").prop("href", "print-brand/"+$end_date+"/"+$search);
                    $("#btnpdf").prop("href", "pdf-brand/"+$end_date+"/"+$search);
                }
                else if($start_date != '' && $end_date == '' && $search != '')
                {
                    $("#paging").hide();
                    $("#btnprint").prop("href", "print-brand/"+$start_date+"/"+$search);
                    $("#btnpdf").prop("href", "pdf-brand/"+$start_date+"/"+$search);
                }
                else if($start_date == '' && $end_date != '' && $search == '')
                {
                    $("#paging").hide();
                    $("#btnprint").prop("href", "print-brand/"+$end_date);
                    $("#btnpdf").prop("href", "pdf-brand/"+$end_date);
                }
            $('tbody').html(data);
            }
        });
    });
    $(document).ready(function() {
        $('#start_date').change(function () {

            $("#proccesing").css("display","inline-block");

            $start_date = $(this).val();
            $search = $('#search').val();
            $end_date = $('#end_date').val();
            $.ajax({
                type : "get",
                url : "{{ URL('/admin/report/start-date-brand') }}",
                data:{"search":$search, "start_date":$start_date, "end_date":$end_date},
                success:function(data){

                    $("#proccesing").css("display","none");

                    if($start_date == '' && $end_date == '' && $search == '')
                    {
                        $("#paging").show();
                        $("#btnprint").prop("href", "{{ URL('admin/report/print-brand') }}");
                        $("#btnpdf").prop("href", "{{ URL('admin/report/pdf-brand') }}");
                    }
                    else if($start_date != '' && $end_date == '' && $search == '')
                    {
                        $("#paging").hide();
                        $("#btnprint").prop("href", "print-brand/"+$start_date);
                        $("#btnpdf").prop("href", "pdf-brand/"+$start_date);
                    }
                    else if($start_date != '' && $end_date != '' && $search == '')
                    {
                        $("#paging").hide();
                        $("#btnprint").prop("href", "print-brand/"+$start_date+"/"+$end_date);
                        $("#btnpdf").prop("href", "pdf-brand/"+$start_date+"/"+$end_date);
                    }
                    else if($start_date != '' && $end_date != '' && $search != '')
                    {
                        $("#paging").hide();
                        $("#btnprint").prop("href", "print-brand/"+$start_date+"/"+$end_date+"/"+$search);
                        $("#btnpdf").prop("href", "pdf-brand/"+$start_date+"/"+$end_date+"/"+$search);
                    }
                    else if($start_date == '' && $end_date == '' && $search != '')
                    {
                        $("#paging").hide();
                        $("#btnprint").prop("href", "print-brand-search/"+$search);
                        $("#btnpdf").prop("href", "pdf-brand-search/"+$search);
                    }
                    else if($start_date == '' && $end_date != '' && $search != '')
                    {
                        $("#paging").hide();
                        $("#btnprint").prop("href", "print-brand/"+$end_date+"/"+$search);
                        $("#btnpdf").prop("href", "pdf-brand/"+$end_date+"/"+$search);
                    }
                    else if($start_date != '' && $end_date == '' && $search != '')
                    {
                        $("#paging").hide();
                        $("#btnprint").prop("href", "print-brand/"+$start_date+"/"+$search);
                        $("#btnpdf").prop("href", "pdf-brand/"+$start_date+"/"+$search);
                    }
                    else if($start_date == '' && $end_date != '' && $search == '')
                    {
                        $("#paging").hide();
                        $("#btnprint").prop("href", "print-brand/"+$end_date);
                        $("#btnpdf").prop("href", "pdf-brand/"+$end_date);
                    }
                $('tbody').html(data);
                }
            });
        });
    });
    $(document).ready(function() {
        $('#end_date').change(function () {

            $("#proccesing").css("display","inline-block");

            $end_date = $(this).val();
            console.log($end_date);
            $start_date = $('#start_date').val();
            $search = $('#search').val();
            $.ajax({
                type : "get",
                url : "{{ URL('/admin/report/end-date-brand') }}",
                data:{"search":$search, "start_date":$start_date, "end_date":$end_date},
                success:function(data){

                    $("#proccesing").css("display","none");

                    if($start_date == '' && $end_date == '' && $search == '')
                    {
                        $("#paging").show();
                        $("#btnprint").prop("href", "{{ URL('admin/report/print-brand') }}");
                        $("#btnpdf").prop("href", "{{ URL('admin/report/pdf-brand') }}");
                    }
                    else if($start_date != '' && $end_date == '' && $search == '')
                    {
                        $("#paging").hide();
                        $("#btnprint").prop("href", "print-brand/"+$start_date);
                        $("#btnpdf").prop("href", "pdf-brand/"+$start_date);
                    }
                    else if($start_date != '' && $end_date != '' && $search == '')
                    {
                        $("#paging").hide();
                        $("#btnprint").prop("href", "print-brand/"+$start_date+"/"+$end_date);
                        $("#btnpdf").prop("href", "pdf-brand/"+$start_date+"/"+$end_date);
                    }
                    else if($start_date != '' && $end_date != '' && $search != '')
                    {
                        $("#paging").hide();
                        $("#btnprint").prop("href", "print-brand/"+$start_date+"/"+$end_date+"/"+$search);
                        $("#btnpdf").prop("href", "pdf-brand/"+$start_date+"/"+$end_date+"/"+$search);
                    }
                    else if($start_date == '' && $end_date == '' && $search != '')
                    {
                        $("#paging").hide();
                        $("#btnprint").prop("href", "print-brand-search/"+$search);
                        $("#btnpdf").prop("href", "pdf-brand-search/"+$search);
                    }
                    else if($start_date == '' && $end_date != '' && $search != '')
                    {
                        $("#paging").hide();
                        $("#btnprint").prop("href", "print-brand/"+$end_date+"/"+$search);
                        $("#btnpdf").prop("href", "pdf-brand/"+$end_date+"/"+$search);
                    }
                    else if($start_date != '' && $end_date == '' && $search != '')
                    {
                        $("#paging").hide();
                        $("#btnprint").prop("href", "print-brand/"+$start_date+"/"+$search);
                        $("#btnpdf").prop("href", "pdf-brand/"+$start_date+"/"+$search);
                    }
                    else if($start_date == '' && $end_date != '' && $search == '')
                    {
                        $("#paging").hide();
                        $("#btnprint").prop("href", "print-brand/"+$end_date);
                        $("#btnpdf").prop("href", "pdf-brand/"+$end_date);
                    }
                $('tbody').html(data);
                }
            });
        });
    });
</script>
<script type="text/javascript">

$.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });

</script>
<script>
    //Datetimepicker plugin
    $('.datetimepicker').bootstrapMaterialDatePicker({
        format : 'YYYY-MM-DD',
        clearButton: true,
        time: false
    });
</script>

@endpush