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

                        <li class="breadcrumb-item">
                            <a href="#">Data Master</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="/admin/siswa">Data Siswa</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit Data Siswa
                        </li>

                    </ol>

                </nav>

            </div>

        </div>

    </div>

    <div class="pd-20 card-box mb-30">

        <div class="clearfix">

            <div class="pull-left">

                <h2 class="text-primary h2">

                    <i class="icon-copy dw dw-edit2"></i>
                    Edit Data Siswa

                </h2>

            </div>

            <div class="pull-right">

                <a href="/admin/siswa"
                    class="btn btn-primary btn-sm">

                    <i class="fa fa-arrow-left"></i>
                    Back

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

        <form action="/admin/siswa/update/{{ $siswa->id }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <!-- NIS -->
            <div class="form-group">

                <label>NIS *</label>

                <input type="text"
                    name="nis"
                    class="form-control"
                    value="{{ old('nis', $siswa->nis) }}"
                    required>

            </div>

            <!-- NAMA -->
            <div class="form-group">

                <label>Nama Siswa *</label>

                <input type="text"
                    name="nama"
                    class="form-control"
                    value="{{ old('nama', $siswa->nama) }}"
                    required>

            </div>

            <!-- KONTAK -->
            <div class="form-group">

                <label>Kontak *</label>

                <input type="text"
                    name="kontak"
                    class="form-control"
                    value="{{ old('kontak', $siswa->kontak) }}"
                    required>

            </div>

            <!-- ALAMAT -->
            <div class="form-group">

                <label>Alamat *</label>

                <input type="text"
                    name="alamat"
                    class="form-control"
                    value="{{ old('alamat', $siswa->alamat) }}"
                    required>

            </div>

            <!-- FOTO -->
            <div class="form-group">

                <label>Foto</label>

                <input type="file"
                    name="foto"
                    class="form-control">

            </div>

            <!-- PREVIEW FOTO -->
            @if($siswa->foto)

            <div class="form-group">

                <img src="{{ asset('storage/' . $siswa->foto) }}"
                    width="120"
                    class="img-thumbnail">

            </div>

            @endif

            <!-- SALDO -->
            <div class="form-group">

                <label>Saldo</label>

                <input type="number"
                    name="saldo"
                    class="form-control"
                    value="{{ old('saldo', $siswa->saldo) }}">

            </div>

            <!-- STATUS -->
            <div class="form-group">

                <label>Status *</label>

                <select name="status"
                    class="form-control"
                    required>

                    <option value="belum_terdaftar"
                        {{ $siswa->status == 'belum_terdaftar' ? 'selected' : '' }}>

                        Belum Terdaftar

                    </option>

                    <option value="terdaftar"
                        {{ $siswa->status == 'terdaftar' ? 'selected' : '' }}>

                        Terdaftar

                    </option>

                </select>

            </div>

            <button type="submit"
                class="btn btn-primary mt-2">

                <i class="ti-save"></i>
                Update Data

            </button>

        </form>

    </div>

</div>

@endsection