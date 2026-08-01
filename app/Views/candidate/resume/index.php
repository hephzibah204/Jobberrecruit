<?php $page_title = 'AI Resume Builder'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <!-- Header Section -->
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-doc"/></svg> My Resumes</h1>
            <p>Create and manage your AI-powered resumes</p>
        </div>
        <div class="page-actions">
            <a href="<?= site_url('candidate/resumes/build') ?>" class="btn btn-primary">
                Create New Resume
            </a>
        </div>
    </div>

    <!-- Resumes Grid -->
    <div class="resumes-grid">
        <?php if (empty($resumes)): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 48px 24px;">
                <div style="margin-bottom: 16px; color: var(--muted);">
                    <svg aria-hidden="true" style="width:48px;height:48px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-doc"/></svg>
                </div>
                <h3 style="font-family:'Sora',sans-serif; font-size:1.1rem; font-weight:800; color:var(--brand-deep); margin-bottom:8px;">No resumes found</h3>
                <p style="font-size:0.86rem; color:var(--muted); margin-bottom:16px;">Start building your professional resume with AI assistance today.</p>
                <a href="<?= site_url('candidate/resumes/build') ?>" class="btn btn-primary">
                    Start Building
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($resumes as $resume): ?>
                <section class="card" aria-label="Resume card" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between; gap: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
                        <div>
                            <h4 style="font-family:'Sora',sans-serif; font-size:0.96rem; font-weight:800; color:var(--brand-deep); margin:0 0 4px;"><?= esc($resume->title) ?></h4>
                            <span style="font-size:0.76rem; color:var(--muted);">Last updated: <?= date('M d, Y', strtotime($resume->updated_at)) ?></span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-label="Action" style="padding: 6px 10px;">
                                Actions
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="border: 1px solid var(--border); box-shadow: var(--shadow); border-radius: 8px; padding: 6px;">
                                <li><a class="dropdown-item" href="<?= site_url('candidate/resumes/build/' . $resume->id) ?>" style="font-size:0.82rem; padding:8px 12px;">Edit</a></li>
                                <li><button class="dropdown-item text-danger delete-resume" data-id="<?= $resume->id ?>" style="font-size:0.82rem; padding:8px 12px; background:none; border:none; width:100%; text-align:left;">Delete</button></li>
                            </ul>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <a href="<?= site_url('candidate/resumes/download/' . $resume->id) ?>" class="btn btn-primary btn-block">
                             Download PDF
                        </a>
                    </div>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Dropdown toggle behavior in grid
    $('.dropdown-toggle').on('click', function(e) {
        e.stopPropagation();
        const menu = $(this).next('.dropdown-menu');
        $('.dropdown-menu').not(menu).removeClass('show');
        menu.toggleClass('show');
    });

    $(document).on('click', function() {
        $('.dropdown-menu').removeClass('show');
    });

    $('.delete-resume').on('click', function() {
        const id = $(this).attr('data-id');
        const card = $(this).closest('.card');
        
        if (confirm('Are you sure you want to delete this resume? This action cannot be undone.')) {
            $.ajax({
                url: '<?= base_url('candidate/resumes/delete') ?>/' + id,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(res) {
                    if (res.status === 'success') {
                        toastr.success(res.message);
                        card.fadeOut(400, function() {
                            $(this).remove();
                            if ($('.resumes-grid').children('.card').length === 0) {
                                location.reload();
                            }
                        });
                    } else {
                        toastr.error(res.message || 'Could not delete resume.');
                    }
                },
                error: function() {
                    toastr.error('Network error. Please try again.');
                }
            });
        }
    });
});
</script>
<?= $this->endSection() ?>
