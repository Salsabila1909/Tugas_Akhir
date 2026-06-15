@extends('siswa.layouts.app', [
'activePage' => 'dashboard',
])

@section('content')

<div class="min-height-200px">
<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Dashboard</h4>
            </div>

            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/siswa/home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Dashboard
                    </li>
                </ol>
            </nav>

        </div>
    </div>
</div>

<!-- Welcome Card -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 mb-30">

        <div class="card-box pd-20 height-100-p mb-30">

            <div class="row align-items-center">

                <div class="col-md-4 text-center">
                    <img
                        src="{{ asset('assets-siswa/vendors/images/banner-img.png') }}"
                        alt="Banner"
                        class="img-fluid"
                    >
                </div>

                <div class="col-md-8">

                    <h4 class="font-20 weight-500 mb-2">
                        Selamat Datang,
                    </h4>

                    <h3 class="weight-600 text-primary mb-3">
                        {{ Auth::user()->name }}
                    </h3>

                    <p class="font-16 text-justify">
                        Sistem Payment Siswa adalah sebuah sistem berbasis komputer
                        yang digunakan untuk mengelola seluruh proses pembayaran siswa
                        secara otomatis dan terintegrasi, seperti pengelolaan data siswa,
                        pencatatan transaksi pembayaran, autentikasi pengguna, serta
                        pembuatan laporan pembayaran.

                        Sistem ini dirancang untuk meningkatkan keamanan, efisiensi,
                        dan keakuratan dalam proses pembayaran, serta mengurangi
                        penggunaan transaksi tunai di lingkungan sekolah.
                    </p>

                </div>

            </div>

        </div>

    </div>
</div>

</div>

@endsection
