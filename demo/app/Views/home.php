<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>
<section class="section-box">
    <div class="banner-hero hero-1 banner-homepage4">
        <div class="banner-inner">
            <div class="row">
                <div class="col-xl-7 col-lg-12">
                    <div class="block-banner">
                        <h1 class="heading-banner wow animate__animated animate__fadeInUp text-gradient">
                            Get The <span class="color-brand-2">Right Job</span><br class="d-none d-lg-block">You Deserve
                        </h1>
                        <div class="banner-description mt-20 wow animate__animated animate__fadeInUp text-muted" data-wow-delay=".1s">
                            Each month, more than 3 million job seekers turn to our website in their search for work,<br class="d-none d-lg-block">
                            making over 140,000 applications every single day
                        </div>

                        <div class="form-find mt-40 wow animate__animated animate__fadeIn glass-card p-4" data-wow-delay=".2s">
                            <form method="GET" action="<?= base_url('jobs') ?>" id="job-search-form">
                                <div class="box-industry">
                                    <select class="form-input mr-10 select-active input-industry" name="industry_id">
                                        <option value="">Select Industry</option>
                                        <?php foreach ($industries as $industry): ?>
                                            <option value="<?= esc($industry->id) ?>"><?= esc($industry->name) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <select class="form-input mr-10 select-active" name="state_id" id="state">
                                    <option value="">Select Location</option>
                                    <?php foreach ($states as $state): ?>
                                        <option value="<?= esc($state->id) ?>"><?= esc($state->name) ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <select class="form-input mr-10 select-active" name="experience_level" id="experience_level">
                                    <option value="">Select Experience Level</option>
                                    <option value="entry">Entry Level</option>
                                    <option value="mid">Mid Level</option>
                                    <option value="senior">Senior Level</option>
                                    <option value="executive">Executive</option>
                                </select>

                                <button type="submit" class="btn btn-default btn-find font-sm" id="search-btn">Search</button>
                            </form>
                        </div>

                        <div class="list-tags-banner mt-60 wow animate__animated animate__fadeInUp" data-wow-delay=".3s">
                            <strong>Popular Searches:</strong>
                            <a href="<?= site_url('jobs?experience_level=entry') ?>">Entry Level</a>,
                            <a href="<?= site_url('jobs?experience_level=mid') ?>">Mid Level</a>,
                            <a href="<?= site_url('jobs?experience_level=senior') ?>">Senior</a>,
                            <a href="<?= site_url('jobs?experience_level=executive') ?>">Executive</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-12 d-none d-xl-block col-md-6">
                    <div class="banner-imgs">
                        <div class="block-1 shape-1"><img class="img-responsive" alt="jobBox" src="assets/imgs/page/homepage4/banner.png"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-box mt-110">
    <div class="section-box wow animate__animated animate__fadeIn mt-70">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp text-gradient">Latest Jobs Post</h2>
                <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">Explore the different types of available jobs to apply<br class="d-none d-lg-block">discover which is right for you.</p>
                <div class="list-tabs list-tabs-2 mt-30">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php $first = true; ?>
                        <?php foreach ($categories as $category): ?>
                            <?php if ($category->job_count > 0): ?>
                                <li>
                                    <a class="<?= $first ? 'active' : '' ?>" id="nav-tab-job-<?= $category->id ?>" href="#tab-job-<?= $category->id ?>" data-bs-toggle="tab" role="tab" aria-controls="tab-job-<?= $category->id ?>" aria-selected="<?= $first ? 'true' : 'false' ?>">
                                        <img src="<?= base_url('assets/imgs/template/favicon.png') ?>" alt="JobberRecruit"> <?= esc($category->name) ?>
                                    </a>
                                </li>
                                <?php $first = false; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="mt-10">
                <div class="tab-content" id="myTabContent-1">
                    <?php $first = true; ?>
                    <?php foreach ($categories as $category): ?>
                        <?php if ($category->job_count > 0): ?>
                            <div class="tab-pane fade <?= $first ? 'show active' : '' ?>" id="tab-job-<?= $category->id ?>" role="tabpanel" aria-labelledby="nav-tab-job-<?= $category->id ?>">
                                <div class="row" id="job-list-<?= $category->id ?>">
                                    <?php $jobCount = 0; ?>
                                    <?php foreach ($jobs as $job): ?>
                                        <?php if ($job->category_id == $category->id && $jobCount < 6): ?>
                                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                                                <div class="glass-card p-4 hover-up mb-4">
                                                     <div class="card-grid-2-image">
                                                        <span class="lbl-hot <?= $job->job_type == 'freelance' ? 'bg-green' : '' ?>">
                                                            <span><?= ucfirst($job->job_type) ?></span>
                                                        </span>
                                                        <div class="image-box">
                                                            <figure>
                                                                <img src="<?= $job->company_logo ? base_url($job->company_logo) : base_url($website_logo) ?>" alt="JobberRecruit">
                                                            </figure>
                                                        </div>
                                                    </div>
                                                    <div class="card-block-info">
                                                        <h5>
                                                            <a href="<?= site_url('jobs/view/' . $job->id) ?>"><?= esc($job->title) ?></a>
                                                            <?php if ($job->is_verified): ?>
                                                                <span class="badge-verified ms-2" title="Verified Employer">
                                                                    <i class="ti ti-circle-check-filled"></i> Verified
                                                                </span>
                                                            <?php endif; ?>
                                                        </h5>
                                                        <div class="mt-5">
                                                            <span class="card-location mr-15"><?= esc($job->location) ?: 'Remote' ?></span>
                                                            <span class="card-time"><?= humanize_time($job->created_at) ?></span>
                                                        </div>
                                                        <div class="card-2-bottom mt-20">
                                                            <div class="row">
                                                                <div class="col-xl-7 col-md-7 mb-2">
                                                                    <?php if (!empty($job->skills)): ?>
                                                                        <?php foreach (explode(',', $job->skills) as $skill): ?>
                                                                            <a class="btn btn-tags-sm mr-5" href="<?= site_url('jobs?skill=' . urlencode(trim($skill))) ?>"><?= esc(trim($skill)) ?></a>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="col-xl-5 col-md-5 text-lg-end">
                                                                    <span class="card-text-price"><?= esc($job->salary) ?: 'Negotiable' ?></span>
                                                                    <span class="text-muted"><?= $job->salary ? '/' . esc($job->salary_period) : '' ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="font-sm color-text-paragraph mt-20">
                                                            <?= esc(strip_tags(substr($job->description, 0, 100))) . '...' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $jobCount++; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                                <?php if ($category->job_count > 6): ?>
                                    <div class="text-center mt-10">
                                        <a class="btn btn-brand-1 btn-icon-more hover-up" href="javascript:void(0);" data-category-id="<?= $category->id ?>" data-offset="6">See more</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php $first = false; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-box mt-110 bg-cat">
    <div class="section-box wow animate__animated animate__fadeIn mt-70">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp text-gradient">Browse by category</h2>
                <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp text-muted">Find the job that's perfect for you. about 800+ new jobs everyday</p>
            </div>
            <div class="box-swiper mt-50">
                <div class="swiper-container swiper-group-5 swiper">
                    <div class="swiper-wrapper pb-70 pt-5">
                        <?php
                        // Group categories into pairs for the swiper slides
                        $categoryPairs = array_chunk($categories, 2);
                        foreach ($categoryPairs as $pair):
                        ?>
                            <div class="swiper-slide hover-up">
                                <?php foreach ($pair as $category):
                                    $jobCount = $categoryJobs[$category->id] ?? 0;
                                    $iconPath = getCategoryIcon($category->name);
                                ?>
                                    <a href="<?= site_url('jobs-list?category=' . $category->id) ?>">
                                        <div class="item-logo glass-card p-3 mb-2">
                                            <div class="text-info-right">
                                                <h6><?= esc($category->name) ?></h6>
                                                <p class="font-xs text-muted"><?= $category->job_count ?><span> Jobs Available</span></p>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div> -->
            </div>
        </div>
    </div>
