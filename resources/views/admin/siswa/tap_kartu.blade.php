<!-- resources/views/admin/siswa/tap_kartu.blade.php -->

@extends('admin.layouts.app', [
'activePage' => 'siswa',
])

@section('content')

<div class="min-height-200px">

    <!-- PAGE HEADER -->
    <div class="page-header">

        <div class="row">

            <div class="col-md-12 col-sm-12">

                <div class="title">
                    <h4>Registrasi RFID</h4>
                </div>

                <nav aria-label="breadcrumb" role="navigation">

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item">
                            <a href="#">Data Siswa</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="/admin/siswa">
                                Pendaftaran Autentikasi
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Registrasi RFID
                        </li>

                    </ol>

                </nav>

            </div>

        </div>

    </div>

    <!-- CARD -->
    <div class="pd-20 card-box mb-30">

        <!-- HEADER CARD -->
        <div class="clearfix">

            <div class="pull-left">

                <h2 class="text-primary h2">

                    <i class="icon-copy dw dw-smartphone-1"></i>
                    Tap Kartu RFID

                </h2>

            </div>

            <div class="pull-right">

                <a href="/admin/siswa"
                    class="btn btn-primary btn-sm">

                    <i class="fa fa-arrow-left"></i>
                    Back

                </a>

            </div>

        </div>

        <hr style="margin-top: 0px">

        <!-- ALERT INFO -->
        <div class="alert alert-info text-center">

            Silahkan tempelkan kartu RFID
            untuk mendaftarkan siswa.

        </div>

        <!-- ALERT AUTO -->
        <div class="alert alert-success text-center">

            Sistem akan otomatis menghubungkan
            kartu RFID ke siswa yang belum terdaftar.

        </div>

        <!-- DATA SISWA -->
        <div class="row">

            <!-- NAMA -->
            <div class="col-md-6">

                <div class="form-group">

                    <label>Nama Siswa</label>

                    <input type="text"
                        class="form-control"
                        value="{{ $siswa->nama }}"
                        readonly>

                </div>

            </div>

            <!-- NIS -->
            <div class="col-md-6">

                <div class="form-group">

                    <label>NIS</label>

                    <input type="text"
                        class="form-control"
                        value="{{ $siswa->nis }}"
                        readonly>

                </div>

            </div>

        </div>

        <!-- STATUS RFID -->
        <div class="text-center mt-5">

            <h1 class="text-primary">

                <i class="icon-copy fa fa-id-card-o"></i>

            </h1>

            <h3 class="text-primary mt-3">

                Tempelkan Kartu RFID

            </h3>

            <br>

            @php
                $rfid = \App\Models\Rfid::where('siswa_id', $siswa->id)->first();
            @endphp

            <!-- STATUS -->
            @if($rfid)

                <div class="alert alert-success"
                    id="status-rfid">

                    <strong>
                        RFID SUDAH TERDAFTAR
                    </strong>

                    <br>

                    UID : {{ $rfid->uid }}

                </div>

                <!-- BUTTON NEXT -->
    <div class="mt-4">

        <a href="{{ url('/admin/siswa/fingerprint/' . $siswa->id) }}"
            class="btn btn-success btn-lg">

            <i class="fa fa-arrow-right"></i>
            Next Registrasi Fingerprint

        </a>

    </div>

            @else

                <div class="alert alert-warning"
                    id="status-rfid">

                    Menunggu kartu RFID...

                </div>

            @endif

        </div>

    </div>
    <!-- END CARD -->

</div>

@endsection