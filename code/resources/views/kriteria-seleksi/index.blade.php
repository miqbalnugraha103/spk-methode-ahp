@extends('layouts.admin.frame')

@section('title', 'Daftar Kriteria Setiap Seleksi')

@section('content')

    <ol class="breadcrumb breadcrumb-col-deep-purple">
        <li><a href="{{ url('/home') }}">Home</a></li>
        <li class="active">Daftar Kriteria Setiap Seleksi</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Daftar Kriteria Setiap Seleksi <span class="pull-right"><a href="{{ url('/kriteria-seleksi/tambah') }}" class="btn bg-green waves-effect" title="Add New Color">
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
                                        <th width="80%">Nama Kriteria</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Kejujuran</td>
                                        <td>
                                            <a onclick="deleteData()" class="btn bg-red btn-sm waves-effect"><i class="fa fa-trash-o"></i> Hapus </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Daya tahan kerja</td>
                                        <td>
                                            <a onclick="deleteData()" class="btn bg-red btn-sm waves-effect"><i class="fa fa-trash-o"></i> Hapus </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Ketelitian</td>
                                        <td>
                                            <a onclick="deleteData()" class="btn bg-red btn-sm waves-effect"><i class="fa fa-trash-o"></i> Hapus </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Inisiatif</td>
                                        <td>
                                            <a onclick="deleteData()" class="btn bg-red btn-sm waves-effect"><i class="fa fa-trash-o"></i> Hapus </a>
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
                        url: 'kriteria-seleksi' + "/" + id + '?' + $.param({"_token" : '{{ csrf_token() }}' }),
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
