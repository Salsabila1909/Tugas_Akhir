@extends('siswa.layouts.app', ['activePage' => 'transaksi'])

@section('content')

<div class="min-height-200px">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="row">

            <div class="col-md-12 col-sm-12">

                <div class="title">
                    <h4>Topup Saldo</h4>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Transaksi</li>
                        <li class="breadcrumb-item">
                            <a href="/siswa/transaksi">Data Transaksi</a>
                        </li>
                        <li class="breadcrumb-item active">Topup Saldo</li>
                    </ol>
                </nav>

            </div>

        </div>
    </div>

    <!-- CARD -->
    <div class="pd-20 card-box mb-30">

        <div class="clearfix">
            <div class="pull-left">
                <h2 class="text-success h2">
                    Topup Saldo
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


        <form action="{{ route('siswa.transaksi.storeTopup') }}" method="POST">

            @csrf

            <!-- SISWA -->
            <div class="form-group">
                <label>Nama Siswa</label>

                <input type="text"
                    class="form-control"
                    value="{{ $siswa->nama }}"
                    readonly>
            </div>

            <!-- NOMINAL -->
            <div class="form-group">
                <label>Nominal Topup *</label>
                <input type="number"
                       name="total"
                       class="form-control"
                       min="1000"
                       required>
            </div>

            <!-- INFO -->
            <div class="alert alert-info text-center">
                Topup akan dilanjutkan ke pembayaran dengan kartu RFID.
            </div>

            <button type="submit"
                    class="btn btn-success mt-3">

                <i class="fa fa-arrow-right"></i>
                Next Pembayaran 

            </button>

        </form>

    </div>

</div>

@endsection