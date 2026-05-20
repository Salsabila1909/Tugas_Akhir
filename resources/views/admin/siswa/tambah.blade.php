@extends('admin.layouts.app', [
'activePage' => 'siswa',
])

@section('content')

<div class="min-height-200px">

    <div class="page-header">
        <div class="row">

            <div class="col-md-12 col-sm-12">

                <div class="title">
                    <h4>Data Siswa</h4>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Data Siswa</li>
                        <li class="breadcrumb-item">
                            <a href="/admin/siswa">Pendaftaran Autentikasi</a>
                        </li>
                        <li class="breadcrumb-item active">Tambah Data</li>
                    </ol>
                </nav>

            </div>

        </div>
    </div>

    <div class="pd-20 card-box mb-30">

        <div class="clearfix">
            <div class="pull-left">
                <h2 class="text-primary h2">
                    <i class="icon-copy dw dw-add-user"></i>
                    Tambah Data Siswa
                </h2>
            </div>

            <div class="pull-right">
                <a href="/admin/siswa" class="btn btn-primary btn-sm">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <hr>

        {{-- ERROR VALIDATION --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        
        <form action="{{ route('admin.siswa.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <!-- NIS -->
            <div class="form-group">
                <label>NIS *</label>
                <input type="text"
                       name="nis"
                       class="form-control"
                       value="{{ old('nis') }}"
                       required>
            </div>

            <!-- Nama -->
            <div class="form-group">
                <label>Nama Siswa *</label>
                <input type="text"
                       name="nama"
                       class="form-control"
                       value="{{ old('nama') }}"
                       required>
            </div>

            <!-- Kontak -->
            <div class="form-group">
                <label>Kontak *</label>
                <input type="text"
                       name="kontak"
                       class="form-control"
                       value="{{ old('kontak') }}"
                       required>
            </div>

            <!-- Alamat -->
             <div class="form-group">
                <label>Alamat *</label>
                <input type="text"
                       name="alamat"
                       class="form-control"
                       value="{{ old('alamat') }}"
                       required>
            </div>

            <!-- Foto -->
            <div class="form-group">
                <label>Foto</label>
                <input type="file"
                       name="foto"
                       class="form-control">
            </div>

            <!-- Saldo -->
            <div class="form-group">
                <label>Saldo Awal</label>
                <input type="number"
                       name="saldo"
                       class="form-control"
                       value="{{ old('saldo', 0) }}">
            </div>


            <button type="submit"
                    class="btn btn-primary mt-2">

                <i class="ti-save"></i> Tambah Data

            </button>

        </form>

    </div>

</div>

@endsection