<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="content">

    <div class="page-header">
        <h4 class="fw-bold">Job Alerts</h4>
        <h6>Get notified when new jobs match your criteria</h6>
    </div>

    <div class="row">

        <!-- Left: Create Alert -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">Create Job Alert</h6>
                </div>

                <div class="card-body">

                    <form id="alert-form">

                        <div class="mb-3">
                            <label class="fw-semibold">Keyword</label>
                            <input type="text" name="keyword" class="form-control" placeholder="e.g. Software Developer" value="<?= esc($presetKeyword ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Location</label>
                            <select name="location_id" class="form-select" required>
                                <option value="" disabled selected>Choose Location</option>
                                <?php foreach ($states as $state): ?>
                                    <option value="<?= $state->id ?>" <?= ($presetLocationId ?? '') == $state->id ? 'selected' : '' ?>><?= esc($state->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Frequency</label>
                            <select name="frequency" class="form-select">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Delivery Time</label>
                            <input type="time" name="delivery_time" class="form-control" value="08:00">
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Notification Channel</label>
                            <select name="channel" class="form-select">
                                <option value="email">Email (Default)</option>
                                <option value="sms" disabled>SMS (Not Available)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Create Alert
                        </button>

                    </form>

                </div>
            </div>
        </div>

        <!-- Right: Existing Alerts -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">Your Alerts</h6>
                </div>

                <div class="card-body p-0">

                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Keyword</th>
                                <th>Location</th>
                                <th>Frequency</th>
                                <th>Time</th>
                                <th>Channel</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($alerts) > 0): ?>
                                <?php foreach ($alerts as $alert): ?>
                                    <tr>
                                        <td><?= esc($alert->keyword) ?></td>
                                        <td><?= esc($alert->location_id ?: 'Any') ?></td>
                                        <td><?= ucfirst($alert->frequency) ?></td>
                                        <td><?= $alert->delivery_time ? date('g:i A', strtotime($alert->delivery_time)) : '—' ?></td>
                                        <td><?= ucfirst($alert->channel) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-danger delete-alert" data-id="<?= $alert->id ?>">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No alerts created yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
    $('#alert-form').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('candidate/alerts/save') ?>",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success("Job alert created successfully");
                    setTimeout(() => location.reload(), 800);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error("Network error");
            }
        });
    });

    // Delete alert
    $('.delete-alert').on('click', function() {
        let id = $(this).data('id');

        if (!confirm("Delete this alert?")) return;

        $.post("<?= site_url('candidate/alerts/delete') ?>/" + id, {
            <?= csrf_token() ?>: "<?= csrf_hash() ?>"
        }, function(response) {
            if (response.success) {
                toastr.success("Alert deleted");
                setTimeout(() => location.reload(), 800);
            } else {
                toastr.error("Unable to delete alert");
            }
        });
    });
</script>
<?= $this->endSection() ?>