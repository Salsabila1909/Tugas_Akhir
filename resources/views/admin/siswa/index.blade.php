@extends('admin.layouts.app', [
'activePage' => 'siswa',
])

@section('content')

<div class="min-height-200px">

    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Data Siswa</h4>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Data Siswa</a></li>
                        <li class="breadcrumb-item active">Pendaftaran Autentikasi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="pd-20 card-box mb-30">

        <div class="clearfix">
            <div class="pull-left">
                <h2 class="text-primary h2">
                    <i class="icon-copy dw dw-user1"></i> List Data Siswa
                </h2>
            </div>

            <div class="pull-right">
                <a href="{{ route('admin.siswa.add') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tambah Data
                </a>
            </div>
        </div>

        <hr>

        {{-- ALERT --}}
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif

        <table class="table table-striped table-bordered data-table hover">
            <thead class="bg-primary text-white">
                <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kontak</th>
                    <th>Alamat</th>
                    <th>Saldo</th>
                    <th>Status</th>
                    <th>UID RFID</th>
                    <th>Finger ID</th>
                    <th class="text-center" width="180">Action</th>
                </tr>
            </thead>

            <tbody>
                @php $no = 1; @endphp

                @foreach($siswa as $data)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>

                    {{-- FOTO --}}
                    <td class="text-center">
                        @if($data->foto)
                            <img src="{{ asset('storage/'.$data->foto) }}"
                                 width="60"
                                 height="60"
                                 style="object-fit: cover; border-radius: 10px;">
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ $data->nis }}</td>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->kontak }}</td>
                    <td>{{ $data->alamat }}</td>

                    {{-- SALDO --}}
                    <td>
                        Rp {{ number_format($data->saldo,0,',','.') }}
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @if($data->status == 'terdaftar')
                            <span class="badge badge-success">Terdaftar</span>
                        @else
                            <span class="badge badge-danger">Belum Terdaftar</span>
                        @endif
                    </td>

                    {{-- RFID --}}
                    <td>
                        @if(!empty($data->uid))
                            {{ $data->uid }}
                        @else
                            <span class="text-danger">Belum Tap</span>
                        @endif
                    </td>

                    {{-- FINGERPRINT ID --}}
                    <td>
                        {{ optional($data->fingerprint)->finger_id ?? 'Belum Fingerprint' }}
                    </td>

                    {{-- ACTION --}}
                    <td class="text-center">

                        {{-- RFID TAP --}}
                        <a href="/admin/siswa/tap-kartu/{{$data->id}}"
                           class="btn btn-warning btn-xs"
                           title="Tap RFID">
                            <i class="fa fa-id-card"></i>
                        </a>

                        {{-- FINGERPRINT --}}
                        @if(empty(optional($data->fingerprint)->finger_id))
                            <a href="{{ route('admin.siswa.fingerprint', $data->id) }}"
                              class="btn btn-warning btn-xs"
                                title="Scan Fingerprint">
                                <i class="fa fa-spinner fa-spin"></i>
                            </a>
                        @else
                            <span class="badge badge-success" title="Fingerprint Terdaftar">
                                <i class="fa fa-check"></i>
                            </span>
                        @endif

                        {{-- RIWAYAT TRANSAKSI --}}
                        <a href="{{ route('admin.siswa.riwayat', $data->id) }}"
                        class="btn btn-info btn-xs"
                        title="Riwayat Transaksi">
                            <i class="fa fa-history"></i>
                        </a>

                        {{-- EDIT --}}
                        <a href="/admin/siswa/edit/{{ $data->id }}"
                           class="btn btn-success btn-xs">
                            <i class="fa fa-edit"></i>
                        </a>

                        {{-- DELETE --}}
                        <button class="btn btn-danger btn-xs"
                                data-toggle="modal"
                                data-target="#data-{{ $data->id }}">
                            <i class="fa fa-trash"></i>
                        </button>

                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>

    </div>
</div>

{{-- MODAL DELETE --}}
@foreach($siswa as $data)
<div class="modal fade" id="data-{{ $data->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h3 class="text-center">Yakin hapus data ini?</h3>
                <hr>

                <p><b>Nama:</b> {{ $data->nama }}</p>
                <p><b>NIS:</b> {{ $data->nis }}</p>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <form action="{{ route('admin.siswa.delete', $data->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-primary btn-block">Ya</button>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <button class="btn btn-danger btn-block" data-dismiss="modal">
                            Tidak
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endforeach

@endsection