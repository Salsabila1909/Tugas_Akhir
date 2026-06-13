<!-- resources/views/admin/transaksi/fingerprint.blade.php -->

@extends('admin.layouts.app', [
'activePage' => 'transaksi',
])

@section('content')

<div class="min-height-200px">

    <!-- HEADER -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Verifikasi Fingerprint Transaksi</h4>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/admin/transaksi">Data Transaksi</a></li>
                        <li class="breadcrumb-item active">Fingerprint</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- CARD -->
    <div class="pd-20 card-box mb-30 text-center">

        <h3 class="text-primary">
            <i class="fa fa-fingerprint"></i>
            Verifikasi Fingerprint
        </h3>

        <hr>

        <!-- DATA TRANSAKSI -->
        <div class="row mb-4 text-left">

            <div class="col-md-6">
                <label>Nama Siswa</label>
                <input type="text" class="form-control"
                    value="{{ $transaksi->siswa->nama }}" readonly>
            </div>

            <div class="col-md-6">
                <label>Total Pembayaran</label>
                <input type="text" class="form-control"
                    value="Rp {{ number_format($transaksi->total,0,',','.') }}" readonly>
            </div>

        </div>

        <!-- STATUS BOX -->
        <div id="status-box" class="mt-5">

            <div class="spinner-border text-primary"></div>

            <h4 class="text-primary mt-3">
                Tempelkan sidik jari untuk menyelesaikan transaksi...
            </h4>

            <div class="alert alert-warning mt-3" id="status">
                {{ $transaksi->status }}
            </div>

        </div>

    </div>

</div>

@endsection


{{-- SCRIPT POLLING --}}
@if($transaksi->status != 'success')
<script>

let interval = setInterval(() => {

    fetch("{{ route('admin.transaksi.check_fingerprint', $transaksi->id) }}")
        .then(res => res.json())
        .then(data => {

            document.getElementById('status').innerHTML = data.status;

            if (data.status === 'success') {

                clearInterval(interval);

                document.getElementById('status-box').innerHTML = `
                    <div class="alert alert-success">
                        ✅ Transaksi berhasil diverifikasi fingerprint
                    </div>

                    <a href="/admin/transaksi"
                        class="btn btn-primary mt-3">
                        Selesai
                    </a>
                `;
            }

        })
        .catch(err => console.log(err));

}, 2000);

</script>
@endif