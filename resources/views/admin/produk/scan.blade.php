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
                    <h4>Scan QR Produk</h4>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Produk</li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.produk.index') }}">Data Produk</a>
                        </li>
                        <li class="breadcrumb-item active">Scan QR Produk</li>
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
                    <i class="fa fa-qrcode"></i>
                    Scan QR Produk
                </h2>
            </div>

            <div class="pull-right">
                <a href="{{ route('admin.produk.index') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <hr>

        <div class="row">

            <!-- DETAIL PRODUK -->
            <div class="col-md-6">

                <h5 class="mb-3">Detail Produk</h5>

                <table class="table table-bordered">
                    <tr>
                        <th>Nama</th>
                        <td>{{ $produk->nama_produk }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $produk->kategori ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>Rp {{ number_format($produk->harga,0,',','.') }}</td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td>{{ $produk->stok }}</td>
                    </tr>
                    <tr>
                        <th>Kode Barang</th>
                        <td id="kode-barang">

                            @if($produk->kode_barang)
                                <span class="badge badge-success">
                                    {{ $produk->kode_barang }}
                                </span>
                            @else
                                <span class="badge badge-warning">
                                    Belum Scan
                                </span>
                            @endif

                        </td>
                    </tr>
                </table>

            </div>

            <!-- STATUS SCAN -->
            <div class="col-md-6 text-center">

                <div id="status-box">

                    @if(!$produk->kode_barang)

                        <div class="alert alert-info">
                            <i class="fa fa-qrcode fa-3x"></i>
                            <br><br>
                            <h5>Menunggu Scan QR</h5>
                            <p>Silakan scan QR / kode barang menggunakan ESP32 atau scanner</p>
                        </div>

                    @else

                        <div class="alert alert-success">
                            <i class="fa fa-check-circle fa-3x"></i>
                            <br><br>
                            <h5>Scan Berhasil</h5>
                            <p>Kode barang sudah tersimpan di sistem</p>
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


{{-- REALTIME CHECK --}}
<script>

let interval = setInterval(() => {

    fetch('/api/esp/check-scan/{{ $produk->id }}')
        .then(res => res.json())
        .then(data => {

            if (!data.kode_barang) return;

            // STOP langsung setelah dapat data valid
            clearInterval(interval);

            document.getElementById('kode-barang').innerHTML =
                `<span class="badge badge-success">${data.kode_barang}</span>`;

            document.getElementById('status-box').innerHTML = `
                <div class="alert alert-success">
                    <i class="fa fa-check-circle fa-3x"></i>
                    <br><br>
                    <h5>Scan Berhasil</h5>
                    <p>Kode Barang: <b>${data.kode_barang}</b></p>
                    <p>Status: <b>${data.mode}</b></p>
                </div>
            `;
        })
        .catch(() => {
            console.log("Waiting for ESP scan...");
        });

}, 2000);

document.addEventListener("visibilitychange", function () {
    if (document.hidden) {
        clearInterval(interval);
    }
});

</script>