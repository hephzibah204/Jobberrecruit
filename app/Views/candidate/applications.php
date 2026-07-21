<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-doc"/></svg> My Applications</h1>
            <p>Track the status of every job you've applied to.</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url('jobs') ?>" class="btn btn-accent btn-sm">
                <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-search"/></svg> Browse Jobs
            </a>
        </div>
    </div>

    <section class="card" aria-label="Applications">
        <?php if (empty($applications)): ?>
            <div class="empty">
                <span class="empty-ic"><svg aria-hidden="true" style="width:26px;height:26px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-doc"/></svg></span>
                <h3>No applications yet</h3>
                <p>When you apply for jobs, you'll track every status change here — and we'll notify you the moment an employer responds.</p>
                <a href="<?= base_url('jobs') ?>" class="btn btn-primary btn-sm"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-search"/></svg> Find Jobs to Apply</a>
            </div>
        <?php else: ?>
            <div class="card-body p-0">
                <div class="tbl-wrap">
                    <table class="tbl" id="applications-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Applied On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                                <?php
                                $statusClass = 'pill--pending';
                                $status = strtolower($app->status);
                                if ($status === 'hired' || $status === 'accepted') {
                                    $statusClass = 'pill--hired';
                                } elseif ($status === 'rejected') {
                                    $statusClass = 'pill--rejected';
                                } elseif ($status === 'reviewed' || $status === 'shortlisted') {
                                    $statusClass = 'pill--reviewed';
                                }
                                ?>
                                <tr>
                                    <td><b><?= esc($app->job_title) ?></b></td>
                                    <td><?= esc($app->company_name ?: 'N/A') ?></td>
                                    <td>
                                        <span class="pill <?= $statusClass ?>">
                                            <?= ucfirst($app->status) ?>
                                        </span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($app->created_at)) ?></td>
                                    <td>
                                        <div style="display:flex;gap:8px;">
                                            <a href="<?= base_url('job/view/' . $app->job_id) ?>" class="btn btn-outline btn-sm">
                                                View Job
                                            </a>
                                            <a href="<?= base_url('candidate/applications/view/' . $app->id) ?>" class="btn btn-primary btn-sm">
                                                View Details
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <section class="card" aria-label="How applications work">
      <div class="card-head"><span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bulb"/></svg> How It Works</span></div>
      <div class="card-body">
        <div class="how-strip">
          <div class="how-step"><span class="how-n">1</span><div><b>Apply</b><p>Submit your CV and cover message in about 2 minutes.</p></div></div>
          <div class="how-step"><span class="how-n">2</span><div><b>Pending</b><p>The employer receives your application instantly.</p></div></div>
          <div class="how-step"><span class="how-n">3</span><div><b>Reviewed / Shortlisted</b><p>We email you the moment your status changes.</p></div></div>
          <div class="how-step"><span class="how-n">4</span><div><b>Hired 🎉</b><p>A complete profile makes every application stronger.</p></div></div>
        </div>
      </div>
    </section>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            var appsTable = $('#applications-table').DataTable({
                order: [
                    [3, 'desc']
                ],
                pageLength: 10,
                dom: '<"card-head"<"toolbar"f>>t<"pager"ip>',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search your applications..."
                }
            });
            // Style search field
            $('.dataTables_filter input').addClass('input').css({
                'width': '250px',
                'display': 'inline-block'
            });
            $('.dataTables_filter label').contents().filter(function() {
                return this.nodeType === 3;
            }).remove();

            // Status filter dropdown (parity with mockup toolbar)
            var statusFilter = $('<select class="select" aria-label="Filter by status" style="margin-left:8px;">' +
                '<option value="">All statuses</option>' +
                '<option>Pending</option>' +
                '<option>Reviewed</option>' +
                '<option>Shortlisted</option>' +
                '<option>Rejected</option>' +
                '<option>Hired</option>' +
                '</select>');
            $('#applications-table_wrapper .toolbar').append(statusFilter);
            statusFilter.on('change', function() {
                appsTable.column(2).search(this.value).draw();
            });
        }
    });
</script>
<?= $this->endSection() ?>