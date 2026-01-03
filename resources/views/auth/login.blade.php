@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-flask" style="font-size: 3rem; color: #667eea;"></i>
                        </div>
                        <h3 class="fw-bold">SIM-LAB</h3>
                        <p class="text-muted">Sistem Manajemen Laboratorium</p>
                    </div>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle"></i>
                            @foreach($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                    
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="user@lab.com" required autofocus>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ingat Saya
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2" 
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                        </button>
                    </form>
                    
                    
                    {{-- Quick Access Buttons --}}
                    <div class="mt-4">
                        <div class="text-center mb-2">
                            <small class="text-muted">Akses Cepat</small>
                        </div>
                        <div class="row g-2">
                            {{-- Schedule Button --}}
                            <div class="col-6">
                                <a href="{{ route('schedule.index') }}" class="btn btn-outline-primary w-100 py-2">
                                    <i class="bi bi-calendar-week"></i>
                                    <div class="small">Jadwal Lab</div>
                                </a>
                            </div>
                            
                            {{-- Guest Check-in Button --}}
                            <div class="col-6">
                                <a href="{{ route('visitor.check-in') }}" class="btn btn-outline-success w-100 py-2">
                                    <i class="bi bi-person-check"></i>
                                    <div class="small">Guest Check-in</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="small text-muted">
                        <strong>Akun Demo:</strong><br>
                        Superadmin: superadmin@lab.com<br>
                        Staff: staff@lab.com<br>
                        Dosen/Mahasiswa: user@lab.com<br>
                        Password: password
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
