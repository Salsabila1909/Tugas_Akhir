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
                    <h4>Data Produk</h4>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Produk</a></li>
                        <li class="breadcrumb-item active">Data Produk</li>
                    </ol>
                </nav>

            </div>

        </div>
    </div>

    {{-- CARD --}}
    <div class="pd-20 card-box mb-30">

        <div class="clearfix">

            <div class="pull-left">
                <h2 class="text-primary h2">
                    <i class="fa fa-box"></i>
                    List Data Produk
                </h2>
            </div>

            <div class="pull-right">
                <a href="{{ route('admin.produk.add') }}"
                   class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tambah Data
                </a>
            </div>

        </div>

        <hr>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        {{-- TABLE --}}
        <table class="table table-striped table-bordered data-table hover">

            <thead class="bg-primary text-white">

                <tr>
                    <th width="5%">#</th>
                    <th>Kode Barang</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th width="20%" class="text-center">Action</th>
                </tr>

            </thead>

            <tbody>

                @php $no = 1; @endphp

                @foreach($produk as $data)

                <tr>

                    <td class="text-center">
                        {{ $no++ }}
                    </td>

                    {{-- KODE BARANG --}}
                    <td>
                        @if($data->kode_barang)
                            <span class="badge badge-primary">
                                {{ $data->kode_barang }}
                            </span>
                        @else
                            <span class="badge badge-danger">
                                NULL
                            </span>
                        @endif
                    </td>

                    {{-- NAMA --}}
                    <td>{{ $data->nama_produk }}</td>

                    {{-- KATEGORI --}}
                    <td>{{ $data->kategori ?? '-' }}</td>

                    {{-- HARGA --}}
                    <td>
                        Rp {{ number_format($data->harga,0,',','.') }}
                    </td>

                    {{-- STOK --}}
                    <td>
                        @if($data->stok <= 5)
                            <span class="badge badge-danger">
                                {{ $data->stok }}
                            </span>
                        @else
                            <span class="badge badge-success">
                                {{ $data->stok }}
                            </span>
                        @endif
                    </td>

                    {{-- STATUS IOT --}}
                    <td class="text-center">

    @if($data->kode_barang)

        {{-- SUDAH SCAN --}}
        <a href="{{ url('/admin/produk/scan' . $data->id) }}"
           class="btn btn-success btn-xs"
           title="Lihat Barang">

            <i class="fa fa-check"></i>

        </a>

    @else

        {{-- LOADING / MENUNGGU ESP --}}
        <a href="{{ url('/admin/produk/scan/' . $data->id) }}"
           class="btn btn-warning btn-xs"
           title="Menunggu Scan ESP">

            <i class="fa fa-spinner fa-spin"></i>

        </a>

    @endif

    {{-- EDIT --}}
    <a href="/admin/produk/edit/{{ $data->id }}"
       class="btn btn-primary btn-xs ml-1">
        <i class="fa fa-edit"></i>
    </a>

    {{-- DELETE --}}
    <button class="btn btn-danger btn-xs ml-1"
            data-toggle="modal"
            data-target="#delete-{{ $data->id }}">
        <i class="fa fa-trash"></i>
    </button>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

{{-- MODAL DELETE --}}
@foreach($produk as $data)

<div class="modal fade" id="delete-{{ $data->id }}">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-body">

                <h3 class="text-center">
                    Yakin hapus produk ini?
                </h3>

                <hr>

                <p>
                    <b>Kode Barang:</b>
                    {{ $data->kode_barang ?? 'NULL' }}
                </p>

                <p>
                    <b>Nama Produk:</b>
                    {{ $data->nama_produk }}
                </p>

                <div class="row mt-4">

                    <div class="col-md-6">

                        <form action="{{ route('admin.produk.delete', $data->id) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-primary btn-block">
                                Ya
                            </button>
                        </form>

                    </div>

                    <div class="col-md-6">

                        <button class="btn btn-danger btn-block"
                                data-dismiss="modal">
                            Tidak
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endforeach

@endsection