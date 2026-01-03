<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIM-LAB') - Laboratory Management System</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">
    @auth
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="p-3">
                    <h4 class="text-white mb-4">
                        <i class="bi bi-flask"></i> SIM-LAB
                    </h4>
                    <div class="text-white-50 small mb-3">
                        <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        <br>
                        <span class="badge bg-light text-dark mt-1">
                            @if(auth()->user()->isSuperadmin())
                                Superadmin
                            @elseif(auth()->user()->isStaff())
                                Staff/PJ Lab
                            @else
                                Dosen/Mahasiswa
                            @endif
                        </span>
                    </div>
                    <hr class="text-white-50">
                    
                    <ul class="nav flex-column">
                        @if(auth()->user()->isSuperadmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Beranda
                                </a>
                            </li>
                        @elseif(auth()->user()->isStaff())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}" href="{{ route('staff.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Beranda
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Beranda
                                </a>
                            </li>
                        @endif
                        
                        {{-- Laboratorium - Only for Staff & Superadmin --}}
                        @if(auth()->user()->isStaff() || auth()->user()->isSuperadmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('labs.*') ? 'active' : '' }}" href="{{ route('labs.index') }}">
                                <i class="bi bi-building"></i> Laboratorium
                            </a>
                        </li>
                        @endif
                        
                        {{-- Inventaris - Only for Staff & Superadmin --}}
                        @if(auth()->user()->isStaff() || auth()->user()->isSuperadmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}" href="{{ route('assets.index') }}">
                                <i class="bi bi-laptop"></i> Inventaris
                            </a>
                        </li>
                        @endif
                        
                        {{-- Peminjaman - Available for all roles --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">
                                <i class="bi bi-calendar-check"></i> Peminjaman
                            </a>
                        </li>
                        
                        {{-- Laporan Masalah - Available for all roles --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}" href="{{ route('tickets.index') }}">
                                <i class="bi bi-tools"></i> Laporan Masalah
                            </a>
                        </li>
                        
                        @if(auth()->user()->isStaff() || auth()->user()->isSuperadmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('staff.visitor*') || request()->routeIs('staff.notifications') ? 'active' : '' }}" href="{{ route('staff.visitors.dashboard') }}">
                                <i class="bi bi-person-check-fill"></i> Pengunjung
                                @php
                                    $pendingCount = \App\Models\Visitor::where('status', 'pending')->count();
                                @endphp
                                @if($pendingCount > 0)
                                    <span class="badge bg-danger ms-1">{{ $pendingCount }}</span>
                                @endif
                            </a>
                        </li>
                        @endif
                    </ul>
                    
                    <hr class="text-white-50">
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm w-100">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-4 py-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    @else
        @yield('content')
    @endauth
    
    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
