@extends('admin.layouts.app', [
'activePage' => 'siswa',
])

@section('content')

<div class="min-height-200px">

    <!-- HEADER -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Registrasi Fingerprint</h4>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/admin/siswa">Data Siswa</a></li>
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
            Registrasi Fingerprint
        </h3>

        <hr>

        <!-- DATA SISWA -->
        <div class="row mb-4 text-left">

            <div class="col-md-6">
                <label>Nama Siswa</label>
                <input type="text" class="form-control" value="{{ $siswa->nama }}" readonly>
            </div>

            <div class="col-md-6">
                <label>NIS</label>
                <input type="text" class="form-control" value="{{ $siswa->nis }}" readonly>
            </div>

        </div>

        <!-- STATUS BOX -->
        <div id="status-box" class="mt-4">

            <div class="spinner-border text-primary"></div>

            <h4 class="text-primary mt-3">
                Silakan tempelkan sidik jari pada sensor...
            </h4>

        </div>

    </div>

</div>

@endsection


@push('scripts')
<script>

let interval = setInterval(() => {

    fetch("{{ route('admin.siswa.fingerprint.check', $siswa->id) }}")
    .then(response => response.json())
    .then(data => {

        console.log(data);

        if (data.success === true) {

            document.getElementById('status-box').innerHTML = `
                <div class="alert alert-success">
                    ✅ Fingerprint berhasil didaftarkan
                </div>

                <div class="alert alert-info">
                    ID Fingerprint: ${data.finger_id}
                </div>

                <a href="/admin/siswa"
                    class="btn btn-primary mt-3">

                    Selesai

                </a>
            `;

            clearInterval(interval);
        }

    })
    .catch(error => {

        console.log('ERROR:', error);

    });

}, 2000);

</script>
@endpush
