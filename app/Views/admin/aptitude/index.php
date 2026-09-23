<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Aptitude Tests</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Aptitude Tests</li>
                </ol>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('admin/aptitude/create') ?>" class="btn btn-primary btn-wave d-inline-flex align-items-center">
                    <i class="ri-add-line me-1"></i> Create Test with Builder
                </a>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">Aptitude Test Templates</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-nowrap table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Test Title</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Questions</th>
                            <th scope="col">Pass Threshold</th>
                            <th scope="col">Difficulty</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($tests)): ?>
                            <?php foreach ($tests as $test): 
                                $tId = is_array($test) ? $test['id'] : $test->id;
                                $tTitle = is_array($test) ? $test['title'] : $test->title;
                                $tDesc = is_array($test) ? ($test['description'] ?? '') : ($test->description ?? '');
                                $tDuration = is_array($test) ? ($test['duration_mins'] ?? 20) : ($test->duration_mins ?? 20);
                                $tQuestions = is_array($test) ? ($test['question_count'] ?? $test['num_questions'] ?? 0) : ($test->question_count ?? $test->num_questions ?? 0);
                                $tPass = is_array($test) ? ($test['pass_threshold'] ?? 50) : ($test->pass_threshold ?? 50);
                                $tDiff = is_array($test) ? ($test['difficulty'] ?? 'medium') : ($test->difficulty ?? 'medium');
                            ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <span class="fw-semibold d-block"><?= esc($tTitle) ?></span>
                                                <span class="text-muted fs-11"><?= esc($tDesc ?: 'No description') ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($tDuration) ?> mins</td>
                                    <td><span class="badge bg-primary-transparent"><?= esc($tQuestions) ?> Questions</span></td>
                                    <td><?= esc($tPass) ?>%</td>
                                    <td>
                                        <?php 
                                        $difficulty = strtolower($tDiff);
                                        $badgeClass = 'bg-secondary';
                                        if ($difficulty === 'easy') $badgeClass = 'bg-success';
                                        elseif ($difficulty === 'medium') $badgeClass = 'bg-warning';
                                        elseif ($difficulty === 'hard') $badgeClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst($difficulty) ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <button type="button" class="btn btn-sm btn-success-light btn-wave" 
                                                    onclick="openShareTestModal('<?= esc(is_array($test) ? $test['title'] : $test->title, 'js') ?>', '<?= base_url('aptitude/' . (is_array($test) ? $test['slug'] : $test->slug) . '/start') ?>', '<?= base_url('aptitude/' . (is_array($test) ? $test['slug'] : $test->slug) . '/practice') ?>')" 
                                                    title="Share Test Link">
                                                <i class="ti ti-share me-1"></i> Share Link
                                            </button>
                                            <a href="<?= base_url('admin/aptitude/edit/' . $tId) ?>" class="btn btn-sm btn-info-light btn-wave" title="Edit Test &amp; Questions">
                                                <i class="ti ti-edit me-1"></i> Edit Test
                                            </a>
                                            <a href="<?= base_url('admin/aptitude/import/' . $tId) ?>" class="btn btn-sm btn-secondary-light btn-wave" title="Import CSV Questions">
                                                <i class="ti ti-upload me-1"></i> CSV Import
                                            </a>
                                            <form action="<?= base_url('admin/aptitude/delete/' . $tId) ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this test?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger-light btn-wave" title="Delete Test">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">No tests configured yet. Click "Create Test with Builder" above to configure your first test.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Share Test Modal -->
<div class="modal fade" id="shareTestModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="shareModalTitle"><i class="ti ti-share me-2 text-primary"></i>Share Test Link</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted fs-13 mb-3" id="shareModalDesc">Copy and share the test assessment link with candidates or post it on job boards.</p>
        
        <div class="mb-3">
          <label class="form-label fw-semibold fs-12 text-uppercase text-muted">Official Candidate Assessment Link</label>
          <div class="input-group">
            <input type="text" class="form-control fw-medium" id="shareOfficialUrl" readonly>
            <button class="btn btn-primary btn-wave" type="button" onclick="copyInputValue('shareOfficialUrl', this)">
              <i class="ti ti-copy me-1"></i> Copy Link
            </button>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold fs-12 text-uppercase text-muted">Free Practice Mode Link</label>
          <div class="input-group">
            <input type="text" class="form-control fw-medium" id="sharePracticeUrl" readonly>
            <button class="btn btn-outline-secondary btn-wave" type="button" onclick="copyInputValue('sharePracticeUrl', this)">
              <i class="ti ti-copy me-1"></i> Copy Link
            </button>
          </div>
        </div>

        <div class="d-flex gap-2 justify-content-center mt-4">
          <a id="shareWhatsappBtn" href="#" target="_blank" class="btn btn-success btn-wave btn-sm d-inline-flex align-items-center">
            <i class="ri-whatsapp-line me-1"></i> Share WhatsApp
          </a>
          <a id="shareEmailBtn" href="#" target="_blank" class="btn btn-danger btn-wave btn-sm d-inline-flex align-items-center">
            <i class="ri-mail-send-line me-1"></i> Share Email
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function openShareTestModal(title, officialUrl, practiceUrl) {
    document.getElementById('shareModalTitle').innerHTML = '<i class="ti ti-share me-2 text-primary"></i>Share: ' + title;
    document.getElementById('shareOfficialUrl').value = officialUrl;
    document.getElementById('sharePracticeUrl').value = practiceUrl;

    var shareText = encodeURIComponent('Complete the ' + title + ' assessment on JobberRecruit:\n' + officialUrl);
    document.getElementById('shareWhatsappBtn').href = 'https://api.whatsapp.com/send?text=' + shareText;
    document.getElementById('shareEmailBtn').href = 'mailto:?subject=' + encodeURIComponent('Aptitude Test Invitation: ' + title) + '&body=' + shareText;

    var modal = new bootstrap.Modal(document.getElementById('shareTestModal'));
    modal.show();
}

function copyInputValue(inputId, btn) {
    var input = document.getElementById(inputId);
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(function() {
        var originalText = btn.innerHTML;
        btn.innerHTML = '<i class="ti ti-check me-1"></i> Copied!';
        btn.classList.add('btn-success');
        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
        }, 2000);
    });
}
</script>

<?= $this->endSection() ?>
