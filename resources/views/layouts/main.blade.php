<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>MyAirNav Apps</title>

<link rel="icon" href="{{ asset('assets/img/airnavicon.png') }}">
  
  <!-- General CSS Files -->
<link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">

<!-- Template CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">


<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>
</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      
      @include('partials.navbar')

      @include('partials.sidebar')

      <!-- Main Content -->
      <div class="main-content">
        
        @yield('content')

        @stack('scripts')
        
      </div>

      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; 2025 <a href="#"></a>
        </div>
        <div class="footer-right">
          
        </div>
      </footer>
    </div>
  </div>

  

  <!-- General JS Scripts -->
<script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
<script src="{{ asset('assets/modules/popper.js') }}"></script>
<script src="{{ asset('assets/modules/tooltip.js') }}"></script>
<script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('assets/modules/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/stisla.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<!-- Template JS File -->
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

</body>
</html>