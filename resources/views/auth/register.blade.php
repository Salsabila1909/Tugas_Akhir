<html>
<head>
    <meta charset="utf-8">
    <title>Register Siswa - Sisment</title>
<link rel="apple-touch-icon" sizes="180x180" href="{{url('assets-admin')}}/vendors/images/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="{{url('assets-admin')}}/vendors/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="{{url('assets-admin')}}/vendors/images/favicon-16x16.png">

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="{{url('assets-admin')}}/vendors/styles/core.css">
<link rel="stylesheet" type="text/css" href="{{url('assets-admin')}}/vendors/styles/icon-font.min.css">
<link rel="stylesheet" type="text/css" href="{{url('assets-admin')}}/vendors/styles/style.css">
</head>

<body class="login-page">

<div class="login-header box-shadow">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="brand-logo">
            <a href="/">
                <img src="{{url('assets-admin')}}/vendors/images/sisment.png" alt="">
            </a>
        </div>
    </div>
</div>

<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
    <div class="container">
        <div class="row align-items-center">

        <div class="col-md-6 col-lg-7">
            <img src="{{url('assets-admin')}}/vendors/images/login-page-img.png" alt="">
        </div>

        <div class="col-md-6 col-lg-5">

            <div class="login-box bg-white box-shadow border-radius-10">

                <div class="login-title">
                    <h2 class="text-center text-primary">
                        Registrasi Akun Siswa
                    </h2>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('siswa.register.store') }}">
                    @csrf

                    <div class="input-group custom">
                        <input type="text"
                               name="nis"
                               class="form-control form-control-lg"
                               placeholder="Masukkan NIS"
                               required>

                        <div class="input-group-append custom">
                            <span class="input-group-text">
                                <i class="icon-copy dw dw-user1"></i>
                            </span>
                        </div>
                    </div>

                    <div class="input-group custom">
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control form-control-lg"
                               placeholder="Masukkan Password"
                               required>

                        <div class="input-group-append custom"
                             onclick="togglePassword()">
                            <span class="input-group-text">
                                <i class="dw dw-padlock1" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>

                    <div class="input-group mb-0 mt-3">
                        <button type="submit"
                                class="btn btn-primary btn-lg btn-block">
                            Daftar
                        </button>
                    </div>

                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}">
                        Sudah punya akun? Login
                    </a>
                </div>

            </div>

        </div>

    </div>
</div>


<script>
function togglePassword()
{
    var passwordInput = document.getElementById("password");
    var toggleIcon = document.getElementById("toggleIcon");

    if (passwordInput.type === "password")
    {
        passwordInput.type = "text";
        toggleIcon.classList.remove("dw-padlock1");
        toggleIcon.classList.add("dw-unlock1");
    }
    else
    {
        passwordInput.type = "password";
        toggleIcon.classList.remove("dw-unlock1");
        toggleIcon.classList.add("dw-padlock1");
    }
}
</script>

<script src="{{url('assets-admin')}}/vendors/scripts/core.js"></script>

<script src="{{url('assets-admin')}}/vendors/scripts/script.min.js"></script>

<script src="{{url('assets-admin')}}/vendors/scripts/process.js"></script>

<script src="{{url('assets-admin')}}/vendors/scripts/layout-settings.js"></script>

</body>
</html>
