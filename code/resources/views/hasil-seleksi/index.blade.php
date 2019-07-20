@extends('layouts.admin.frame')

@section('title', 'Perbandingan Alternatif (Alternatif) Terhadap Kriteria')

@section('content')

    <ol class="breadcrumb breadcrumb-col-deep-purple">
        <li><a href="{{ url('/home') }}">Home</a></li>
        <li class="active">Perbandingan Alternatif (Alternatif) Terhadap Kriteria</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Hasil Seleksi Metode AHP</h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <h2 class="card-inside-title">Pilih Seleksi :</h2>
                        <select class="form-control show-tick" tabindex="-98">
                            <option value="">2015 - Seleksi Bagian Marketing</option>
                        </select>
                        <br>
                        <br>
                        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                            <button class="btn btn-primary btn-lg btn-block waves-effect" type="button" style="cursor:context-menu;">K01 <span class="badge">Kejujuran</span></button>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                            <button class="btn btn-primary btn-lg btn-block waves-effect" type="button" style="cursor:context-menu;">K02 <span class="badge">Daya tahan kerja</span></button>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                            <button class="btn btn-primary btn-lg btn-block waves-effect" type="button" style="cursor:context-menu;">K03 <span class="badge">Ketelitian</span></button>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                            <button class="btn btn-primary btn-lg btn-block waves-effect" type="button" style="cursor:context-menu;">K04 <span class="badge">Inisiatif</span></button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="colsor_table" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="45%">Alternatif</th>
                                        <th width="10%">k01</th>
                                        <th width="10%">k02</th>
                                        <th width="10%">k03</th>
                                        <th width="10%">k04</th>
                                        <th width="10%" style="color:#f30;">Nilai</th>
                                        <th width="10%" style="color:#448a47;">Rank</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th></th>
                                        <th style="color:#039;">Eigen Kriteria</th>
                                        <th style="color:#039;">0,136</th>
                                        <th style="color:#039;">0,231</th>
                                        <th style="color:#039;">0,067</th>
                                        <th style="color:#039;">0,566</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>A001 - Bagus Susanto</td>
                                        <td>0,174</td>
                                        <td>0,511</td>
                                        <td>0,212</td>
                                        <td>0,051</td>
                                        <th style="color:#F30;">0,185</th>
                                        <th class="text-center">3</th>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>A002 - Siti Zumrotul</td>
                                        <td>0,293</td>
                                        <td>0,035</td>
                                        <td>0,048</td>
                                        <td>0,397</td>
                                        <th style="color:#F30;">0,276</th>
                                        <th class="text-center">3</th>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>A003 - Iskandar</td>
                                        <td>0,044</td>
                                        <td>0,173</td>
                                        <td>0,422</td>
                                        <td>0,192</td>
                                        <th style="color:#F30;">0,183</th>
                                        <th class="text-center">4</th>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>A004 - Arif Prawiro</td>
                                        <td>0,489</td>
                                        <td>0,280</td>
                                        <td>0,319</td>
                                        <td>0,360</td>
                                        <th style="color:#F30;">0,356</th>
                                        <th class="text-center">1</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>Grafik Nilai Eigen Kriteria dan Hasil</h2>
                        <div class="body">
                            <canvas id="bar_chart" height="100" style="display: block; width: 528px; height: 264px;"></canvas>
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
                        labels: ["Iskandar", "Bagus Susanto", "Siti Zumrotul", "Arif Prawiro"],
                        datasets: [
                            {
                                label: "Kejujuran",
                                data: [0.04, 0.17, 0.29, 0.49],
                                backgroundColor: 'rgba(156, 39, 176, 0.8)'
                            },
                            {
                                label: "Daya tahan kerja",
                                data: [0.17, 0.51, 0.04, 0.28],
                                backgroundColor: 'rgba(51, 122, 183, 0.8)'
                            },
                            {
                                label: "Ketelitian",
                                data: [0.42, 0.21, 0.05, 0.32],
                                backgroundColor: 'rgba(121, 85, 72, 0.3)',
                            },
                            {
                                label: "Inisiatif",
                                data: [0.19, 0.05, 0.4, 0.36],
                                backgroundColor: 'rgba(3, 165, 244, 0.3)',
                            }
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
    <script>
        var oTable;
        oTable = $('#color_table').DataTable();
    </script>
@endpush
