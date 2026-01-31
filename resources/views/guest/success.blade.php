<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kehadiran Tercatat - SIM-LAB</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .success-card {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .check-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .detail-row {
            background-color: #f8f9fa;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card success-card border-0">
                    <div class="card-body p-5">
                        <!-- Success Icon -->
                        <div class="check-icon">
                            <i class="bi bi-check-lg text-white" style="font-size: 4rem;"></i>
                        </div>

                        <!-- Success Message -->
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-success">Kehadiran Berhasil Dicatat!</h3>
                            <p class="text-muted">Terima kasih telah mengunjungi laboratorium</p>
                        </div>

                        @if(session('guest'))
                            @php
                                $guest = session('guest');
                            @endphp

                            <!-- Detail Information -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Detail Kunjungan:</h6>
                                
                                <div class="detail-row">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted"><i class="bi bi-person-badge"></i> NIM:</span>
                                        <span class="fw-bold">{{ $guest->nim }}</span>
                                    </div>
                                </div>

                                <div class="detail-row">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted"><i class="bi bi-person"></i> Nama:</span>
                                        <span class="fw-bold">{{ $guest->nama }}</span>
                                    </div>
                                </div>

                                <div class="detail-row">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted"><i class="bi bi-building"></i> Laboratorium:</span>
                                        <span class="fw-bold">{{ $guest->lab->name }}</span>
                                    </div>
                                </div>

                                <div class="detail-row">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted"><i class="bi bi-clock"></i> Waktu Check-in:</span>
                                        <span class="fw-bold">{{ $guest->check_in_time->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                </div>

                                <div class="detail-row">
                                    <div>
                                        <span class="text-muted"><i class="bi bi-journal-text"></i> Keperluan:</span>
                                        <p class="mb-0 mt-1 fw-bold">{{ $guest->purpose }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('guest.checkin') }}" class="btn btn-success btn-lg">
                                <i class="bi bi-plus-circle"></i> Catat Kehadiran Lagi
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-house"></i> Kembali ke Beranda
                            </a>
                        </div>

                        <!-- Info Message -->
                        <div class="alert alert-info mt-4 mb-0">
                            <i class="bi bi-info-circle"></i>
                            <small>
                                Data kehadiran Anda telah tersimpan. Staff laboratorium dapat melihat data kunjungan Anda.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
