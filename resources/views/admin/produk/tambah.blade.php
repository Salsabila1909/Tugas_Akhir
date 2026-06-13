@extends('admin.layouts.app', [
'activePage' => 'produk',
])

@section('content')

<div class="min-height-200px">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="row">

            <div class="col-md-12 col-sm-12">

                <div class="title">
                    <h4>Tambah Produk</h4>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Produk</li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.produk.index') }}">Data Produk</a>
                        </li>
                        <li class="breadcrumb-item active">Tambah Produk</li>
                    </ol>
                </nav>

            </div>

        </div>
    </div>

    <!-- CARD -->
    <div class="pd-20 card-box mb-30">

        <div class="clearfix">
            <div class="pull-left">
                <h2 class="text-primary h2">
                    <i class="fa fa-box"></i>
                    Tambah Produk
                </h2>
            </div>

            <div class="pull-right">
                <a href="{{ route('admin.produk.index') }}" class="btn btn-primary btn-sm">
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


        <form action="{{ route('admin.produk.store') }}" method="POST">

            @csrf

            <!-- NAMA PRODUK -->
            <div class="form-group">
                <label>Nama Produk *</label>
                <input type="text"
                       name="nama_produk"
                       class="form-control"
                       value="{{ old('nama_produk') }}"
                       required>
            </div>

            <!-- KATEGORI -->
            <div class="form-group">
                <label>Kategori</label>
                <input type="text"
                       name="kategori"
                       class="form-control"
                       value="{{ old('kategori') }}">
            </div>

            <!-- HARGA -->
            <div class="form-group">
                <label>Harga *</label>
                <input type="number"
                       name="harga"
                       class="form-control"
                       value="{{ old('harga') }}"
                       required>
            </div>

            <!-- STOK -->
            <div class="form-group">
                <label>Stok *</label>
                <input type="number"
                       name="stok"
                       class="form-control"
                       value="{{ old('stok') }}"
                       required>
            </div>

            <!-- INFO -->
            <div class="alert alert-info text-center">
                <i class="fa fa-info-circle"></i>
                Produk akan dibuat dalam status <b>menunggu scan ESP</b>
                untuk pengisian kode barang otomatis.
            </div>

            <!-- BUTTON -->
            <button type="submit" class="btn btn-primary mt-3">
                <i class="fa fa-save"></i>
                Simpan Produk
            </button>

        </form>

    </div>

</div>

@endsection