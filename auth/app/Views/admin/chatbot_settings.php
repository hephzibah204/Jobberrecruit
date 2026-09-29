<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4><i class="ti ti-message-chatbot me-2"></i>Chatbot Management</h4>
            <h6>Configure chatbot settings and behavior</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Chatbot Settings</h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <form method="POST" action="<?= base_url('admin/chatbot/save') ?>">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="chatbot_enabled" id="chatbot_enabled" <?= $chatbot_enabled ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="chatbot_enabled">
                                    Enable Chatbot
                                </label>
                            </div>
                            <small class="text-muted">Turn the chatbot on or off across the platform</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Welcome Message</label>
                            <textarea name="chatbot_welcome_message" class="form-control" rows="3" placeholder="Hello! How can I help you today?"><?= esc($chatbot_welcome_message) ?></textarea>
                            <small class="text-muted">This message appears when users first open the chatbot</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Quick Suggestions</label>
                            <textarea name="chatbot_suggestions" class="form-control" rows="3" placeholder="Browse Jobs,Post a Job,Resume Builder,Career Advice"><?= esc($chatbot_suggestions) ?></textarea>
                            <small class="text-muted">Comma-separated list of suggested questions</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i>Save Settings
                            </button>
                            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Chatbot Info</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar bg-primary-transparent me-3">
                            <i class="ti ti-message-chatbot text-primary fs-20"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">AI Support Chatbot</h6>
                            <p class="text-muted small mb-0">Powered by AI</p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-semibold mb-3">Features:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i>24/7 automated support</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Context-aware responses</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Platform knowledge base</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Multi-language support</li>
                    </ul>

                    <div class="alert alert-info small">
                        <i class="ti ti-info-circle me-1"></i>
                        Changes to chatbot settings take effect immediately after saving.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
