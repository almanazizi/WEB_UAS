<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-graph-up text-primary"></i> Statistik Pengunjung
        </h5>
        
        <div class="d-flex gap-2">
            <select id="statsRange" class="form-select form-select-sm" style="width: 150px;">
                <option value="7">7 Hari Terakhir</option>
                <option value="30">30 Hari Terakhir</option>
                <option value="this_month">Bulan Ini</option>
                <option value="custom">Custom Range</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <!-- Date Picker for Custom Range (Hidden by default) -->
        <div id="customDateContainer" class="row g-2 mb-3 d-none">
            <div class="col-auto">
                <input type="date" id="startDate" class="form-control form-control-sm">
            </div>
            <div class="col-auto">
                <span class="align-middle">-</span>
            </div>
            <div class="col-auto">
                <input type="date" id="endDate" class="form-control form-control-sm">
            </div>
            <div class="col-auto">
                <button id="applyCustomDate" class="btn btn-primary btn-sm">Terapkan</button>
            </div>
        </div>

        <!-- Canvas for Chart -->
        <div style="height: 200px;">
            <canvas id="visitorChart"></canvas>
        </div>
        
        <div class="text-center mt-3">
            <span class="badge bg-light text-dark border p-2">
                Total Pengunjung dalam Periode: <strong id="periodTotal">Loading...</strong>
            </span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('visitorChart').getContext('2d');
    const rangeSelect = document.getElementById('statsRange');
    const customContainer = document.getElementById('customDateContainer');
    const applyBtn = document.getElementById('applyCustomDate');
    const totalEl = document.getElementById('periodTotal');
    
    // Determine stats URL based on user role (checking current URL path)
    const isSuperadmin = window.location.pathname.includes('/superadmin/');
    const statsUrl = isSuperadmin ? "{{ route('superadmin.guests.stats') }}" : "{{ route('staff.guests.stats') }}";
    
    let chartInstance = null;

    function fetchStats(startDate = null, endDate = null) {
        let url = statsUrl;
        const params = new URLSearchParams();
        
        if (startDate && endDate) {
            params.append('start_date', startDate);
            params.append('end_date', endDate);
        } else {
            // Logic for presets handled by backend default or we pass explicit dates
            const range = rangeSelect.value;
            const today = new Date();
            let start = new Date();
            
            if (range === '7') {
                start.setDate(today.getDate() - 6);
            } else if (range === '30') {
                start.setDate(today.getDate() - 29);
            } else if (range === 'this_month') {
                start.setDate(1); // First day of month
            }
            
            if (range !== 'custom') {
                params.append('start_date', start.toISOString().split('T')[0]);
                params.append('end_date', today.toISOString().split('T')[0]);
            }
        }

        fetch(`${url}?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                updateChart(data);
                totalEl.textContent = data.total;
            })
            .catch(err => console.error('Error fetching stats:', err));
    }

    function updateChart(data) {
        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Jumlah Pengunjung',
                    data: data.data,
                    borderColor: '#10b981', // Success green
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#059669',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            title: function(context) {
                                return context[0].label; // Date
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            borderDash: [2, 4]
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Event Listeners
    rangeSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customContainer.classList.remove('d-none');
        } else {
            customContainer.classList.add('d-none');
            fetchStats();
        }
    });

    applyBtn.addEventListener('click', function() {
        const start = document.getElementById('startDate').value;
        const end = document.getElementById('endDate').value;
        if (start && end) {
            fetchStats(start, end);
        } else {
            alert('Silakan pilih tanggal mulai dan akhir');
        }
    });

    // Initial Load
    fetchStats();
});
</script>
