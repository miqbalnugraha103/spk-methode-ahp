@extends('layouts.admin.frame')

@section('title', 'Report By Sales')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">Report By Sales</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <!-- Line Chart -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Chart By Sales</h2>
                        <div class="body">
                            <canvas id="bar_chart" height="100" style="display: block; width: 528px; height: 264px;"></canvas>
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
                                <h2>Report By Sales
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <p><a href="{{ URL('admin/report/pdf-sales-activity') }}" id="btnpdf" class="btn bg-primary" target="_blank">PDF</a>
                            <a href="{{ URL('admin/report/print-sales-activity') }}" id="btnprint" class="btn bg-primary" target="_blank">Print</a>
                            <a href="{{ URL('admin/report/excel-sales-activity/xlsx') }}" id="btnprint" class="btn bg-primary" target="_blank">Excel</a>
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
                                    <th width="2%">#</th><th>Sales Person</th><th>Company Name</th><th>Activity Status</th><th>Last Activity Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($quotelist as $no => $quote)
                                    <tr>
                                        <td>{!! ++$no !!}</td>
                                        <td>{!! $quote->name_sales !!}</td>
                                        <td>{!! $quote->company_name !!}</td>
                                        <td>
                                            @foreach($prospectHistory as $history)
                                                @if($quote->prospect_id == $history->prospect_id && $history->status_id == $quote->status_id && $history->status == 1 )
                                                    {{--<p><span class="label label-success">{!! $history->name_progress !!}&nbsp;-&nbsp;{!! date('d-F-Y H:s:i', strtotime($history->assignment_date)); !!}</span><p>--}}
                                                    <p>{!! $history->name_progress !!}&nbsp;-&nbsp;{!! date('d-F-Y H:s:i', strtotime($history->assignment_date)); !!}</p>
                                                @endif
                                                @if($quote->prospect_id == $history->prospect_id && $history->status != 1 )
                                                    {!! $history->name_progress !!}&nbsp;-&nbsp;{!! date('d-F-Y H:s:i', strtotime($history->assignment_date)); !!}<br>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{!! $quote->name_progress !!}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @if(count($quotelist) == 0)
                                    <tfoot>
                                    <tr>
                                        <th colspan="6">No data available in table</th>
                                    </tr>
                                    </tfoot>
                                @endif
                            </table>
                            <div id="paging">{{ $quotelist->links() }}</div>
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
            new Chart(document.getElementById("bar_chart").getContext("2d"), getChartJs('bar'));
        });

        function getChartJs(type) {
            var config = null;

            if (type === 'bar') {
                config = {
                    type: 'bar',
                    data: {
                        labels: ["September", "October", "November", "December", "January"],
                        datasets: [
                            {
                                label: "Assignment",
                                data: [5, 4, 11, 4, 6],
                                backgroundColor: 'rgba(91, 192, 222, 0.8)'
                            },
                            {
                                label: "Contact",
                                data: [2, 2, 3, 2, 3],
                                backgroundColor: 'rgba(51, 122, 183, 0.8)'
                            },
                            {
                                label: "Follow Up",
                                data: [2, 1, 0, 2, 2],
                                backgroundColor: 'rgba(121, 85, 72, 0.3)',
                            },
                            {
                                label: "Appointment",
                                data: [0, 1, 0, 0, 1],
                                backgroundColor: 'rgba(3, 165, 244, 0.3)',
                            },
                            {
                                label: "Visit",
                                data: [0, 0, 0, 0, 1],
                                backgroundColor: 'rgba(255, 152, 0, 0.3)',
                            },
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
    $('#search').on('keyup',function(){

        $("#proccesing").css("display","inline-block");

        $value=$(this).val();
        $.ajax({
            type : "get",
            url : "{{ URL('/admin/report/search-sales-activity') }}",
            data:{"search":$value},
            success:function(data){
                $("#proccesing").css("display","none");

                if($value == ''){
                    $("#paging").show();
                    $("#btnprint").prop("href", "{{ URL('admin/report/print-sales-activity') }}");
                    $("#btnpdf").prop("href", "{{ URL('admin/report/pdf-sales-activity') }}");
                }else{
                    $("#paging").hide();
                    $("#btnprint").prop("href", "print-sales-activity/"+$value);
                    $("#btnpdf").prop("href", "pdf-sales-activity/"+$value);
                }
            $('tbody').html(data);
            }
        });
    })
</script>
<script type="text/javascript">

$.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });

</script>
@endpush