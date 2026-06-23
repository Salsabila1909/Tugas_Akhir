@extends('siswa.layouts.app', [
    'activePage' => 'transaksi',
])

@section('content')

<div class="min-height-200px">

    {{-- HEADER --}}
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">

                <div class="title">
                    <h4>Profil Siswa</h4>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('siswa.transaksi.index') }}">Transaksi</a>
                        </li>
                        <li class="breadcrumb-item active">Profil Siswa</li>
                    </ol>
                </nav>

            </div>
        </div>
    </div>

    {{-- CARD --}}
    <div class="pd-20 card-box mb-30">

        @if($siswa)

        <div class="row">

            {{-- FOTO --}}
            <div class="col-md-3 text-center">

                <img
                    src="{{ $siswa->foto ? asset('storage/'.$siswa->foto) : asset('images/default-user.png') }}"
                    width="140"
                    height="140"
                    style="object-fit:cover; border-radius:10px;"
                >

            </div>

            {{-- DATA --}}
            <div class="col-md-9">

                <h3 class="mb-20">
                    {{ $siswa->nama ?? '-' }}
                </h3>

                <table class="table table-borderless">

                    <tr>
                        <td width="180"><strong>NIS</strong></td>
                        <td>: {{ $siswa->nis ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>: {{ $siswa->nama ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td><strong>Kontak</strong></td>
                        <td>: {{ $siswa->kontak ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td><strong>Alamat</strong></td>
                        <td>: {{ $siswa->alamat ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td><strong>Saldo</strong></td>
                        <td>
                            :
                            <span class="badge badge-success">
                                Rp {{ number_format($siswa->saldo ?? 0,0,',','.') }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            :
                            @if(($siswa->status ?? '') == 'terdaftar')
                                <span class="badge badge-primary">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Belum Aktif</span>
                            @endif
                        </td>
                    </tr>

                </table>

            </div>

        </div>

        @else
            <div class="alert alert-warning">
                Data siswa tidak ditemukan.
            </div>
        @endif

        <hr>

        <a href="{{ route('siswa.transaksi.index') }}"
           class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>

    </div>

</div>

@endsection