<!DOCTYPE html>
<html lang="en" data-theme="fresh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIM-LAB') - Laboratory Management System</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Fresh Lab Theme -->
    <link rel="stylesheet" href="{{ asset('css/themes/fresh.css') }}">
    
    <style>
        * {
            font-family: 'Inter', sans-serif !important;
        }
        
        body {
            background-color: var(--bg-main);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .sidebar {
            min-height: 100vh;
            background: var(--bg-sidebar);
            transition: background 0.3s ease;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: var(--radius-md);
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
            background: var(--bg-card);
            box-shadow: var(--shadow-md);
            border-radius: var(--radius-md);
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }
        
        .card {
            background: var(--bg-card);
            border-color: var(--border-color);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: var(--radius-md);
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .theme-switcher {
            position: relative;
        }
        
        .theme-preview {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            border: 2px solid white;
        }
        
        .theme-modern { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .theme-dark { background: linear-gradient(135deg, #a78bfa 0%, #ec4899 100%); }
        .theme-fresh { background: linear-gradient(135deg, #06b6d4 0%, #10b981 100%); }
        
        .navbar-top {
            background: var(--bg-navbar);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
        }
    </style>
</head>
<body>
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
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('labs.*') ? 'active' : '' }}" href="{{ route('labs.index') }}">
                                <i class="bi bi-building"></i> Laboratorium
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}" href="{{ route('assets.index') }}">
                                <i class="bi bi-laptop"></i> Inventaris
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">
                                <i class="bi bi-calendar-check"></i> Peminjaman
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}" href="{{ route('tickets.index') }}">
                                <i class="bi bi-tools"></i> Laporan Masalah
                            </a>
                        </li>
                        
                        @if(auth()->user()->isStaff() || auth()->user()->isSuperadmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('staff.guests.*') ? 'active' : '' }}" href="{{ route('staff.guests.index') }}">
                                <i class="bi bi-people-fill"></i> Pengunjung
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
            <div class="col-md-9 col-lg-10 px-0">
                <!-- Top Navbar with Theme Switcher -->
                <nav class="navbar navbar-expand-lg navbar-top px-4 py-3">
                    <div class="container-fluid">
                        <div class="ms-auto d-flex align-items-center gap-3">
                            <span class="text-muted">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            </span>
                        </div>
                    </div>
                </nav>
                
                <!-- Page Content -->
                <div class="px-4 py-4">
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
    </div>
    @else
        @yield('content')
    @endauth
    
    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Theme Switcher Script -->
    <!-- Theme Enforcement Script -->
    <script>
        // Enforce Fresh theme
        document.addEventListener('DOMContentLoaded', function() {
            document.documentElement.setAttribute('data-theme', 'fresh');
            localStorage.setItem('simlab-theme', 'fresh');
        });
    </script>
    
    @yield('scripts')
</body>
</html>
