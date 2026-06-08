@extends('admin.layouts.app', [
'activePage' => 'produk',
])

@section('content')

<div class="min-height-200px">

    <div class="page-header">

        <div class="row">

            <div class="col-md-6 col-sm-12">

                <div class="title">
                    <h4>Edit Produk</h4>
                </div>

            </div>

        </div>

    </div>

    <div class="pd-20 card-box mb-30">

        <form action="{{ route('admin.produk.update', $produk->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            {{-- KODE --}}
            <div class="form-group">

                <label>Kode Barang</label>

                <input type="text"
                       class="form-control"
                       value="{{ $produk->kode_barang }}"
                       readonly>

            </div>

            {{-- NAMA --}}
            <div class="form-group">

                <label>Nama Produk</label>

                <input type="text"
                       name="nama_produk"
                       class="form-control"
                       value="{{ $produk->nama_produk }}"
                       required>

            </div>

            {{-- KATEGORI --}}
            <div class="form-group">

                <label>Kategori</label>

                <input type="text"
                       name="kategori"
                       class="form-control"
                       value="{{ $produk->kategori }}">

            </div>

            {{-- HARGA --}}
            <div class="form-group">

                <label>Harga</label>

                <input type="number"
                       name="harga"
                       class="form-control"
                       value="{{ $produk->harga }}"
                       required>

            </div>

            {{-- STOK --}}
            <div class="form-group">

                <label>Stok</label>

                <input type="number"
                       name="stok"
                       class="form-control"
                       value="{{ $produk->stok }}"
                       required>

            </div>

            <button class="btn btn-primary">

                <i class="fa fa-save"></i>
                Update

            </button>

            <a href="/admin/produk"
               class="btn btn-danger">

                Kembali

            </a>

        </form>

    </div>

</div>

@endsection