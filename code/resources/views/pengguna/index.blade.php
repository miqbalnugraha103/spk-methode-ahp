@extends('layouts.admin.frame')

@section('title', 'Daftar Pengguna Sistem')

@section('content')

    <ol class="breadcrumb breadcrumb-col-blue">
        <li><a href="{{ url('/admin') }}">Home</a></li>
        <li class="active">Daftar Pengguna Sistem</li>
    </ol>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12">
                                <h2>Daftar Pengguna Sistem <span class="pull-right"><a href="{{ url('/pengguna/tambah') }}" class="btn bg-green waves-effect" title="Tambah">
                                  <i class="fa fa-plus" aria-hidden="true"></i> Tambah</a></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="pengguna-table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>No Telp</th>
                                        <th>Hak Akses</th>
                                        <th width="20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Admin</td>
                                        <td>Admin Sistem</td>
                                        <td>081904013089</td>
                                        <td>Administrator</td>
                                        <td>
                                            <a href="{{ url('/kriteria/edit') }}" class="btn bg-cyan btn-sm waves-effect"><i class="fa fa-pencil-square-o"></i> Ubah </a>
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
        oTable = $('#pengguna-table').DataTable();

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
                        url: 'pengguna' + "/" + id + '?' + $.param({"_token" : '{{ csrf_token() }}' }),
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
