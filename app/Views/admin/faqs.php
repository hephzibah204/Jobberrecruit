<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    .faq-answer {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">FAQs</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">FAQs</li>
                </ol>
            </div>

            <button class="btn btn-primary" onclick="openFaqModal()">
                <i class="ti ti-plus"></i> New FAQ
            </button>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card custom-card">
        <div class="card-body p-0 mb-2">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Category</th>
                            <th>Sort Order</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($faqs)): ?>
                            <?php foreach ($faqs as $faq): ?>
                                <tr>
                                    <td>
                                        <div class="fw-medium"><?= esc($faq->question) ?></div>
                                        <div class="text-muted small faq-answer">
                                            <?= esc(character_limiter($faq->answer, 90)) ?>
                                        </div>
                                    </td>

                                    <td><?= esc($faq->category ?? '-') ?></td>

                                    <td><?= (int) $faq->sort_order ?></td>

                                    <td>
                                        <span class="badge bg-<?= $faq->is_active ? 'success' : 'secondary' ?>-transparent">
                                            <?= $faq->is_active ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>

                                    <td><?= $faq->updated_at ? date('M d, Y', strtotime($faq->updated_at)) : '-' ?></td>

                                    <td class="text-center">
                                        <button
                                            class="btn btn-sm btn-light border me-1"
                                            onclick="openFaqModal(<?= esc(json_encode($faq), 'attr') ?>)">
                                            <i class="ti ti-edit"></i>
                                        </button>

                                        <button
                                            class="btn btn-sm btn-danger border"
                                            onclick="deleteFaq(<?= $faq->id ?>)">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No FAQs available. The public FAQ page is showing its built-in questions until you add some here.
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?= $pager->links('default', 'admin_pagination') ?>
    </div>

</div>

<!-- MODAL -->
<div class="modal fade" id="faqModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="faqForm">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="faq_id">

            <div class="modal-header">
                <h5 class="modal-title">FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-3">

                <div class="col-12">
                    <label class="form-label">Question *</label>
                    <input type="text" class="form-control" name="question" id="faq_question" maxlength="500" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Answer *</label>
                    <textarea class="form-control" name="answer" id="faq_answer" rows="4" required></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <input type="text" class="form-control" name="category" id="faq_category" list="faqCategoryList" maxlength="100">
                    <datalist id="faqCategoryList">
                        <?php foreach ($faqCategories ?? [] as $categoryOption): ?>
                            <option value="<?= esc($categoryOption, 'attr') ?>"></option>
                        <?php endforeach ?>
                    </datalist>
                    <div class="form-text">Pick one of the FAQ page's section names to group it there, or a page name (CV Review, Employers, Recruitment, Webinars, Candidate Hub, Homepage, Training, Candidate Pricing) to manage that page's FAQ list. Any other category is shown under "General Inquiries &amp; Support".</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" class="form-control" name="sort_order" id="faq_sort_order" value="0">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status *</label>
                    <select class="form-select" name="is_active" id="faq_is_active">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="faqSubmitBtn">
                    Save FAQ
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right'
    };

    const faqModal = new bootstrap.Modal('#faqModal');

    function openFaqModal(data = null) {
        document.getElementById('faqForm').reset();
        document.getElementById('faq_id').value = '';
        document.getElementById('faq_sort_order').value = 0;
        document.getElementById('faq_is_active').value = '1';

        if (data) {
            Object.keys(data).forEach(key => {
                const el = document.getElementById(`faq_${key}`);
                if (el) el.value = data[key];
            });
        }

        faqModal.show();
    }

    document.getElementById('faqForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('faqSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = 'Saving...';

        fetch("<?= base_url('admin/faqs/save') ?>", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(this)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message);
                    setTimeout(() => location.reload(), 700);
                } else {
                    toastr.error(res.message || 'Save failed');
                }
            })
            .catch(() => toastr.error('Server error'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Save FAQ';
            });
    });

    function deleteFaq(id) {
        if (!confirm('Delete this FAQ?')) return;

        fetch(`<?= base_url('admin/faqs/delete') ?>/${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message);
                    setTimeout(() => location.reload(), 600);
                } else {
                    toastr.error(res.message);
                }
            })
            .catch(() => toastr.error('Server error'));
    }
</script>

<?= $this->endSection() ?>
