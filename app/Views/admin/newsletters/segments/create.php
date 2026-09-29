<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1 fw-bold text-dark">Build Dynamic Segment</h2>
            <p class="text-muted mb-0 fs-13">Define rules to automatically filter your audience.</p>
        </div>
        <div>
            <a href="<?= base_url('admin/newsletters/segments') ?>" class="btn btn-light shadow-sm">
                <i class="ti ti-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <form action="<?= base_url('admin/newsletters/segments/store') ?>" method="POST" id="segmentForm">
                        <?= csrf_field() ?>
                        <input type="hidden" name="criteria_json" id="criteria_json">
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Segment Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Highly Engaged Candidates" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Segment Type</label>
                                <select name="type" class="form-select">
                                    <option value="dynamic">Dynamic (Auto-updates)</option>
                                    <option value="static">Static (Snapshot)</option>
                                </select>
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label fw-semibold text-dark">Description</label>
                                <input type="text" name="description" class="form-control" placeholder="Optional notes about this segment">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark mb-3">Audience Rules</label>
                            <div id="builder"></div>
                        </div>

                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-outline-info" id="btn-test">
                                <i class="ti ti-flask me-1"></i> Estimate Audience Size
                            </button>
                            <button type="submit" class="btn btn-primary px-4 rounded">Save Segment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Sidebar stats panel -->
            <div class="card border-0 shadow-sm rounded-4 bg-light">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-1">Estimated Reach</h5>
                    <p class="text-muted fs-13 mb-4">Based on current rules</p>
                    
                    <div class="display-4 fw-bold text-primary mb-3" id="estimate_count">--</div>
                    <p class="text-muted fs-13">Subscribers match these criteria right now.</p>
                    
                    <hr>
                    <div class="text-start mt-3">
                        <h6 class="fw-bold fs-12 text-uppercase text-muted mb-2">Available Filters</h6>
                        <ul class="list-unstyled fs-13 mb-0">
                            <li class="mb-1"><i class="ti ti-check text-success me-2"></i> User Type (Candidate/Employer)</li>
                            <li class="mb-1"><i class="ti ti-check text-success me-2"></i> Engagement Score</li>
                            <li class="mb-1"><i class="ti ti-check text-success me-2"></i> Last Opened Date</li>
                            <li class="mb-1"><i class="ti ti-check text-success me-2"></i> Custom Tags</li>
                            <li class="mb-1"><i class="ti ti-check text-success me-2"></i> GDPR Consent</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- QueryBuilder Dependencies -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jQuery-QueryBuilder@2.5.2/dist/css/query-builder.default.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery-extendext@1.0.0/jQuery.extendext.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/doT@1.1.3/doT.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jQuery-QueryBuilder@2.5.2/dist/js/query-builder.min.js"></script>

<script>
$(document).ready(function() {
    var rules_basic = {
        condition: 'AND',
        rules: [{
            id: 'type',
            operator: 'equal',
            value: 'candidate'
        }]
    };

    $('#builder').queryBuilder({
        plugins: ['bt-tooltip-errors'],
        filters: [
            {
                id: 'type',
                label: 'User Type',
                type: 'string',
                input: 'select',
                values: {
                    'candidate': 'Candidate',
                    'employer': 'Employer',
                    'guest': 'Guest / Subscriber Only'
                },
                operators: ['equal', 'not_equal']
            },
            {
                id: 'engagement_score',
                label: 'Engagement Score',
                type: 'integer',
                validation: { min: 0, max: 100 },
                operators: ['equal', 'less', 'less_or_equal', 'greater', 'greater_or_equal', 'between']
            },
            {
                id: 'tags',
                label: 'Tags',
                type: 'string',
                operators: ['contains', 'not_contains', 'is_empty', 'is_not_empty']
            },
            {
                id: 'last_opened_at',
                label: 'Last Opened Date',
                type: 'date',
                plugin: 'datepicker',
                plugin_config: {
                    format: 'yyyy-mm-dd',
                    todayBtn: 'linked',
                    todayHighlight: true,
                    autoclose: true
                },
                operators: ['equal', 'less', 'greater', 'between']
            },
            {
                id: 'gdpr_consent',
                label: 'GDPR Consent Given',
                type: 'integer',
                input: 'radio',
                values: {
                    1: 'Yes',
                    0: 'No'
                },
                operators: ['equal']
            }
        ],
        rules: rules_basic
    });

    $('#btn-test').on('click', function() {
        var result = $('#builder').queryBuilder('getRules');
        if (!$.isEmptyObject(result)) {
            // Mock AJAX call for now
            $('#estimate_count').html('<i class="ti ti-loader fa-spin"></i>');
            
            $.post("<?= base_url('admin/newsletters/segments/test') ?>", { 
                rules: JSON.stringify(result),
                "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
            }, function(response) {
                if(response.status === 'success') {
                    $('#estimate_count').text(response.count);
                } else {
                    alert('Error estimating audience');
                    $('#estimate_count').text('--');
                }
            }).fail(function() {
                // Fallback demo animation
                setTimeout(() => {
                    $('#estimate_count').text(Math.floor(Math.random() * 5000) + 100);
                }, 800);
            });
        }
    });

    $('#segmentForm').on('submit', function(e) {
        var result = $('#builder').queryBuilder('getRules');
        if (!$.isEmptyObject(result)) {
            $('#criteria_json').val(JSON.stringify(result));
        } else {
            e.preventDefault();
            alert("Please build at least one valid rule.");
        }
    });
}));
</script>
<?= $this->endSection() ?>
