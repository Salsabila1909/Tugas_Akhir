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
                    <h4>Scan QR Produk</h4>
                </div>
                <p class="text-muted">Menunggu scan QR dari ESP32 / scanner</p>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <a href="{{ route('admin.produk.index') }}"
                class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- CARD --}}
    <div class="pd-20 card-box mb-30">

        <div class="row">

            {{-- DATA PRODUK --}}
            <div class="col-md-6">

                <h5>Detail Produk</h5>

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

            {{-- STATUS SCAN --}}
            <div class="col-md-6 text-center">

                <div id="status-box">
                    

                    @if(!$produk->kode_barang)

                        <div class="alert alert-info">
                            <i class="fa fa-qrcode fa-3x"></i>
                            <br><br>
                            <h5>Menunggu kode barang...</h5>
                            <p>Silakan scan kode_barang / scanner</p>
                        </div>

                    @else

                        <div class="alert alert-success">
                            <i class="fa fa-check-circle fa-3x"></i>
                            <br><br>
                            <h5>Scan Berhasil!</h5>
                            <p>Kode sudah tersimpan</p>
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

{{-- AJAX REALTIME CHECK --}}
@if(!$produk->kode_barang)
<script>
let interval = setInterval(function () {

    fetch("{{ route('admin.produk.check', $produk->id) }}")
        .then(res => {
            if (!res.ok) throw new Error("Network error");
            return res.json();
        })
        .then(data => {

            if (data.kode_barang) {

                document.getElementById('kode-barang').innerHTML =
                    `<span class="badge badge-success">${data.kode_barang}</span>`;

                document.getElementById('status-box').innerHTML = `
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle fa-3x"></i>
                        <br><br>
                        <h5>Scan Berhasil!</h5>
                        <p>Kode Barang: <b>${data.kode_barang}</b></p>
                    </div>
                `;

                clearInterval(interval);
            }

        })
        .catch(err => {
            console.log("Waiting for scan...");
        });

}, 2000);

// optional: stop polling kalau tab tidak aktif
document.addEventListener("visibilitychange", function () {
    if (document.hidden) {
        clearInterval(interval);
    }
});
</script>
@endif

@endsection