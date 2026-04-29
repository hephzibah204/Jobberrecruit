<?= $this->extend('templates/app') ?>

<?= $this->section('styles') ?>
<style>
    .banner-image-single img {
        width: 50px;
        height: 300px;
        object-fit: cover;
        border-radius: 50%;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section-box mt-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                <div class="box-border-single">
                    <div class="row mt-10">
                        <div class="col-lg-8 col-md-12">
                            <h3><?= esc($job->title) ?> - <?= esc($job->job_type) ?></h3>
                            <div class="mt-0 mb-15">
                                <span class="card-briefcase"><?= esc(ucfirst($job->job_type)) ?></span>
                                <span class="card-time"><?= humanize_time($job->created_at) ?></span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 text-lg-end">
                            <div class="btn btn-apply-icon btn-apply btn-apply-big hover-up" data-bs-toggle="modal" data-bs-target="#ModalApplyJobForm">Apply now</div>
                            <!-- <a class="btn btn-border ms-2" href="<?= site_url('jobs/save/' . $job->id) ?>">Save job</a> -->
                        </div>
                    </div>
                    <div class="border-bottom pt-10 pb-10"></div>
                    <div class="banner-hero banner-image-single mt-10 mb-20">
                        <img src="<?= $job->company_logo ? base_url($job->company_logo) : 'assets/imgs/page/job-single-2/img.png' ?>" alt="JobberRecruit">
                    </div>
                    <div class="job-overview">
                        <h5 class="border-bottom pb-15 mb-30">Overview</h5>
                        <div class="row">
                            <div class="col-md-6 d-flex">
                                <div class="sidebar-icon-item"><img src="<?= base_url('assets/imgs/page/job-single/industry.svg'); ?>" alt="JobberRecruit"></div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description industry-icon mb-10">Industry</span>
                                    <strong class="small-heading"><?= esc($job->industry_name) ?></strong>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex mt-sm-15">
                                <div class="sidebar-icon-item"><img src="<?= base_url('assets/imgs/page/job-single/job-level.svg'); ?>" alt="JobberRecruit"></div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description joblevel-icon mb-10">Job level</span>
                                    <strong class="small-heading"><?= esc(ucfirst($job->experience_level)) ?> Level</strong>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-25">
                            <div class="col-md-6 d-flex mt-sm-15">
                                <div class="sidebar-icon-item"><img src="<?= base_url('assets/imgs/page/job-single/salary.svg'); ?>" alt="JobberRecruit"></div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description salary-icon mb-10">Salary</span>
                                    <strong class="small-heading"><?= esc($job->salary) ?: 'Negotiable' ?></strong>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex">
                                <div class="sidebar-icon-item"><img src="<?= base_url('assets/imgs/page/job-single/experience.svg'); ?>" alt="JobberRecruit"></div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description experience-icon mb-10">Experience</span>
                                    <strong class="small-heading"><?= esc($job->experience) ?? '3+' ?> years</strong>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-25">
                            <div class="col-md-6 d-flex mt-sm-15">
                                <div class="sidebar-icon-item"><img src="<?= base_url('assets/imgs/page/job-single/job-type.svg'); ?>" alt="JobberRecruit"></div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description jobtype-icon mb-10">Job type</span>
                                    <strong class="small-heading"><?= esc(ucfirst($job->job_type)) ?></strong>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex mt-sm-15">
                                <div class="sidebar-icon-item"><img src="<?= base_url('assets/imgs/page/job-single/deadline.svg'); ?>" alt="JobberRecruit"></div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description mb-10">Deadline</span>
                                    <strong class="small-heading"><?= date('d/m/Y', strtotime($job->deadline ?? 'now')) ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-25">
                            <div class="col-md-6 d-flex mt-sm-15">
                                <div class="sidebar-icon-item"><img src="<?= base_url('assets/imgs/page/job-single/updated.svg'); ?>" alt="JobberRecruit"></div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description jobtype-icon mb-10">Updated</span>
                                    <strong class="small-heading"><?= date('d/m/Y', strtotime($job->updated_at ?? 'now')) ?></strong>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex mt-sm-15">
                                <div class="sidebar-icon-item"><img src="<?= base_url('assets/imgs/page/job-single/location.svg'); ?>" alt="JobberRecruit"></div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description mb-10">Location</span>
                                    <strong class="small-heading"><?= esc($job->location) ?> <?= $job->work_arrangement ? ' (' . ucfirst($job->work_arrangement) . ')' : '' ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-single">
                        <h4>Welcome to <?= esc($job->employer_name) ?> Team</h4>
                        <p><?= esc($job->description) ?></p>
                        <h4>Essential Knowledge, Skills, and Experience</h4>
                        <ul>
                            <?php foreach (explode(',', $job->skills) as $skill): ?>
                                <li><?= esc(trim($skill)) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <h4>Preferred Experience</h4>
                        <ul>
                            <?php foreach (explode(',', $job->preferred_experience ?? '') as $exp): ?>
                                <li><?= esc(trim($exp)) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <h4>Requirements</h4>
                        <?= htmlspecialchars($job->requirements) ?>

                        <h4>Application Detail</h4>
                        <?= htmlspecialchars($job->application) ?>
                    </div>
                    <div class="author-single"><span><?= esc($job->employer_name) ?></span></div>
                    <div class="single-apply-jobs">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <a class="btn btn-default mr-15" href="#" data-bs-toggle="modal" data-bs-target="#ModalApplyJobForm">Apply now</a>
                                <a class="btn btn-border" href="<?= site_url('jobs/save/' . $job->id) ?>">Save job</a>
                            </div>
                            <div class="col-md-7 text-lg-end social-share">
                                <h6 class="color-text-paragraph-2 d-inline-block d-baseline mr-10">Share this</h6>
                                <a class="mr-5 d-inline-block d-middle" href="https://facebook.com/sharer.php?u=<?= urlencode(site_url('jobs/view/' . $job->id)) ?>"><img alt="JobberRecruit" src="<?= base_url('assets/imgs/template/icons/share-fb.svg'); ?>"></a>
                                <a class="mr-5 d-inline-block d-middle" href="https://twitter.com/intent/tweet?url=<?= urlencode(site_url('jobs/view/' . $job->id)) ?>&text=<?= urlencode($job->title) ?>"><img alt="JobberRecruit" src="<?= base_url('assets/imgs/template/icons/share-tw.svg'); ?>"></a>
                                <a class="mr-5 d-inline-block d-middle" href="https://reddit.com/submit?url=<?= urlencode(site_url('jobs/view/' . $job->id)) ?>"><img alt="JobberRecruit" src="<?= base_url('assets/imgs/template/icons/share-red.svg'); ?>"></a>
                                <a class="d-inline-block d-middle" href="https://wa.me/?text=<?= urlencode($job->title . ' ' . site_url('jobs/view/' . $job->id)) ?>"><img alt="JobberRecruit" src="<?= base_url('assets/imgs/template/icons/share-whatsapp.svg'); ?>"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-12 pl-40 pl-lg-15 mt-lg-30">
                <div class="sidebar-border">
                    <div class="sidebar-heading">
                        <div class="avatar-sidebar">
                            <figure><img class="company-logo" alt="JobberRecruit" src="<?= $job->company_logo ? base_url($job->company_logo) : 'assets/imgs/page/job-single/avatar.png' ?>"></figure>
                            <div class="sidebar-info">
                                <span class="sidebar-company"><?= esc($job->employer_name) ?></span>
                                <span class="card-location"><?= esc($job->location) ?></span>
                                <a class="link-underline mt-15" href="<?= site_url('company/' . $job->employer_id) ?>"><?= $employer_job_count ?> Open Jobs</a>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-list-job">
                        <div class="box-map">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2970.3150609575905!2d-87.6235655!3d41.886080899999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x880e2ca8b34afe61%3A0x6caeb5f721ca846!2s205%20N%20Michigan%20Ave%20Suit%20810%2C%20Chicago%2C%20IL%2060601%2C%20Hoa%20K%E1%BB%B3!5e0!3m2!1svi!2s!4v1658551322537!5m2!1svi!2s" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <ul class="ul-disc">
                            <li><?= esc($job->company_address ?? 'N/A') ?></li>
                            <li>Phone: <?= esc($job->company_phone ?? 'N/A') ?></li>
                            <li>Email: <?= esc($job->company_email ?? 'N/A') ?></li>
                        </ul>
                    </div>
                </div>
                <div class="sidebar-border">
                    <h6 class="f-18">Similar Jobs</h6>
                    <div class="sidebar-list-job">
                        <ul>
                            <?php foreach ($similar_jobs as $similar): ?>
                                <li>
                                    <div class="card-list-4 hover-up">
                                        <div class="image"><a href="<?= site_url('jobs/' . $similar->id) ?>"><img src="<?= $similar->company_logo ? base_url($similar->company_logo) : 'assets/imgs/brands/brand-1.png' ?>" alt="JobberRecruit"></a></div>
                                        <div class="info-text">
                                            <h5 class="font-md font-bold color-brand-1"><a href="<?= site_url('jobs/view/' . $similar->id) ?>"><?= esc($similar->title) ?></a></h5>
                                            <div class="mt-0"><span class="card-briefcase"><?= esc(ucfirst($similar->job_type)) ?></span><span class="card-time"><?= humanize_time($similar->created_at) ?></span></div>
                                            <div class="mt-5">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h6 class="card-price"><?= esc($job->salary) ?: 'Negotiable' ?><span>/<?= $similar->salary_period ?? 'Hour' ?></span></h6>
                                                    </div>
                                                    <div class="col-6 text-end"><span class="card-briefcase"><?= esc($similar->location) ?></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="sidebar-border">
                    <h6 class="f-18">Tags</h6>
                    <div class="sidebar-list-job">
                        <?php foreach (explode(',', $job->skills) as $skill): ?>
                            <a class="btn btn-grey-small bg-14 mb-10 mr-5" href="<?= site_url('jobs?keywords=' . urlencode(trim($skill))) ?>"><?= esc(trim($skill)) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-box mt-50 mb-50">
    <div class="container">
        <div class="text-left">
            <h2 class="section-title mb-10">Featured Jobs</h2>
            <p class="font-lg color-text-paragraph-2">Get the latest news, updates and tips</p>
        </div>
        <div class="mt-50">
            <div class="box-swiper style-nav-top">
                <div class="swiper-container swiper-group-4 swiper">
                    <div class="swiper-wrapper pb-10 pt-5">
                        <?php foreach ($featured_jobs as $featured): ?>
                            <div class="swiper-slide">
                                <div class="card-grid-2 hover-up">
                                    <div class="card-grid-2-image-left"><span class="flash"></span>
                                        <div class="image-box"><img src="<?= $featured->company_logo ? base_url($featured->company_logo) : 'assets/imgs/brands/brand-6.png' ?>" alt="JobberRecruit"></div>
                                        <div class="right-info"><a class="name-job" href="<?= site_url('company/' . $featured->employer_id) ?>"><?= esc($featured->employer_name) ?></a><span class="location-small"><?= esc($featured->location) ?></span></div>
                                    </div>
                                    <div class="card-block-info">
                                        <h6><a href="<?= site_url('jobs/view/' . $featured->id) ?>"><?= esc($featured->title) ?></a></h6>
                                        <div class="mt-5"><span class="card-briefcase"><?= esc(ucfirst($featured->job_type)) ?></span><span class="card-time"><?= humanize_time($featured->created_at) ?></span></div>
                                        <p class="font-sm color-text-paragraph mt-15"><?= esc(substr($featured->description, 0, 100)) ?>...</p>
                                        <div class="mt-30">
                                            <?php foreach (array_slice(explode(',', $featured->skills), 0, 3) as $skill): ?>
                                                <a class="btn btn-grey-small mr-5" href="<?= site_url('jobs?keywords=' . urlencode(trim($skill))) ?>"><?= esc(trim($skill)) ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="card-2-bottom mt-30">
                                            <div class="row">
                                                <div class="col-lg-7 col-7"><span class="card-text-price">$<?= number_format($featured->salary, 2) ?></span><span class="text-muted">/<?= $featured->salary_period ?? 'Hour' ?></span></div>
                                                <div class="col-lg-5 col-5 text-end">
                                                    <div class="btn btn-apply-now" data-bs-toggle="modal" data-bs-target="#ModalApplyJobForm">Apply now</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="swiper-button-next swiper-button-next-4"></div>
                <div class="swiper-button-prev swiper-button-prev-4"></div>
            </div>
            <div class="text-center"><a class="btn btn-grey" href="<?= site_url('jobs') ?>">Load more posts</a></div>
        </div>
    </div>
</section>

<!-- Apply Job Modal -->
<div class="modal fade" id="ModalApplyJobForm" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Apply for <?= esc($job->title) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="applyJobForm" action="<?= site_url('jobs/apply/' . $job->id) ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cover Letter</label>
                        <textarea class="form-control" name="cover_letter" rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Resume (PDF)</label>
                        <input type="file" class="form-control" name="resume" accept=".pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#applyJobForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var submitBtn = form.find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('Submitting...');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        alert('Application submitted successfully!');
                        $('#ModalApplyJobForm').modal('hide');
                    } else {
                        alert('Error: ' + (response.message || 'Something went wrong'));
                    }
                },
                error: function() {
                    alert('Network error. Please try again.');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text('Submit Application');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>