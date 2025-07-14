<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
  <div class="container">
    <div class="d-flex align-items-center" style="height: 80px;">
      <img src="{{ asset('images/emblem-dark.png') }}" alt="Emblem" class="h-100" style="object-fit: contain;">
      <div class="d-flex flex-column ms-3">
        <h4 class="bold-font">Cabinet Secretariat</h4>
        <span lang="hi" class="bold-font">Government of India</span>
      </div>
    </div>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        @if (!request()->routeIs('home'))
        <li class="nav-item mt-1">
          <a class="nav-link" href="{{ route('home') }}">Home</a>
        </li>
        @endif
        @auth
        <li class="nav-item mt-1">
          <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold text-primary' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
        </li>

        <li class="nav-item mt-1">
          <a class="nav-link {{ request()->routeIs('complaints.index') ? 'active fw-bold text-primary' : '' }}" href="{{ route('complaints.index') }}">Tickets</a>
        </li>


        @if(auth()->user()->isManager())
        <li class="nav-item mt-1">
          <a class="nav-link {{ request()->routeIs('users.index') ? 'active fw-bold text-primary' : '' }}" href="{{ route('users.index') }}">Users</a>
        </li>
        <li class="nav-item mt-1">
          <a class="nav-link {{ request()->routeIs('masters.index') ? 'active fw-bold text-primary' : '' }}" href="{{ route('masters.index') }}">Masters</a>
        </li>
        @endif

        @if(auth()->check() && auth()->user()->role && strtolower(auth()->user()->role->name) === 'manager')
        <li class="nav-item mt-1">
          <a class="nav-link" href="{{ route('audit-log.index') }}">Audit Log</a>
        </li>
        @endif

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center" style="width: 36px; height: 36px; font-size: 1rem;">
              {{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li class="dropdown-header text-center">
              <strong>{{ auth()->user()->full_name }}</strong><br>
              <small class="text-muted">{{ auth()->user()->role->name ?? 'No Role' }}

                @unless(auth()->user()->isAdmin() || auth()->user()->isManager())
                @if(auth()->user()->verticals && auth()->user()->verticals->count())
                (Verticals: {{ auth()->user()->verticals->pluck('name')->implode(', ') }})
                @endif
                @endunless
              </small>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item text-primary text-center" href="{{ route('profile.change-password') }}">
                <i class="bi bi-key me-1"></i> Change Password
              </a>
            </li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-danger text-center">Logout</button>
              </form>
            </li>
          </ul>
        </li>


        @endauth
      </ul>
    </div>
  </div>
</nav>

@push('style')
<link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}">
@endpush