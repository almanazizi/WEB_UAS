<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catat Kehadiran - SIM-LAB</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .check-in-card {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card check-in-card border-0">
                    <div class="card-body p-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <i class="bi bi-clipboard-check text-success" style="font-size: 3.5rem;"></i>
                            <h3 class="fw-bold mt-3">Catat Kehadiran Pengunjung</h3>
                            <p class="text-muted">Sistem Manajemen Laboratorium</p>
                        </div>

                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle"></i>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Check-in Form -->
                        <form action="{{ route('guest.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="nim" class="form-label fw-bold">NIM <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                    <input type="text" class="form-control @error('nim') is-invalid @enderror" 
                                           id="nim" name="nim" value="{{ old('nim') }}" 
                                           placeholder="Contoh: 123456789" required autofocus>
                                </div>
                                <small class="text-muted">Masukkan Nomor Induk Mahasiswa</small>
                            </div>

                            <div class="mb-3">
                                <label for="nama" class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                           id="nama" name="nama" value="{{ old('nama') }}" 
                                           placeholder="Contoh: John Doe" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="lab_id" class="form-label fw-bold">Laboratorium <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <select class="form-select @error('lab_id') is-invalid @enderror" 
                                            id="lab_id" name="lab_id" required>
                                        <option value="">-- Pilih Laboratorium --</option>
                                        @foreach($labs as $lab)
                                            <option value="{{ $lab->id }}" {{ old('lab_id') == $lab->id ? 'selected' : '' }}>
                                                {{ $lab->name }} - {{ $lab->location }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="purpose" class="form-label fw-bold">Keperluan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('purpose') is-invalid @enderror" 
                                          id="purpose" name="purpose" rows="3" 
                                          placeholder="Contoh: Belajar mandiri, mengerjakan tugas, praktikum, dll." 
                                          required>{{ old('purpose') }}</textarea>
                                <small class="text-muted">Jelaskan tujuan kunjungan Anda</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle"></i> Catat Kehadiran
                                </button>
                                <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="text-center mt-3 text-white">
                    <small>
                        <i class="bi bi-info-circle"></i> 
                        Form ini untuk pengunjung mandiri yang tidak memiliki jadwal booking
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
