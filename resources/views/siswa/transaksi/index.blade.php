@extends('siswa.layouts.app', [
    'activePage' => 'transaksi',
])

@section('content')

<div class="min-height-200px">

    {{-- HEADER --}}
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Data Transaksi</h4>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                        <li class="breadcrumb-item active">Data Transaksi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="pd-20 card-box mb-30">

        {{-- HEADER --}}
        <div class="clearfix">

            <div class="pull-left">
                <h2 class="text-primary h2">
                    <i class="icon-copy dw dw-money"></i> List Data Transaksi
                </h2>
            </div>

            <div class="pull-right">
                <a href="{{ route('siswa.transaksi.payment') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-shopping-cart"></i> Payment
                </a>

                <a href="{{ route('siswa.transaksi.topup') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus-circle"></i> Topup
                </a>
            </div>

        </div>

        <hr>

        {{-- ALERT --}}
        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif

        {{-- TABLE --}}
        <table class="table table-striped table-bordered data-table hover">

            <thead class="bg-primary text-white">
                <tr>
                    <th>#</th>
                    <th>Nama Siswa</th>
                    <th>Type</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>

                @php $no = 1; @endphp

                @forelse($transaksi as $item)

                <tr>

                    <td class="text-center">{{ $no++ }}</td>

                    {{-- SISWA --}}
                    <td>
                        {{ $item->siswa->nama ?? '-' }}
                    </td>

                    {{-- TYPE --}}
                    <td>
                        @if($item->type == 'topup')
                            <span class="badge badge-success">Topup</span>
                        @else
                            <span class="badge badge-primary">Payment</span>
                        @endif
                    </td>

                    {{-- PRODUK --}}
                    <td>
                        @if($item->type == 'payment')
                            {{ $item->produk->nama_produk ?? '-' }}
                        @else
                            <span class="text-success">Topup Saldo</span>
                        @endif
                    </td>

                    {{-- QTY --}}
                    <td>
                        {{ $item->qty ?? '-' }}
                    </td>

                    {{-- TOTAL --}}
                    <td>
                        Rp {{ number_format($item->total, 0, ',', '.') }}
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @if($item->status == 'pending')
                            <span class="badge badge-warning">Pending</span>

                        @elseif($item->status == 'success')
                            <span class="badge badge-success">Success</span>

                        @else
                            <span class="badge badge-secondary">{{ $item->status }}</span>
                        @endif
                    </td>

                    {{-- ACTION --}}
                    <td class="text-center">

                        {{-- DETAIL / TAP KARTU (HANYA JIKA PENDING) --}}
                        @if($item->status == 'pending')
                        <a href="{{ route('siswa.transaksi.tab_kartu', $item->id) }}"
                           class="btn btn-primary btn-xs"
                           title="Verifikasi Kartu">
                            <i class="fa fa-id-card"></i>
                        </a>
                        @endif

                        {{-- PROFIL SISWA --}}
                        <a href="{{ route('siswa.transaksi.profil') }}"
                        class="btn btn-info btn-xs"
                        title="cek saldo">
                            <i class="fa fa-user"></i>
                        </a>

                        {{-- DELETE --}}
                        <form action="{{ route('siswa.transaksi.delete', $item->id) }}"
                              method="POST"
                              style="display:inline-block"
                              onsubmit="return confirm('Yakin hapus transaksi ini?')">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash"></i>
                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="8" class="text-center">
                        Tidak ada data transaksi
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection