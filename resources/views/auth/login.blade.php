
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login &mdash; MyAirnav</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="assets/modules/bootstrap-social/bootstrap-social.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
<!-- Start GA -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>
<!-- /END GA --></head>

<body class="login-background">
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
             <div class="card card-primary">
                <div class="login-brand">
                    <img src="assets/img/airnav.png" alt="logo" width="160">
                </div>
                <div class="card-header"><h4>Login</h4></div>
              <div class="card-body">
                <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
                    @csrf
                
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" tabindex="1" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Please fill in your email</div>
                        @enderror
                    </div>
                
                    <!-- Password -->
                    <div class="form-group">
                        <div class="d-block">
                            <label for="password" class="control-label">Password</label>
                            <div class="float-right">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-small">Lupa Password?</a>
                                @endif
                            </div>
                        </div>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" tabindex="2" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Please fill in your password</div>
                        @enderror
                    </div>
                
                    <!-- Remember Me -->
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="remember" class="custom-control-input" id="remember-me" tabindex="3">
                            <label class="custom-control-label" for="remember-me">Remember Me</label>
                        </div>
                    </div>
                
                    <!-- Submit Button -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                            Login
                        </button>
                    </div>
                </form>
                
                <!-- Sign up Link -->
                <div class="mt-5 text-muted text-center">
                    Belum memiliki akun? <a href="{{ route('register') }}">Buat Disini</a>
                </div>
                
            <div class="simple-footer">
              Copyright &copy; 2025
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="assets/modules/jquery.min.js"></script>
  <script src="assets/modules/popper.js"></script>
  <script src="assets/modules/tooltip.js"></script>
  <script src="assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="assets/modules/moment.min.js"></script>
  <script src="assets/js/stisla.js"></script>
  
  <!-- JS Libraies -->

  <!-- Page Specific JS File -->
  
  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>
</body>
</html>