</section>
<section class="section-box mt-0">
    <div class="section-box wow animate__animated animate__fadeIn">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">See Some Words</h2>
                <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">Thousands of employee get their ideal jobs<br class="d-none d-lg-block">and feed back to us!</p>
            </div>
            <div class="box-swiper mt-50">
                <div class="swiper-container swiper-group-4-border swiper">
                    <div class="swiper-wrapper pb-70 pt-5">
                        <div class="swiper-slide hover-up">
                            <div class="card-review-1">
                                <div class="image-review"> <img src="assets/imgs/page/homepage4/user.png" alt="jobBox"></div>
                                <div class="review-info">
                                    <div class="review-name">
                                        <h5>Ellis Kim</h5><span class="font-xs">Digital Artist</span>
                                    </div>
                                    <div class="review-rating"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"></div>
                                    <div class="review-comment">Sit amet, consectetur adipiscing elit, sed do eiusmod tempor incidid unt. Labore et dolore nostrud temp exercitation.</div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide hover-up">
                            <div class="card-review-1">
                                <div class="image-review"> <img src="assets/imgs/page/homepage4/user2.png" alt="jobBox"></div>
                                <div class="review-info">
                                    <div class="review-name">
                                        <h5>John Smith</h5><span class="font-xs">Product designer</span>
                                    </div>
                                    <div class="review-rating"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"></div>
                                    <div class="review-comment">Sit amet, consectetur adipiscing elit, sed do eiusmod tempor incidid unt. Labore et dolore nostrud temp exercitation.</div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide hover-up">
                            <div class="card-review-1">
                                <div class="image-review"> <img src="assets/imgs/page/homepage4/user3.png" alt="jobBox"></div>
                                <div class="review-info">
                                    <div class="review-name">
                                        <h5>Sayen Ahmod</h5><span class="font-xs">Developer</span>
                                    </div>
                                    <div class="review-rating"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"></div>
                                    <div class="review-comment">Sit amet, consectetur adipiscing elit, sed do eiusmod tempor incidid unt. Labore et dolore nostrud temp exercitation.</div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide hover-up">
                            <div class="card-review-1">
                                <div class="image-review"> <img src="assets/imgs/page/homepage4/user4.png" alt="jobBox"></div>
                                <div class="review-info">
                                    <div class="review-name">
                                        <h5>Tayla Swef</h5><span class="font-xs">Graphic designer</span>
                                    </div>
                                    <div class="review-rating"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"></div>
                                    <div class="review-comment">Sit amet, consectetur adipiscing elit, sed do eiusmod tempor incidid unt. Labore et dolore nostrud temp exercitation.</div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide hover-up">
                            <div class="card-review-1">
                                <div class="image-review"> <img src="assets/imgs/page/homepage4/user.png" alt="jobBox"></div>
                                <div class="review-info">
                                    <div class="review-name">
                                        <h5>Ellis Kim</h5><span class="font-xs">Digital Artist</span>
                                    </div>
                                    <div class="review-rating"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"> <img src="assets/imgs/template/icons/star.svg" alt="jobBox"></div>
                                    <div class="review-comment">Sit amet, consectetur adipiscing elit, sed do eiusmod tempor incidid unt. Labore et dolore nostrud temp exercitation.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination swiper-pagination-style-border"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="section-box mt-70">
    <div class="container">
        <div class="box-trust">
            <div class="row">
                <div class="left-trust col-lg-2 col-md-3 col-sm-3 col-3">
                    <h4 class="color-text-paragraph-2">Trusted by</h4>
                </div>
                <div class="right-logos col-lg-10 col-md-9 col-sm-9 col-9">
                    <div class="box-swiper">
                        <div class="swiper-container swiper-group-7 swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide"><a href="#"><img src="assets/imgs/page/homepage3/microsoft.svg" alt="jobBox"></a></div>
                                <div class="swiper-slide"><a href="#"><img src="assets/imgs/page/homepage3/sony.svg" alt="jobBox"></a></div>
                                <div class="swiper-slide"><a href="#"><img src="assets/imgs/page/homepage3/acer.svg" alt="jobBox"></a></div>
                                <div class="swiper-slide"><a href="#"><img src="assets/imgs/page/homepage3/nokia.svg" alt="jobBox"></a></div>
                                <div class="swiper-slide"><a href="#"><img src="assets/imgs/page/homepage3/asus.svg" alt="jobBox"></a></div>
                                <div class="swiper-slide"><a href="#"><img src="assets/imgs/page/homepage3/casio.svg" alt="jobBox"></a></div>
                                <div class="swiper-slide"><a href="#"><img src="assets/imgs/page/homepage3/dell.svg" alt="jobBox"></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="section-box mt-70">
    <div class="section-box wow animate__animated animate__fadeIn">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">Popular category</h2>
                <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">Search and connect with the right candidates faster.</p>
            </div>
            <div class="box-swiper mt-50 layout-brand-1">
                <div class="swiper-container swiper-group-6 mh-none swiper">
                    <div class="swiper-wrapper pb-70 pt-5">
                        <div class="swiper-slide hover-up">
                            <div class="card-grid-5 card-category hover-up" style="background-image: url('assets/imgs/page/homepage4/img-big1.png')"><a href="jobs-grid">
                                    <div class="box-cover-img">
                                        <div class="content-bottom">
                                            <h6 class="color-white mb-5">Marketing &amp; Sales</h6>
                                            <p class="color-white font-xs"><span>123</span><span> Jobs Available</span></p>
                                        </div>
                                    </div>
                                </a></div>
                        </div>
                        <div class="swiper-slide hover-up">
                            <div class="card-grid-5 card-category hover-up" style="background-image: url('assets/imgs/page/homepage4/img-big2.png')"><a href="jobs-grid">
                                    <div class="box-cover-img">
                                        <div class="content-bottom">
                                            <h6 class="color-white mb-5">Human resource</h6>
                                            <p class="color-white font-xs"><span>154</span><span> Jobs Available</span></p>
                                        </div>
                                    </div>
                                </a></div>
                        </div>
                        <div class="swiper-slide hover-up">
                            <div class="card-grid-5 card-category hover-up" style="background-image: url('assets/imgs/page/homepage4/img-big3.png')"><a href="jobs-grid">
                                    <div class="box-cover-img">
                                        <div class="content-bottom">
                                            <h6 class="color-white mb-5">Finance</h6>
                                            <p class="color-white font-xs"><span>546</span><span> Jobs Available</span></p>
                                        </div>
                                    </div>
                                </a></div>
                        </div>
                        <div class="swiper-slide hover-up">
                            <div class="card-grid-5 card-category hover-up" style="background-image: url('assets/imgs/page/homepage4/img-big4.png')"><a href="jobs-grid">
                                    <div class="box-cover-img">
                                        <div class="content-bottom">
                                            <h6 class="color-white mb-5">Market Research</h6>
                                            <p class="color-white font-xs"><span>24</span><span> Jobs Available</span></p>
                                        </div>
                                    </div>
                                </a></div>
                        </div>
                        <div class="swiper-slide hover-up">
                            <div class="card-grid-5 card-category hover-up" style="background-image: url('assets/imgs/page/homepage4/img-big5.png')"><a href="jobs-grid">
                                    <div class="box-cover-img">
                                        <div class="content-bottom">
                                            <h6 class="color-white mb-5">Furniture design</h6>
                                            <p class="color-white font-xs"><span>54</span><span> Jobs Available</span></p>
                                        </div>
                                    </div>
                                </a></div>
                        </div>
                        <div class="swiper-slide hover-up">
                            <div class="card-grid-5 card-category hover-up" style="background-image: url('assets/imgs/page/homepage4/img-big6.png')"><a href="jobs-grid">
                                    <div class="box-cover-img">
                                        <div class="content-bottom">
                                            <h6 class="color-white mb-5">Content writer</h6>
                                            <p class="color-white font-xs"><span>87</span><span> Jobs Available</span></p>
                                        </div>
                                    </div>
                                </a></div>
                        </div>
                    </div>
                </div>
                <div class="swiper-button-next swiper-button-next-1"></div>
                <div class="swiper-button-prev swiper-button-prev-1"></div>
            </div>
        </div>
    </div>
