@extends('siswa.layouts.app', [
'activePage' => 'transaksi',
])

@section('content')

<div class="min-height-200px">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="row">

            <div class="col-md-12 col-sm-12">

                <div class="title">
                    <h4>Payment</h4>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Transaksi</li>
                        <li class="breadcrumb-item">
                            <a href="/siswa/transaksi">Data Transaksi</a>
                        </li>
                        <li class="breadcrumb-item active">Payment</li>
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
                    Payment
                </h2>
            </div>

            <div class="pull-right">
                <a href="/siswa/transaksi" class="btn btn-primary btn-sm">
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


        <form id="form-payment"
              action="{{ route('siswa.transaksi.storePayment') }}"
              method="POST">

            @csrf

            <!-- SISWA -->
           <div class="form-group">
                <label>Nama Siswa</label>

                <input type="text"
                    class="form-control"
                    value="{{ $siswa->nama }}"
                    readonly>
            </div>

            <!-- STATUS SCAN -->
            <div class="alert alert-info text-center">
                📡 Menunggu scan dari ESP32CAM...
            </div>

            <!-- HASIL SCAN -->
            <div id="produk-box" class="alert alert-success" style="display:none;">

                <h5>Produk Terdeteksi</h5>

                <p><b>Nama:</b> <span id="nama_produk"></span></p>
                <p><b>Harga:</b> Rp <span id="harga_produk"></span></p>

                <input type="hidden" name="produk_id" id="produk_id">

            </div>

            <!-- QTY -->
            <div class="form-group">
                <label>Qty *</label>
                <input type="number"
                       name="qty"
                       class="form-control"
                       value="1"
                       min="1"
                       required>
            </div>

            <button type="submit"
                    class="btn btn-primary mt-3">

                <i class="ti-arrow-right"></i>
                Next Pembayaran

            </button>

        </form>

    </div>

</div>

@endsection


@push('scripts')
<script>

let lastScanId = 0;

async function updateScan() {

    try {

        const res = await fetch('/api/esp/payment-realtime?t=' + Date.now(), {
            cache: "no-store"
        });

        const json = await res.json();

        if (!json.status || !json.scan_id) return;

        if (json.scan_id === lastScanId) return;

        lastScanId = json.scan_id;

        document.getElementById('produk-box').style.display = 'block';
        document.getElementById('nama_produk').innerText = json.produk.nama;
        document.getElementById('harga_produk').innerText = json.produk.harga;
        document.getElementById('produk_id').value = json.produk.id;

        lockScan(json.scan_id);

    } catch (err) {
        console.log("waiting scan...");
    }
}

function lockScan(scanId) {

    fetch('/api/esp/mark-used', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ scan_id: scanId })
    });
}

setInterval(updateScan, 1500);
updateScan();

document.getElementById('form-payment').addEventListener('submit', function (e) {

    if (!document.getElementById('produk_id').value) {
        e.preventDefault();
        alert('Belum ada hasil scan dari ESP!');
    }

});

</script>
@endpush