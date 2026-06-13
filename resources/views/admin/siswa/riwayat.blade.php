@extends('admin.layouts.app', [
'activePage' => 'siswa',
])

@section('content')

<div class="min-height-200px">
<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Riwayat Transaksi</h4>
            </div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.siswa.read') }}">
                            Data Siswa
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        Riwayat Transaksi
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="pd-20 card-box mb-30">

    <div class="clearfix mb-20">

        <div class="pull-left">
            <h4 class="text-primary h4">
                <i class="fa fa-history"></i>
                Riwayat Transaksi Siswa
            </h4>
        </div>

        <div class="pull-right">
            <a href="{{ route('admin.siswa.read') }}"
               class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>

    </div>

    <div class="row mb-30">

        <div class="col-md-3 text-center">

            @if($siswa->foto)
                <img src="{{ asset('storage/'.$siswa->foto) }}"
                     width="120"
                     height="120"
                     style="object-fit:cover;border-radius:10px;">
            @else
                <img src="{{ asset('images/default-user.png') }}"
                     width="120">
            @endif

        </div>

        <div class="col-md-9">

            <table class="table table-borderless">
                <tr>
                    <td width="180"><strong>NIS</strong></td>
                    <td>: {{ $siswa->nis }}</td>
                </tr>

                <tr>
                    <td><strong>Nama</strong></td>
                    <td>: {{ $siswa->nama }}</td>
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
                            Rp {{ number_format($siswa->saldo,0,',','.') }}
                        </span>
                    </td>
                </tr>
            </table>

        </div>

    </div>

    <hr>

    <table class="table table-striped table-bordered data-table hover">

        <thead class="bg-primary text-white">
            <tr>
                <th width="5%">#</th>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>Nominal</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            @forelse($transaksi as $item)

            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>
                    {{ $item->created_at->format('d-m-Y H:i') }}
                </td>

                <td>
                    @if($item->type == 'topup')
                        <span class="badge badge-success">
                            Topup
                        </span>
                    @else
                        <span class="badge badge-primary">
                            Pembelian
                        </span>
                    @endif
                </td>

                <td>
                    {{ $item->produk->nama_produk ?? '-' }}
                </td>

                <td>
                    {{ $item->qty ?? '-' }}
                </td>

                <td>
                    Rp {{ number_format($item->total,0,',','.') }}
                </td>

                <td>
                    @if($item->status == 'success')
                        <span class="badge badge-success">
                            Success
                        </span>
                    @elseif($item->status == 'pending')
                        <span class="badge badge-warning">
                            Pending
                        </span>
                    @elseif($item->status == 'failed')
                        <span class="badge badge-danger">
                            Failed
                        </span>
                    @else
                        <span class="badge badge-info">
                            {{ ucfirst($item->status) }}
                        </span>
                    @endif
                </td>

            </tr>

            @empty

            <tr>
                <td colspan="7" class="text-center">
                    Belum ada riwayat transaksi
                </td>
            </tr>

            @endforelse

        </tbody>

    </table>

</div>
</div>

@endsection
