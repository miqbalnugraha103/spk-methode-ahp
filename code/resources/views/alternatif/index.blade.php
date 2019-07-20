@extends('layouts.admin.frame')

@section('title', 'Daftar Alternatif (Alternatif)')

@section('content')

    <ol class="breadcrumb breadcrumb-col-deep-purple">
        <li><a href="{{ url('/home') }}">Home</a></li>
        <li class="active">Daftar Alternatif (Alternatif)</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Daftar Alternatif (Alternatif) <span class="pull-right"><a href="{{ url('/alternatif/tambah') }}" class="btn bg-green waves-effect" title="Add New Color">
                                  <i class="fa fa-plus" aria-hidden="true"></i> Tambah Data</a></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <h2 class="card-inside-title">Seleksi :</h2>
                        <select class="form-control show-tick" tabindex="-98">
                            <option value="">2015 - Seleksi Bagian Marketing</option>
                        </select>
                        <br>
                        <br>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="colsor_table" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="25%">Alternatif</th>
                                        <th width="35%">Catatan</th>
                                        <th width="20%">Tgl Terdaftar</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Bagus Susanto</td>
                                        <td>-</td>
                                        <td>24/04/2015</td>
                                        <td>
                                            <a href="{{ url('/alternatif/edit') }}" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Ubah </a>
                                            <a onclick="deleteData()" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Hapus </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Siti Zumrotul</td>
                                        <td>-</td>
                                        <td>24/04/2015</td>
                                        <td>
                                            <a href="{{ url('/alternatif/edit') }}" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Ubah </a>
                                            <a onclick="deleteData()" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Hapus </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Iskandar</td>
                                        <td>-</td>
                                        <td>24/04/2015</td>
                                        <td>
                                            <a href="{{ url('/alternatif/edit') }}" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Ubah </a>
                                            <a onclick="deleteData()" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Hapus </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Arif Prawiro</td>
                                        <td>-</td>
                                        <td>24/04/2015</td>
                                        <td>
                                            <a href="{{ url('/alternatif/edit') }}" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Ubah </a>
                                            <a onclick="deleteData()" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Hapus </a>
                                        </td>
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

        function deleteData(id) {
            swal({
                title: "Apa kamu yakin?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: "POST",
                        url: 'alternatif' + "/" + id + '?' + $.param({"_token" : '{{ csrf_token() }}' }),
                        data: {_method: 'delete'},
                         complete: function (msg) {
                            oTable.draw();
                            swal("Success", "Data berhasil dihapus", "success");
                        }
                    });
                }else{
                    swal("Success", "data tidak terhapus", "success");
                }
            });
        }
    </script>
@endpush