</section>
<section class="section-box mt-80">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">Jobs by Location</h2>
            <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">Find your favourite jobs and get the benefits of yourself</p>
        </div>
    </div>
    <div class="container">
        <div class="row mt-50">
            <div class="col-xl-3 col-lg-3 col-md-5 col-sm-12 col-12">
                <div class="card-image-top hover-up"><a href="jobs-grid">
                        <div class="image" style="background-image: url(assets/imgs/page/homepage1/location1.png);"><span class="lbl-hot">Hot</span></div>
                    </a>
                    <div class="informations"><a href="jobs-grid">
                            <h5>Paris, France</h5>
                        </a>
                        <div class="row">
                            <div class="col-lg-6 col-6"><span class="text-14 color-text-paragraph-2">5 Vacancy</span></div>
                            <div class="col-lg-6 col-6 text-end"><span class="color-text-paragraph-2 text-14">120 companies</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-7 col-sm-12 col-12">
                <div class="card-image-top hover-up"><a href="jobs-grid">
                        <div class="image" style="background-image: url(assets/imgs/page/homepage1/location2.png);"><span class="lbl-hot">Trending</span></div>
                    </a>
                    <div class="informations"><a href="jobs-grid">
                            <h5>London, England</h5>
                        </a>
                        <div class="row">
                            <div class="col-lg-6 col-6"><span class="text-14 color-text-paragraph-2">7 Vacancy</span></div>
                            <div class="col-lg-6 col-6 text-end"><span class="color-text-paragraph-2 text-14">68 companies</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-7 col-sm-12 col-12">
                <div class="card-image-top hover-up"><a href="jobs-grid">
                        <div class="image" style="background-image: url(assets/imgs/page/homepage1/location3.png);"><span class="lbl-hot">Hot</span></div>
                    </a>
                    <div class="informations"><a href="jobs-grid">
                            <h5>New York, USA</h5>
                        </a>
                        <div class="row">
                            <div class="col-lg-6 col-6"><span class="text-14 color-text-paragraph-2">9 Vacancy</span></div>
                            <div class="col-lg-6 col-6 text-end"><span class="color-text-paragraph-2 text-14">80 companies</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-5 col-sm-12 col-12">
                <div class="card-image-top hover-up"><a href="jobs-grid">
                        <div class="image" style="background-image: url(assets/imgs/page/homepage1/location4.png);"></div>
                    </a>
                    <div class="informations"><a href="jobs-grid">
                            <h5>Amsterdam, Holland</h5>
                        </a>
                        <div class="row">
                            <div class="col-lg-6 col-6"><span class="text-14 color-text-paragraph-2">16 Vacancy</span></div>
                            <div class="col-lg-6 col-6 text-end"><span class="color-text-paragraph-2 text-14">86 companies</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-7 col-sm-12 col-12">
                <div class="card-image-top hover-up"><a href="jobs-grid">
                        <div class="image" style="background-image: url(assets/imgs/page/homepage1/location5.png);"></div>
                    </a>
                    <div class="informations"><a href="jobs-grid">
                            <h5>Copenhagen, Denmark</h5>
                        </a>
                        <div class="row">
                            <div class="col-lg-6 col-6"><span class="text-14 color-text-paragraph-2">39 Vacancy</span></div>
                            <div class="col-lg-6 col-6 text-end"><span class="color-text-paragraph-2 text-14">186 companies</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-5 col-sm-12 col-12">
                <div class="card-image-top hover-up"><a href="jobs-grid">
                        <div class="image" style="background-image: url(assets/imgs/page/homepage1/location6.png);"></div>
                    </a>
                    <div class="informations"><a href="jobs-grid">
                            <h5>Berlin, Germany</h5>
                        </a>
                        <div class="row">
                            <div class="col-lg-6 col-6"><span class="text-14 color-text-paragraph-2">15 Vacancy</span></div>
                            <div class="col-lg-6 col-6 text-end"><span class="color-text-paragraph-2 text-14">632 companies</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-box mt-50 mb-50">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">News and Blog</h2>
            <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">Get the latest news, updates and tips</p>
        </div>
    </div>
    <div class="container">
        <div class="mt-50">
            <div class="box-swiper style-nav-top">
                <div class="swiper-container swiper-group-3 swiper">
                    <div class="swiper-wrapper pb-70 pt-5">
                        <div class="swiper-slide">
                            <div class="card-grid-3 hover-up wow animate__animated animate__fadeIn">
                                <div class="text-center card-grid-3-image"><a href="#">
                                        <figure><img alt="jobBox" src="assets/imgs/page/homepage1/img-news1.png"></figure>
                                    </a></div>
                                <div class="card-block-info">
                                    <div class="tags mb-15"><a class="btn btn-tag" href="blog-grid">News</a></div>
                                    <h5><a href="blog-details">21 Job Interview Tips: How To Make a Great Impression</a></h5>
                                    <p class="mt-10 color-text-paragraph font-sm">Our mission is to create the world&amp;rsquo;s most sustainable healthcare company by creating high-quality healthcare products in iconic, sustainable packaging.</p>
                                    <div class="card-2-bottom mt-20">
                                        <div class="row">
                                            <div class="col-lg-6 col-6">
                                                <div class="d-flex"><img class="img-rounded" src="assets/imgs/page/homepage1/user1.png" alt="jobBox">
                                                    <div class="info-right-img"><span class="font-sm font-bold color-brand-1 op-70">Sarah Harding</span><br><span class="font-xs color-text-paragraph-2">06 September</span></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 text-end col-6 pt-15"><span class="color-text-paragraph-2 font-xs">8 mins to read</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card-grid-3 hover-up wow animate__animated animate__fadeIn">
                                <div class="text-center card-grid-3-image"><a href="#">
                                        <figure><img alt="jobBox" src="assets/imgs/page/homepage1/img-news2.png"></figure>
                                    </a></div>
                                <div class="card-block-info">
                                    <div class="tags mb-15"><a class="btn btn-tag" href="blog-grid">Events</a></div>
                                    <h5><a href="blog-details">39 Strengths and Weaknesses To Discuss in a Job Interview</a></h5>
                                    <p class="mt-10 color-text-paragraph font-sm">Our mission is to create the world&amp;rsquo;s most sustainable healthcare company by creating high-quality healthcare products in iconic, sustainable packaging.</p>
                                    <div class="card-2-bottom mt-20">
                                        <div class="row">
                                            <div class="col-lg-6 col-6">
                                                <div class="d-flex"><img class="img-rounded" src="assets/imgs/page/homepage1/user2.png" alt="jobBox">
                                                    <div class="info-right-img"><span class="font-sm font-bold color-brand-1 op-70">Steven Jobs</span><br><span class="font-xs color-text-paragraph-2">06 September</span></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 text-end col-6 pt-15"><span class="color-text-paragraph-2 font-xs">6 mins to read</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card-grid-3 hover-up wow animate__animated animate__fadeIn">
                                <div class="text-center card-grid-3-image"><a href="#">
                                        <figure><img alt="jobBox" src="assets/imgs/page/homepage1/img-news3.png"></figure>
                                    </a></div>
                                <div class="card-block-info">
                                    <div class="tags mb-15"><a class="btn btn-tag" href="blog-grid">News</a></div>
                                    <h5><a href="blog-details">Interview Question: Why Dont You Have a Degree?</a></h5>
                                    <p class="mt-10 color-text-paragraph font-sm">Learn how to respond if an interviewer asks you why you dont have a degree, and read example answers that can help you craft</p>
                                    <div class="card-2-bottom mt-20">
                                        <div class="row">
                                            <div class="col-lg-6 col-6">
                                                <div class="d-flex"><img class="img-rounded" src="assets/imgs/page/homepage1/user3.png" alt="jobBox">
                                                    <div class="info-right-img"><span class="font-sm font-bold color-brand-1 op-70">Wiliam Kend</span><br><span class="font-xs color-text-paragraph-2">06 September</span></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 text-end col-6 pt-15"><span class="color-text-paragraph-2 font-xs">9 mins to read</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            <div class="text-center"><a class="btn btn-brand-1 btn-icon-load mt--30 hover-up" href="blog-grid">Load More Posts</a></div>
        </div>
    </div>
