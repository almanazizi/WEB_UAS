{{-- Maintenance Pie Chart Component --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">
            <i class="bi bi-pie-chart-fill"></i> Status Tiket Maintenance
        </h5>
    </div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <canvas id="ticketPieChart" style="max-height: 250px;"></canvas>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge" style="background-color: #f59e0b; width: 20px; height: 20px;"></span>
                        <span class="ms-2"><strong>Open:</strong> {{ $openTickets }} tiket</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" style="background-color: #f59e0b; width: {{ $openTickets + $resolvedTickets > 0 ? ($openTickets / ($openTickets + $resolvedTickets)) * 100 : 0 }}%;"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge" style="background-color: #10b981; width: 20px; height: 20px;"></span>
                        <span class="ms-2"><strong>Resolved:</strong> {{ $resolvedTickets }} tiket</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" style="background-color: #10b981; width: {{ $openTickets + $resolvedTickets > 0 ? ($resolvedTickets / ($openTickets + $resolvedTickets)) * 100 : 0 }}%;"></div>
                    </div>
                </div>
                <hr class="my-3">
                <p class="mb-0 text-muted small">
                    <i class="bi bi-info-circle"></i> Total: {{ $openTickets + $resolvedTickets }} tiket
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('ticketPieChart');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Open', 'Resolved'],
            datasets: [{
                label: 'Tiket',
                data: [{{ $openTickets }}, {{ $resolvedTickets }}],
                backgroundColor: [
                    '#f59e0b', // Open - Amber
                    '#10b981'  // Resolved - Green
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.parsed || 0;
                            let total = {{ $openTickets + $resolvedTickets }};
                            let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value + ' tiket (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
</script>
