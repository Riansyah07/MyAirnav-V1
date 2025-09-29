<!-- Sidebar -->
<div class="main-sidebar sidebar-style-2">
    @php
        $user = Auth::user();
        $role = $user->role ?? null;

        switch ($role) {
            case 'superadmin':
                $dashboardRoute = route('superadmin.dashboard');
                $documentsMainRoute = route('superadmin.documents.index');
                $documentsRoutePrefix = 'superadmin.documents.category';
                $certificateRoute = route('superadmin.sertifikat.index');
                $locaRoute = route('superadmin.loca.index');
                $isrRoute = route('superadmin.isr.index');
                
                break;
            case 'admin':
                $dashboardRoute = route('admin.dashboard');
                $documentsMainRoute = route('admin.documents.index');
                $documentsRoutePrefix = 'admin.documents.category';
                $certificateRoute = route('admin.sertifikat.index');
                $locaRoute = route('admin.loca.index');
                $isrRoute = route('admin.isr.index');
                break;
            default:
                $dashboardRoute = route('user.dashboard');
                $documentsMainRoute = route('user.documents.index');
                $documentsRoutePrefix = 'user.documents.category';
                $certificateRoute = route('user.sertifikat.index');
                $locaRoute = route('user.loca.index');
                $isrRoute = route('user.isr.index');
                break;
        }
    @endphp

    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ $dashboardRoute }}">MY AIRNAV</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ $dashboardRoute }}">MA</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Pilihan Menu</li>
            <li>
                <a href="{{ $dashboardRoute }}">
                    <i class="fas fa-archway"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="dropdown">
  <a href="#" class="nav-link has-dropdown">
    <i class="fas fa-fire"></i><span>Pilih Dokumen</span>
  </a>
  <ul class="dropdown-menu">
    <li>
      <a class="dropdown-item" href="{{ $documentsMainRoute }}">
        <i class="fas fa-file-alt"></i>Semua Dokumen
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="{{ route($documentsRoutePrefix, ['category' => 'teknik']) }}">
        <i class="fas fa-cogs"></i>Teknik
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="{{ route($documentsRoutePrefix, ['category' => 'operasi']) }}">
        <i class="fas fa-industry"></i>Operasi
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="{{ route($documentsRoutePrefix, ['category' => 'k3']) }}">
        <i class="fas fa-cog"></i>K3
      </a>
    </li>
  </ul>
</li>

            <li><a href="{{ $certificateRoute }}"><i class="fas fa-certificate"></i> <span>Sertifikat</span></a></li>
            <li><a href="{{ $locaRoute }}"><i class="fas fa-bookmark"></i> <span>LOCA</span></a></li>
            <li><a href="{{ $isrRoute }}"><i class="fas fa-chart-line"></i> <span>ISR</span></a></li>

            @auth
                @if($user->role === 'superadmin')
                    <li>
                        <a href="{{ route('superadmin.users.index') }}">
                            <i class="far fa-user"></i> <span>Kelola User</span>
                        </a>
                    </li>
                @endif
            @endauth
        </ul>
    </aside>
</div>
