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
                                <h2>Perbandingan Alternatif (Alternatif) Terhadap Kriteria</h2>
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
                        <h2 class="card-inside-title">Pilih Kriteria :</h2>
                        <select class="form-control show-tick" tabindex="-98">
                            <option value="">Kejujuran</option>
                            <option value="">Daya Tahan Kerja</option>
                            <option value="">Ketelitian</option>
                            <option value="">Inisiatif</option>
                        </select>
                        <br>
                        <br>
                        <br>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="colsor_table" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="15%">Alternatif</th>
                                        <th width="65%">Pilih Nilai</th>
                                        <th width="15%">Alternatif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Bagus Susanto</td>
                                        <td>
                                            <input name="nilai_1" type="radio" id="radio_1_a9" class="radio-col-light-blue">
                                            <label for="radio_1_a9">9</label>
                                            <input name="nilai_1" type="radio" id="radio_1_a8" class="radio-col-light-blue">
                                            <label for="radio_1_a8">8</label>
                                            <input name="nilai_1" type="radio" id="radio_1_a7" class="radio-col-light-blue">
                                            <label for="radio_1_a7">7</label>
                                            <input name="nilai_1" type="radio" id="radio_1_a6" class="radio-col-light-blue" checked="">
                                            <label for="radio_1_a6">6</label>
                                            <input name="nilai_1" type="radio" id="radio_1_a5" class="radio-col-light-blue">
                                            <label for="radio_1_a5">5</label>
                                            <input name="nilai_1" type="radio" id="radio_1_a4" class="radio-col-light-blue">
                                            <label for="radio_1_a4">4</label>
                                            <input name="nilai_1" type="radio" id="radio_1_a3" class="radio-col-light-blue">
                                            <label for="radio_1_a3">3</label>
                                            <input name="nilai_1" type="radio" id="radio_1_a2" class="radio-col-light-blue">
                                            <label for="radio_1_a2">2</label>
                                            <input name="nilai_1" type="radio" id="radio_1_1" class="radio-col-light-blue">
                                            <label for="radio_1_1">1</label>
                                            <input name="nilai_1" type="radio" id="radio_1_2" class="radio-col-light-blue">
                                            <label for="radio_1_2">2</label>
                                            <input name="nilai_1" type="radio" id="radio_1_3" class="radio-col-light-blue">
                                            <label for="radio_1_3">3</label>
                                            <input name="nilai_1" type="radio" id="radio_1_4" class="radio-col-light-blue">
                                            <label for="radio_1_4">4</label>
                                            <input name="nilai_1" type="radio" id="radio_1_5" class="radio-col-light-blue">
                                            <label for="radio_1_5">5</label>
                                            <input name="nilai_1" type="radio" id="radio_1_6" class="radio-col-light-blue">
                                            <label for="radio_1_6">6</label>
                                            <input name="nilai_1" type="radio" id="radio_1_7" class="radio-col-light-blue">
                                            <label for="radio_1_7">7</label>
                                            <input name="nilai_1" type="radio" id="radio_1_8" class="radio-col-light-blue">
                                            <label for="radio_1_8">8</label>
                                            <input name="nilai_1" type="radio" id="radio_1_9" class="radio-col-light-blue">
                                            <label for="radio_1_9">9</label>
                                        </td>
                                        <td>Siti Zumrotul</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Bagus Susanto</td>
                                        <td>
                                            <input name="nilai_2" type="radio" id="radio_2_a9" class="radio-col-light-blue">
                                            <label for="radio_2_a9">9</label>
                                            <input name="nilai_2" type="radio" id="radio_2_a8" class="radio-col-light-blue">
                                            <label for="radio_2_a8">8</label>
                                            <input name="nilai_2" type="radio" id="radio_2_a7" class="radio-col-light-blue">
                                            <label for="radio_2_a7">7</label>
                                            <input name="nilai_2" type="radio" id="radio_2_a6" class="radio-col-light-blue">
                                            <label for="radio_2_a6">6</label>
                                            <input name="nilai_2" type="radio" id="radio_2_a5" class="radio-col-light-blue">
                                            <label for="radio_2_a5">5</label>
                                            <input name="nilai_2" type="radio" id="radio_2_a4" class="radio-col-light-blue">
                                            <label for="radio_2_a4">4</label>
                                            <input name="nilai_2" type="radio" id="radio_2_a3" class="radio-col-light-blue">
                                            <label for="radio_2_a3">3</label>
                                            <input name="nilai_2" type="radio" id="radio_2_a2" class="radio-col-light-blue">
                                            <label for="radio_2_a2">2</label>
                                            <input name="nilai_2" type="radio" id="radio_2_1" class="radio-col-light-blue" checked="">
                                            <label for="radio_2_1">1</label>
                                            <input name="nilai_2" type="radio" id="radio_2_2" class="radio-col-light-blue">
                                            <label for="radio_2_2">2</label>
                                            <input name="nilai_2" type="radio" id="radio_2_3" class="radio-col-light-blue">
                                            <label for="radio_2_3">3</label>
                                            <input name="nilai_2" type="radio" id="radio_2_4" class="radio-col-light-blue">
                                            <label for="radio_2_4">4</label>
                                            <input name="nilai_2" type="radio" id="radio_2_5" class="radio-col-light-blue">
                                            <label for="radio_2_5">5</label>
                                            <input name="nilai_2" type="radio" id="radio_2_6" class="radio-col-light-blue">
                                            <label for="radio_2_6">6</label>
                                            <input name="nilai_2" type="radio" id="radio_2_7" class="radio-col-light-blue">
                                            <label for="radio_2_7">7</label>
                                            <input name="nilai_2" type="radio" id="radio_2_8" class="radio-col-light-blue">
                                            <label for="radio_2_8">8</label>
                                            <input name="nilai_2" type="radio" id="radio_2_9" class="radio-col-light-blue">
                                            <label for="radio_2_9">9</label>
                                        </td>
                                        <td>Iskandar</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Bagus Susanto</td>
                                        <td>
                                            <input name="nilai_3" type="radio" id="radio_3_a9" class="radio-col-light-blue">
                                            <label for="radio_3_a9">9</label>
                                            <input name="nilai_3" type="radio" id="radio_3_a8" class="radio-col-light-blue">
                                            <label for="radio_3_a8">8</label>
                                            <input name="nilai_3" type="radio" id="radio_3_a7" class="radio-col-light-blue">
                                            <label for="radio_3_a7">7</label>
                                            <input name="nilai_3" type="radio" id="radio_3_a6" class="radio-col-light-blue">
                                            <label for="radio_3_a6">6</label>
                                            <input name="nilai_3" type="radio" id="radio_3_a5" class="radio-col-light-blue">
                                            <label for="radio_3_a5">5</label>
                                            <input name="nilai_3" type="radio" id="radio_3_a4" class="radio-col-light-blue">
                                            <label for="radio_3_a4">4</label>
                                            <input name="nilai_3" type="radio" id="radio_3_a3" class="radio-col-light-blue">
                                            <label for="radio_3_a3">3</label>
                                            <input name="nilai_3" type="radio" id="radio_3_a2" class="radio-col-light-blue">
                                            <label for="radio_3_a2">2</label>
                                            <input name="nilai_3" type="radio" id="radio_3_1" class="radio-col-light-blue">
                                            <label for="radio_3_1">1</label>
                                            <input name="nilai_3" type="radio" id="radio_3_2" class="radio-col-light-blue">
                                            <label for="radio_3_2">2</label>
                                            <input name="nilai_3" type="radio" id="radio_3_3" class="radio-col-light-blue">
                                            <label for="radio_3_3">3</label>
                                            <input name="nilai_3" type="radio" id="radio_3_4" class="radio-col-light-blue">
                                            <label for="radio_3_4">4</label>
                                            <input name="nilai_3" type="radio" id="radio_3_5" class="radio-col-light-blue">
                                            <label for="radio_3_5">5</label>
                                            <input name="nilai_3" type="radio" id="radio_3_6" class="radio-col-light-blue">
                                            <label for="radio_3_6">6</label>
                                            <input name="nilai_3" type="radio" id="radio_3_7" class="radio-col-light-blue" checked="">
                                            <label for="radio_3_7">7</label>
                                            <input name="nilai_3" type="radio" id="radio_3_8" class="radio-col-light-blue">
                                            <label for="radio_3_8">8</label>
                                            <input name="nilai_3" type="radio" id="radio_3_9" class="radio-col-light-blue">
                                            <label for="radio_3_9">9</label>
                                        </td>
                                        <td>Arif Prawiro</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Siti Zumrotul</td>
                                        <td>
                                            <input name="nilai_4" type="radio" id="radio_4_a9" class="radio-col-light-blue">
                                            <label for="radio_4_a9">9</label>
                                            <input name="nilai_4" type="radio" id="radio_4_a8" class="radio-col-light-blue">
                                            <label for="radio_4_a8">8</label>
                                            <input name="nilai_4" type="radio" id="radio_4_a7" class="radio-col-light-blue">
                                            <label for="radio_4_a7">7</label>
                                            <input name="nilai_4" type="radio" id="radio_4_a6" class="radio-col-light-blue">
                                            <label for="radio_4_a6">6</label>
                                            <input name="nilai_4" type="radio" id="radio_4_a5" class="radio-col-light-blue">
                                            <label for="radio_4_a5">5</label>
                                            <input name="nilai_4" type="radio" id="radio_4_a4" class="radio-col-light-blue">
                                            <label for="radio_4_a4">4</label>
                                            <input name="nilai_4" type="radio" id="radio_4_a3" class="radio-col-light-blue">
                                            <label for="radio_4_a3">3</label>
                                            <input name="nilai_4" type="radio" id="radio_4_a2" class="radio-col-light-blue">
                                            <label for="radio_4_a2">2</label>
                                            <input name="nilai_4" type="radio" id="radio_4_1" class="radio-col-light-blue">
                                            <label for="radio_4_1">1</label>
                                            <input name="nilai_4" type="radio" id="radio_4_2" class="radio-col-light-blue" checked="">
                                            <label for="radio_4_2">2</label>
                                            <input name="nilai_4" type="radio" id="radio_4_3" class="radio-col-light-blue">
                                            <label for="radio_4_3">3</label>
                                            <input name="nilai_4" type="radio" id="radio_4_4" class="radio-col-light-blue">
                                            <label for="radio_4_4">4</label>
                                            <input name="nilai_4" type="radio" id="radio_4_5" class="radio-col-light-blue">
                                            <label for="radio_4_5">5</label>
                                            <input name="nilai_4" type="radio" id="radio_4_6" class="radio-col-light-blue">
                                            <label for="radio_4_6">6</label>
                                            <input name="nilai_4" type="radio" id="radio_4_7" class="radio-col-light-blue">
                                            <label for="radio_4_7">7</label>
                                            <input name="nilai_4" type="radio" id="radio_4_8" class="radio-col-light-blue">
                                            <label for="radio_4_8">8</label>
                                            <input name="nilai_4" type="radio" id="radio_4_9" class="radio-col-light-blue">
                                            <label for="radio_4_9">9</label>
                                        </td>
                                        <td>Iskandar</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Siti Zumrotul</td>
                                        <td>
                                            <input name="nilai_5" type="radio" id="radio_5_a9" class="radio-col-light-blue">
                                            <label for="radio_5_a9">9</label>
                                            <input name="nilai_5" type="radio" id="radio_5_a8" class="radio-col-light-blue">
                                            <label for="radio_5_a8">8</label>
                                            <input name="nilai_5" type="radio" id="radio_5_a7" class="radio-col-light-blue">
                                            <label for="radio_5_a7">7</label>
                                            <input name="nilai_5" type="radio" id="radio_5_a6" class="radio-col-light-blue">
                                            <label for="radio_5_a6">6</label>
                                            <input name="nilai_5" type="radio" id="radio_5_a5" class="radio-col-light-blue">
                                            <label for="radio_5_a5">5</label>
                                            <input name="nilai_5" type="radio" id="radio_5_a4" class="radio-col-light-blue">
                                            <label for="radio_5_a4">4</label>
                                            <input name="nilai_5" type="radio" id="radio_5_a3" class="radio-col-light-blue">
                                            <label for="radio_5_a3">3</label>
                                            <input name="nilai_5" type="radio" id="radio_5_a2" class="radio-col-light-blue">
                                            <label for="radio_5_a2">2</label>
                                            <input name="nilai_5" type="radio" id="radio_5_1" class="radio-col-light-blue">
                                            <label for="radio_5_1">1</label>
                                            <input name="nilai_5" type="radio" id="radio_5_2" class="radio-col-light-blue">
                                            <label for="radio_5_2">2</label>
                                            <input name="nilai_5" type="radio" id="radio_5_3" class="radio-col-light-blue" checked="">
                                            <label for="radio_5_3">3</label>
                                            <input name="nilai_5" type="radio" id="radio_5_4" class="radio-col-light-blue">
                                            <label for="radio_5_4">4</label>
                                            <input name="nilai_5" type="radio" id="radio_5_5" class="radio-col-light-blue">
                                            <label for="radio_5_5">5</label>
                                            <input name="nilai_5" type="radio" id="radio_5_6" class="radio-col-light-blue">
                                            <label for="radio_5_6">6</label>
                                            <input name="nilai_5" type="radio" id="radio_5_7" class="radio-col-light-blue">
                                            <label for="radio_5_7">7</label>
                                            <input name="nilai_5" type="radio" id="radio_5_8" class="radio-col-light-blue">
                                            <label for="radio_5_8">8</label>
                                            <input name="nilai_5" type="radio" id="radio_5_9" class="radio-col-light-blue">
                                            <label for="radio_5_9">9</label>
                                        </td>
                                        <td>Arif Prawiro</td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>Iskandar</td>
                                        <td>
                                            <input name="nilai_6" type="radio" id="radio_6_a9" class="radio-col-light-blue">
                                            <label for="radio_6_a9">9</label>
                                            <input name="nilai_6" type="radio" id="radio_6_a8" class="radio-col-light-blue">
                                            <label for="radio_6_a8">8</label>
                                            <input name="nilai_6" type="radio" id="radio_6_a7" class="radio-col-light-blue">
                                            <label for="radio_6_a7">7</label>
                                            <input name="nilai_6" type="radio" id="radio_6_a6" class="radio-col-light-blue">
                                            <label for="radio_6_a6">6</label>
                                            <input name="nilai_6" type="radio" id="radio_6_a5" class="radio-col-light-blue">
                                            <label for="radio_6_a5">5</label>
                                            <input name="nilai_6" type="radio" id="radio_6_a4" class="radio-col-light-blue">
                                            <label for="radio_6_a4">4</label>
                                            <input name="nilai_6" type="radio" id="radio_6_a3" class="radio-col-light-blue">
                                            <label for="radio_6_a3">3</label>
                                            <input name="nilai_6" type="radio" id="radio_6_a2" class="radio-col-light-blue">
                                            <label for="radio_6_a2">2</label>
                                            <input name="nilai_6" type="radio" id="radio_6_1" class="radio-col-light-blue" checked="">
                                            <label for="radio_6_1">1</label>
                                            <input name="nilai_6" type="radio" id="radio_6_2" class="radio-col-light-blue">
                                            <label for="radio_6_2">2</label>
                                            <input name="nilai_6" type="radio" id="radio_6_3" class="radio-col-light-blue">
                                            <label for="radio_6_3">3</label>
                                            <input name="nilai_6" type="radio" id="radio_6_4" class="radio-col-light-blue">
                                            <label for="radio_6_4">4</label>
                                            <input name="nilai_6" type="radio" id="radio_6_5" class="radio-col-light-blue">
                                            <label for="radio_6_5">5</label>
                                            <input name="nilai_6" type="radio" id="radio_6_6" class="radio-col-light-blue">
                                            <label for="radio_6_6">6</label>
                                            <input name="nilai_6" type="radio" id="radio_6_7" class="radio-col-light-blue">
                                            <label for="radio_6_7">7</label>
                                            <input name="nilai_6" type="radio" id="radio_6_8" class="radio-col-light-blue">
                                            <label for="radio_6_8">8</label>
                                            <input name="nilai_6" type="radio" id="radio_6_9" class="radio-col-light-blue">
                                            <label for="radio_6_9">9</label>
                                        </td>
                                        <td>Arif Prawiro</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="form-group">
                                {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Tambah Data', ['class' => 'btn bg-green waves-effect']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Nilai Perbandingan</h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="colsor_table" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="45%">Kriteria</th>
                                        <th width="10%">A001</th>
                                        <th width="10%">A002</th>
                                        <th width="10%">A003</th>
                                        <th width="10%">A004</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>K01 - Kejujuran</td>
                                        <td>1,00</td>
                                        <td>0,50</td>
                                        <td>3,00</td>
                                        <td>0,20</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>K02 - Daya tahan kerja</td>
                                        <td>2,00</td>
                                        <td>1,00</td>
                                        <td>5,00</td>
                                        <td>0,25</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>K03 - Ketelitian</td>
                                        <td>0,33</td>
                                        <td>0,20</td>
                                        <td>1,00</td>
                                        <td>0,20</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>K04 - Inisiatif</td>
                                        <td>5,00</td>
                                        <td>4,00</td>
                                        <td>5,00</td>
                                        <td>1,00</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>Jumlah</th>
                                        <th>8,33</th>
                                        <th>5,70</th>
                                        <th>14,00</th>
                                        <th>1,65</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Normalisasi Dan Nilai Eigen</h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="colsor_table" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="35%">Kriteria</th>
                                        <th width="10%">K01</th>
                                        <th width="10%">K02</th>
                                        <th width="10%">K03</th>
                                        <th width="10%">K04</th>
                                        <th width="10%">Eigen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>K01 - Kejujuran</td>
                                        <td>0,120</td>
                                        <td>0,088</td>
                                        <td>0,214</td>
                                        <td>0,121</td>
                                        <th>0,136</th>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>K02 - Daya tahan kerja</td>
                                        <td>0,240</td>
                                        <td>0,175</td>
                                        <td>0,357</td>
                                        <td>0,152</td>
                                        <th>0,231</th>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>K03 - Ketelitian</td>
                                        <td>0,040</td>
                                        <td>0,035</td>
                                        <td>0,071</td>
                                        <td>0,121</td>
                                        <th>0,067</th>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>K04 - Inisiatif</td>
                                        <td>0,600</td>
                                        <td>0,702</td>
                                        <td>0,357</td>
                                        <td>0,606</td>
                                        <th>0,566</th>
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
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Cek Konsistensi</h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="colsor_table" width="100%">
                                <thead>
                                    <tr>
                                        <th align="center" width="100%" colspan="3">Hasil Cek Nilai Konsistensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>(A)(W^t)</td>
                                        <td>:</td>
                                        <td>[0,5654] [0,9789] [0,2717] [2,5040]</td>
                                    </tr>
                                    <tr>
                                        <td>t</td>
                                        <td>:</td>
                                        <td>4,2202</td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Index Konsistensi (CI)</td>
                                        <td>:</td>
                                        <td>0,0734</td>
                                    </tr>
                                    <tr>
                                        <td>Rasio Konsistensi</td>
                                        <td>:</td>
                                        <td>0,0816</td>
                                    </tr>
                                    <tr>
                                        <th>Hasil Konsistensi</th>
                                        <th>:</th>
                                        <th>KONSISTEN</th>
                                    </tr>
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
        oTable = $('#color_table').DataTable();
    </script>
@endpush
