<!-- resources/views/admin/transaksi/tap_kartu.blade.php -->

@extends('admin.layouts.app', [
'activePage' => 'transaksi',
])

@section('content')

<div class="min-height-200px">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Verifikasi RFID Transaksi</h4>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                        <li class="breadcrumb-item"><a href="/admin/transaksi">Data Transaksi</a></li>
                        <li class="breadcrumb-item active">Verifikasi RFID</li>
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
                    <i class="icon-copy fa fa-credit-card"></i>
                    Tap Kartu RFID
                </h2>
            </div>

            <div class="pull-right">
                <a href="/admin/transaksi" class="btn btn-primary btn-sm">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <hr>

        <!-- INFO -->
        <div class="alert alert-info text-center">
            Silahkan tempelkan kartu RFID untuk verifikasi pembayaran transaksi.
        </div>

        <!-- DATA TRANSAKSI -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nama Siswa</label>
                    <input type="text" class="form-control"
                        value="{{ $transaksi->siswa->nama }}" readonly>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Total Pembayaran</label>
                    <input type="text" class="form-control"
                        value="Rp {{ number_format($transaksi->total,0,',','.') }}" readonly>
                </div>
            </div>
        </div>

        <!-- STATUS RFID -->
        <div class="text-center mt-5">

            <h1 class="text-primary">
                <i class="fa fa-id-card-o"></i>
            </h1>

            <h3 class="text-primary mt-3">
                Tempelkan Kartu RFID
            </h3>

            <br>

            <div class="alert alert-warning" id="status">
                {{ $transaksi->status }}
            </div>

        </div>

    </div>
</div>

@endsection


{{-- AJAX CHECK RFID --}}
@if($transaksi->status != 'success')
<script>
setInterval(function () {

    fetch("{{ route('admin.transaksi.check_rfid', $transaksi->id) }}")
        .then(res => res.json())
        .then(data => {

            document.getElementById('status').innerHTML = data.status;

            if (data.status === 'rfid_verified') {
                window.location.href =
                    "{{ route('admin.transaksi.sidik_jari', $transaksi->id) }}";
            }

            if (data.status === 'success') {
                window.location.href =
                    "{{ route('admin.transaksi.index') }}";
            }

        });

}, 2000);
</script>
@endif