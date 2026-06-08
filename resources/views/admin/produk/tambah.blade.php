@extends('admin.layouts.app', [
'activePage' => 'produk',
])

@section('content')

<div class="min-height-200px">

    {{-- HEADER --}}
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Tambah Produk</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- CARD --}}
    <div class="pd-20 card-box mb-30">

        <form action="{{ route('admin.produk.store') }}" method="POST">
            @csrf

            {{-- NAMA PRODUK --}}
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text"
                       name="nama_produk"
                       class="form-control"
                       value="{{ old('nama_produk') }}"
                       required>
            </div>

            {{-- KATEGORI --}}
            <div class="form-group">
                <label>Kategori</label>
                <input type="text"
                       name="kategori"
                       class="form-control"
                       value="{{ old('kategori') }}">
            </div>

            {{-- HARGA --}}
            <div class="form-group">
                <label>Harga</label>
                <input type="number"
                       name="harga"
                       class="form-control"
                       value="{{ old('harga') }}"
                       required>
            </div>

            {{-- STOK --}}
            <div class="form-group">
                <label>Stok</label>
                <input type="number"
                       name="stok"
                       class="form-control"
                       value="{{ old('stok') }}"
                       required>
            </div>

            {{-- INFO QR --}}
            <div class="alert alert-info">
                <small>
                    <i class="fa fa-info-circle"></i>
                    Produk akan dibuat dalam status <b>menunggu scan ESP</b> untuk pengisian kode barang.
                </small>
            </div>

            <button class="btn btn-primary">
                <i class="fa fa-save"></i> Simpan Produk
            </button>

            <a href="{{ route('admin.produk.index') }}" class="btn btn-danger">
                Kembali
            </a>

        </form>

    </div>

</div>

@endsection