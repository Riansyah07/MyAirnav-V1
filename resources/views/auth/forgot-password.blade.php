
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Reset Password &mdash; MyAirnav</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
</head>

<body class="login-background">
  <div id="app">
    <section class="section mt-5">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">

            <div class="card card-primary">
              <div class="card-header"><h4>Reset Password</h4></div>

              <div class="card-body">
                <p class="text-muted">Masukkan email kamu untuk menerima link reset password</p>

                <!-- Status dari session -->
                @if (session('status'))
                  <div class="alert alert-success">
                      {{ session('status') }}
                  </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                  @csrf

                  <!-- Email Address -->
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                      Kirim Link Reset Password
                    </button>
                  </div>
                </form>
                <div class="simple-footer text-center">
                  <a href="{{ route('login') }}">← Kembali ke Login</a>
                </div> 
              </div>
            </div>

            

          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
</body>
</html>
