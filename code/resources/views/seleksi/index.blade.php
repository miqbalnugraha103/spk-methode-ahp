@extends('layouts.admin.frame')

@section('title', 'Daftar Seleksi')

@section('content')

    <ol class="breadcrumb breadcrumb-col-deep-purple">
        <li><a href="{{ url('/home') }}">Home</a></li>
        <li class="active">Daftar Seleksi</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Daftar Seleksi <span class="pull-right"><a href="{{ url('/seleksi/tambah') }}" class="btn bg-green waves-effect" title="Add New Color">
                                  <i class="fa fa-plus" aria-hidden="true"></i> Tambah Data</a></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="color_table" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">Tahun</th>
                                        <th width="35%">Seleksi (Nama Seleksi)</th>
                                        <th width="40%">Catatan</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>2015</td>
                                        <td>Seleksi Bagian Marketing</td>
                                        <td>Penyeleksian Bagian Marketing Cabang Baru</td>
                                        <td>
                                            <a href="{{ url('/seleksi/edit') }}" class="btn bg-cyan btn-sm waves-effect"><i class="fa fa-pencil-square-o"></i> Ubah </a>
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
                        url: '{{route("color.index")}}' + "/" + id + '?' + $.param({"_token" : '{{ csrf_token() }}' }),
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
