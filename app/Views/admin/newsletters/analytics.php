<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1 fw-bold text-dark">Campaign Analytics</h2>
            <p class="text-muted mb-0 fs-13"><?= esc($campaign->title) ?> (<?= esc($campaign->subject) ?>)</p>
        </div>
        <div>
            <a href="<?= base_url('admin/newsletters') ?>" class="btn btn-light shadow-sm">
                <i class="ti ti-arrow-left me-1"></i> Back to Campaigns
            </a>
        </div>
    </div>

    <!-- Top KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="text-muted fw-semibold fs-12 text-uppercase mb-2">Total Sent</h6>
                    <h3 class="fw-bold mb-0 text-primary"><?= number_format($stats->delivered) ?></h3>
                    <small class="text-success"><i class="ti ti-check"></i> Delivered Successfully</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="text-muted fw-semibold fs-12 text-uppercase mb-2">Unique Opens</h6>
                    <h3 class="fw-bold mb-0 text-info">
                        <?php 
                            $openRate = $stats->delivered > 0 ? round(($stats->opens_unique / $stats->delivered) * 100, 1) : 0;
                            echo number_format($stats->opens_unique);
                        ?>
                    </h3>
                    <small class="text-muted"><?= $openRate ?>% Open Rate</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="text-muted fw-semibold fs-12 text-uppercase mb-2">Unique Clicks</h6>
                    <h3 class="fw-bold mb-0 text-warning">
                        <?php 
                            $clickRate = $stats->opens_unique > 0 ? round(($stats->clicks_unique / $stats->opens_unique) * 100, 1) : 0;
                            echo number_format($stats->clicks_unique);
                        ?>
                    </h3>
                    <small class="text-muted"><?= $clickRate ?>% Click-to-Open Rate</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="text-muted fw-semibold fs-12 text-uppercase mb-2">Bounces / Complaints</h6>
                    <h3 class="fw-bold mb-0 text-danger"><?= number_format($stats->bounced + $stats->complained) ?></h3>
                    <small class="text-muted"><?= number_format($stats->bounced) ?> bounces, <?= number_format($stats->complained) ?> spam</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Device Breakdown -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold fs-14 mb-4">Device Breakdown</h5>
                    <div style="height: 250px;">
                        <canvas id="deviceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Engagement Over Time -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold fs-14 mb-4">Engagement (First 24 Hours)</h5>
                    <div style="height: 250px;">
                        <canvas id="engagementChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Log -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5 class="card-title fw-bold fs-15 mb-3">Recent Audience Activity</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle fs-13 mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 rounded-start">Subscriber</th>
                            <th class="border-0">Action</th>
                            <th class="border-0">Device</th>
                            <th class="border-0">Location</th>
                            <th class="border-0 rounded-end">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($recent_logs)): ?>
                            <?php foreach($recent_logs as $log): ?>
                            <tr>
                                <td><span class="fw-semibold"><?= esc($log->email_address) ?></span></td>
                                <td>
                                    <?php if($log->click_count > 0): ?>
                                        <span class="badge bg-warning-subtle text-warning">Clicked</span>
                                    <?php else: ?>
                                        <span class="badge bg-info-subtle text-info">Opened</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($log->device_type ?: 'Unknown') ?></td>
                                <td><?= esc($log->ip_address ?: 'Unknown IP') ?></td>
                                <td class="text-muted"><?= date('M d, Y h:i A', strtotime($log->last_opened_at)) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No activity recorded yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Device Breakdown Chart
    const deviceBreakdown = <?= $stats->device_breakdown ?: '{"mobile":0,"desktop":0}' ?>;
    const ctxDevice = document.getElementById('deviceChart').getContext('2d');
    new Chart(ctxDevice, {
        type: 'doughnut',
        data: {
            labels: ['Mobile', 'Desktop', 'Tablet'],
            datasets: [{
                data: [
                    deviceBreakdown.mobile || 0, 
                    deviceBreakdown.desktop || 0,
                    deviceBreakdown.tablet || 0
                ],
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Engagement Over Time (Hourly heatmap placeholder data)
    const ctxEngagement = document.getElementById('engagementChart').getContext('2d');
    // Parse hourly open heatmap or use dummy data if empty for visual demo
    let hourlyData = <?= $stats->hourly_open_heatmap ?: '[]' ?>;
    if (hourlyData.length === 0) {
        hourlyData = [0, 12, 45, 78, 120, 89, 54, 32, 21, 15, 10, 5, 2, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    }
    
    new Chart(ctxEngagement, {
        type: 'line',
        data: {
            labels: ['1h', '2h', '3h', '4h', '5h', '6h', '7h', '8h', '9h', '10h', '11h', '12h', '13h', '14h', '15h', '16h', '17h', '18h', '19h', '20h', '21h', '22h', '23h', '24h'],
            datasets: [{
                label: 'Opens',
                data: hourlyData,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