</section>
<section class="section-box mt-50 mb-20">
    <div class="box-newsletter box-newsletter-2">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-12 text-center d-none d-xl-block"><img src="assets/imgs/page/homepage4/img-newsletter.png" alt="joxBox"></div>
                <div class="col-xl-8 col-lg-12 col-12 text-center">
                    <div class="d-inline-block text-start">
                        <h2 class="color-white">Subscribe our newsletter</h2>
                        <p class="mt-10 font-lg color-white">New Things Will Always Update Regularl</p>
                        <div class="box-form-newsletter mt-40">
                            <form class="form-newsletter">
                                <input class="input-newsletter" type="text" value="" placeholder="Enter your email here">
                                <button class="btn btn-default font-heading icon-send-letter">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Initialize toastr
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 5000
    };

    // Humanize time function
    function humanizeTime(date) {
        const now = new Date();
        const diff = Math.floor((now - new Date(date)) / 1000);
        if (diff < 60) return `${diff} secs ago`;
        if (diff < 3600) return `${Math.floor(diff / 60)} mins ago`;
        if (diff < 86400) return `${Math.floor(diff / 3600)} hours ago`;
        return `${Math.floor(diff / 86400)} days ago`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tabs
        var triggerTabList = [].slice.call(document.querySelectorAll('.nav-tabs a'));
        triggerTabList.forEach(function(triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl);
            triggerEl.addEventListener('click', function(event) {
                event.preventDefault();
                tabTrigger.show();
            });
        });

        // AJAX for "See more" button
        document.querySelectorAll('.btn-icon-more').forEach(button => {
            button.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-category-id');
                const offset = parseInt(this.getAttribute('data-offset'));
                const button = this;
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
                button.setAttribute('disabled', 'disabled');

                fetch(`<?= site_url('jobs/more') ?>?category_id=${categoryId}&offset=${offset}&limit=6`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        button.innerHTML = 'See more';
                        button.removeAttribute('disabled');

                        if (data.status === 'success' && data.jobs.length > 0) {
                            const jobList = document.getElementById(`job-list-${categoryId}`);
                            data.jobs.forEach(job => {
                                const jobCard = `
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                                    <div class="card-grid-2 grid-bd-16 hover-up">
                                        <div class="card-grid-2-image">
                                            <span class="lbl-hot ${job.job_type === 'freelance' ? 'bg-green' : ''}">
                                                <span>${job.job_type.charAt(0).toUpperCase() + job.job_type.slice(1)}</span>
                                            </span>
                                            <div class="image-box">
                                                <figure>
                                                    <img src="${job.company_logo ? '<?= base_url() ?>' + job.company_logo : '<?= base_url($website_logo) ?>'}" alt="jobBox">
                                                </figure>
                                            </div>
                                        </div>
                                        <div class="card-block-info">
                                            <h5><a href="<?= site_url('jobs/view') ?>/${job.id}">${job.title}</a></h5>
                                            <div class="mt-5">
                                                <span class="card-location mr-15">${job.location || 'Remote'}</span>
                                                <span class="card-time">${humanizeTime(job.created_at)}</span>
                                            </div>
                                            <div class="card-2-bottom mt-20">
                                                <div class="row">
                                                    <div class="col-xl-7 col-md-7 mb-2">
                                                        ${job.skills ? job.skills.split(',').map(skill => `<a class="btn btn-tags-sm mr-5" href="<?= site_url('jobs?skill=') ?>${encodeURIComponent(skill.trim())}">${skill.trim()}</a>`).join('') : ''}
                                                    </div>
                                                    <div class="col-xl-5 col-md-5 text-lg-end">
                                                        <span class="card-text-price">${job.salary || 'Negotiable'}</span>
                                                        <span class="text-muted">${job.salary ? '/' + job.salary_period || 'Monthly' : ''}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="font-sm color-text-paragraph mt-20">${job.description.substring(0, 100)}...</p>
                                        </div>
                                    </div>
                                </div>`;
                                jobList.insertAdjacentHTML('beforeend', jobCard);
                            });
                            button.setAttribute('data-offset', offset + data.jobs.length);
                            if (data.jobs.length < 6) {
                                button.remove();
                            }
                            toastr.success('More jobs loaded successfully!');
                        } else {
                            toastr.info('No more jobs to load.');
                            button.remove();
                        }
                    })
                    .catch(error => {
                        button.innerHTML = 'See more';
                        button.removeAttribute('disabled');
                        toastr.error('Failed to load more jobs.');
                        console.error(error);
                    });
            });
        });
    });


    // Search
    // Initialize select2 for dropdowns
    $('.select-active').select2({
        width: '100%',
        minimumResultsForSearch: 10
    });

    // Form validation and submission
    const form = document.getElementById('job-search-form');
    const searchBtn = document.getElementById('search-btn');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const industryId = form.querySelector('[name="industry_id"]').value;
        const stateId = form.querySelector('[name="state_id"]').value;
        const experienceLevel = form.querySelector('[name="experience_level"]').value;

        if (!industryId && !stateId && !experienceLevel) {
            toastr.error('Please select at least one filter (Industry, Location, or Experience Level).');
            return;
        }

        searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Searching...';
        searchBtn.setAttribute('disabled', 'disabled');

        // Simulate API call (replace with actual AJAX if needed)
        setTimeout(() => {
            form.submit(); // Submit the form to jobs/search
        }, 500);
    });

    // Re-enable button after page load (in case of back navigation)
    searchBtn.innerHTML = 'Search';
    searchBtn.removeAttribute('disabled');
</script>
<?= $this->endSection() ?>