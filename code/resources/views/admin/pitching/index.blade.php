@extends('layouts.admin.frame')

@section('title', 'Pitching')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">Pitching</li>
    </ol>

    <div class="container-fluid">
                <!-- #END# Line Chart -->
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Pitching (Sales Person)<!--<span class="pull-right"><a href="{{ url('/admin/prospect') }}" class="btn bg-deep-purple waves-effect" title="Back">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></span>-->
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-4">
                                <a href="{{ url('/admin/sales-progress/1') }}" style="text-decoration: none">
                                    <div class="info-box bg-indigo hover-expand-effect" style="cursor: pointer;">
                                        <div class="icon">
                                            <i class="material-icons">assignment_turned_in</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">
                                                @foreach($statusprogress as $pit)
                                                    @if($pit->id == '1')
                                                    {!! $pit->name_progress !!}
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="number count-to" data-from="0" data-to="{{ isset($salesprogress1) ? $salesprogress1 : '' }}" data-speed="1000" data-fresh-interval="20">{{ isset($salesprogress1) ? $salesprogress1 : '' }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-4">
                                <a href="{{ url('/admin/sales-progress/2') }}" style="text-decoration: none">
                                    <div class="info-box bg-teal hover-expand-effect" style="cursor: pointer;">
                                        <div class="icon">
                                            <i class="material-icons">assignment_turned_in</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">
                                                @foreach($statusprogress as $pit)
                                                    @if($pit->id == '2')
                                                    {!! $pit->name_progress !!}
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="number count-to" data-from="0" data-to="{{ isset($salesprogress2) ? $salesprogress2 : '' }}" data-speed="1000" data-fresh-interval="20">{{ isset($salesprogress2) ? $salesprogress2 : '' }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-4">
                                <a href="{{ url('/admin/sales-progress/3') }}" style="text-decoration: none">
                                    <div class="info-box bg-brown hover-expand-effect" style="cursor: pointer;">
                                        <div class="icon">
                                            <i class="material-icons">assignment_turned_in</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">
                                                @foreach($statusprogress as $pit)
                                                    @if($pit->id == '3')
                                                    {!! $pit->name_progress !!}
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="number count-to" data-from="0" data-to="{{ isset($salesprogress3) ? $salesprogress3 : '' }}" data-speed="1000" data-fresh-interval="20">{{ isset($salesprogress3) ? $salesprogress3 : '' }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-4">
                                <a href="{{ url('/admin/sales-progress/4') }}" style="text-decoration: none">
                                    <div class="info-box bg-light-blue hover-expand-effect" style="cursor: pointer;">
                                        <div class="icon">
                                            <i class="material-icons">assignment_turned_in</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">
                                                @foreach($statusprogress as $pit)
                                                    @if($pit->id == '4')
                                                    {!! $pit->name_progress !!}
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="number count-to" data-from="0" data-to="{{ isset($salesprogress4) ? $salesprogress4 : '' }}" data-speed="1000" data-fresh-interval="20">{{ isset($salesprogress4) ? $salesprogress4 : '' }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-4">
                                <a href="{{ url('/admin/sales-progress/5') }}" style="text-decoration: none">
                                    <div class="info-box bg-orange hover-expand-effect" style="cursor: pointer;">
                                        <div class="icon">
                                            <i class="material-icons">assignment_turned_in</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">
                                                @foreach($statusprogress as $pit)
                                                    @if($pit->id == '5')
                                                    {!! $pit->name_progress !!}
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="number count-to" data-from="0" data-to="{{ isset($salesprogress5) ? $salesprogress5 : '' }}" data-speed="1000" data-fresh-interval="20">{{ isset($salesprogress5) ? $salesprogress5 : '' }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="progress-sales-table" width="100%">
                                <thead>
                                <tr>
                                    <th>No</th><th>Sales Person</th><th>Company Name</th><th style="width: 40%">Person Notes</th><th width="5%">Action</th>
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

            @if(isset($id))
                window.scrollBy(0, 300);
            $('html, body').animate({
                   scrollTop: $("#progress-sales-table").offset().top
                }, 1000);
            @endif
        });
    </script>
    <script>
        var oTable;
        oTable = $('#progress-sales-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            scrollX : false,
           dom : '<"row"<"col-sm-12"Bf><"col-sm-12"l><"col-sm-12"t><"col-sm-12"ip>>',
           buttons: [

               @if(isset($id))
                   @if($id == 1)
                       {
                            extend      : 'excel',
                            filename    : 'Pitching-Assignment',
                            text        : 'Excel Assignment',
                            className   : 'bg-indigo'
                       },
                   @elseif($id == 2)
                       {
                            extend      : 'excel',
                            filename    : 'Pitching-Contact',
                            text        : 'Excel Contact',
                            className   : 'bg-teal'
                       },
                   @elseif($id == 3)
                       {
                            extend      : 'excel',
                            filename    : 'Pitching-FollowUp',
                            text        : 'Excel FollowUp',
                            className   : 'bg-brown'
                       },
                   @elseif($id == 4)
                       {
                            extend      : 'excel',
                            filename    : 'Pitching-Appointment',
                            text        : 'Excel Appointment',
                            className   : 'bg-light-blue'
                       },
                   @elseif($id == 5)
                       {
                            extend      : 'excel',
                            filename    : 'Pitching-Visit',
                            text        : 'Excel Visit',
                            className   : 'bg-orange'
                       },
                   @endif
               @else
                   {
                       extend: 'excel',
                       filename: 'Pitching',
                       text: 'Excel Pitching',
                   },
               @endif

           ],
            @if(isset($id))
                ajax: '{!! route('sales-progress.data', ['id' => $id]) !!}',
            @else
                iDisplayLength:-1,
                ajax: '{!! route('sales-progress-all.data') !!}',
            @endif
            columns: [
                { data: "rownum", name: "rownum" },
                { data: 'name_sales', name: 'name_sales' },
                { data: 'company_name', name: 'company_name' },
                { data: 'notes', name: 'notes', className: 'notes_pitching_warp'},
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

    </script>
@endpush