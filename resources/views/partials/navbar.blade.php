
<nav class="navbar navbar-expand-lg main-navbar">
  @php
          if (Auth::check()) {
              if (Auth::user()->role === 'superadmin') {
                  $dashboardRoute = route('superadmin.dashboard');
                  $documentsMainRoute = route('superadmin.documents.index');
                  $documentsRoutePrefix = 'superadmin.documents.category';
              } elseif (Auth::user()->role === 'admin') {
                  $dashboardRoute = route('admin.dashboard');
                  $documentsMainRoute = route('admin.documents.index');
                  $documentsRoutePrefix = 'admin.documents.category';
              } else {
                  $dashboardRoute = route('user.dashboard');
                  $documentsMainRoute = route('user.documents.index');
                  $documentsRoutePrefix = 'user.documents.category';
              }
          } else {
              $dashboardRoute = '#';
              $documentsMainRoute = '#';
              $documentsRoutePrefix = '#';
          }

          
      @endphp

    <form class="form-inline mr-auto">
      <ul class="navbar-nav mr-3">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
      </ul>
    </form>
    <ul class="navbar-nav navbar-right">
      <li class="dropdown dropdown-list-toggle">
        <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg">
            <i class="far fa-bell"></i>
            @php
                $notifCount = Auth::user()?->unreadNotifications->count() ?? 0;
            @endphp
            @if ($notifCount > 0)
                <span class="badge badge-danger badge-pill">{{ $notifCount }}</span>
            @endif
        </a>
        <div class="dropdown-menu dropdown-list dropdown-menu-right">
            <div class="dropdown-header">
                Notifikasi
                <div class="float-right">
                    <a href="{{ route(Auth::user()->role . '.notifications.index') }}">Tandai semua telah dibaca</a>
                </div>
            </div>
            <div class="dropdown-list-content dropdown-list-icons">
                @php
                    $notifLimit = 5;
                    $limitedNotifs = Auth::user()?->unreadNotifications->take($notifLimit);
                @endphp
    
                @forelse ($limitedNotifs as $notif)
                    <a href="#" class="dropdown-item dropdown-item-unread">
                        <div class="dropdown-item-icon bg-primary text-white">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="dropdown-item-desc">
                            {{ $notif->data['message'] }}
                            <div class="time text-primary">{{ $notif->created_at->diffForHumans() }}</div>
                            <form action="{{ route(Auth::user()->role . '.notifications.destroy', $notif->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0">Hapus</button>
                            </form>
                        </div>
                    </a>
                @empty
                    <div class="text-center text-muted my-2">Tidak ada notifikasi baru</div>
                @endforelse
            </div>
            <div class="dropdown-footer text-center">
                <a href="{{ route(Auth::user()->role . '.notifications.index') }}">
                    Lihat Semua <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </li>
    
      <li class="dropdown">
        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
        <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name ?? 'Guest' }}</div></a>
        <div class="dropdown-menu dropdown-menu-right">
          <a href="features-profile.html" class="dropdown-item has-icon">
            <i class="far fa-user"></i> Profile
          </a>
          <a href="features-settings.html" class="dropdown-item has-icon">
            <i class="fas fa-cog"></i> Settings
          </a>
          <div class="dropdown-divider"></div>
          {{-- Logout Link --}}
          <a href="#" class="dropdown-item has-icon text-danger"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
          {{-- Logout Form --}}
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        </div>
      </li>
    </ul>
  </nav>