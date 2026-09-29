<?= $this->extend('templates/base') ?>

<?= $this->section('content') ?>
<main id="main-content">
  <section class="py-5 bg-light-subtle" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
          
          <!-- Acknowledgement & Celebration Card -->
          <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4 position-relative">
            
            <!-- Top Status Banner -->
            <div class="text-center p-4 p-md-5 position-relative" style="background: linear-gradient(135deg, #0A2F57 0%, #0861A9 100%); color: #fff;">
              <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle bg-white text-success shadow-lg" style="width: 76px; height: 76px;">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                  <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
              </div>

              <h2 class="fw-bold mb-2 text-white" style="font-family: 'Sora', sans-serif;">Payment Confirmed!</h2>
              <p class="fs-15 text-white-50 mb-0 max-w-500 mx-auto" style="line-height: 1.6;">
                Thank you, <strong><?= esc($candidate->full_name ?? ($user->username ?? 'Student')) ?></strong>. Your payment has been received and your course seat is fully activated.
              </p>
            </div>

            <!-- Auto Redirect Progress Strip -->
            <div class="bg-success text-white py-2 px-3 d-flex align-items-center justify-content-between text-center fs-13 fw-semibold">
              <span><i class="ti ti-loader animate-spin me-1"></i> Auto-forwarding to your interactive classroom...</span>
              <span id="countdownBadge" class="badge bg-white text-success fw-bold px-2 py-1 fs-12">7s</span>
            </div>

            <!-- Receipt & Course Details Body -->
            <div class="card-body p-4 p-md-5 bg-white">
              
              <!-- Course Preview Row -->
              <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 mb-4 border">
                <?php if (!empty($course->thumbnail)): ?>
                  <img src="<?= base_url($course->thumbnail) ?>" alt="<?= esc($course->title) ?>" class="rounded-2 object-fit-cover" style="width: 80px; height: 60px;">
                <?php else: ?>
                  <div class="rounded-2 bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-18" style="width: 80px; height: 60px;">
                    JR
                  </div>
                <?php endif; ?>

                <div class="flex-grow-1">
                  <span class="badge bg-primary-subtle text-primary fw-semibold fs-11 mb-1">ENROLLED COURSE</span>
                  <h5 class="fw-bold mb-1 text-dark fs-16"><?= esc($course->title) ?></h5>
                  <p class="text-muted fs-12 mb-0">
                    <i class="ti ti-user me-1"></i> Instructor: <?= esc($course->instructor ?: 'JobberRecruit') ?> · 
                    <i class="ti ti-clock me-1"></i> <?= esc($course->duration ?: 'Self-paced') ?>
                  </p>
                </div>
              </div>

              <!-- Payment Summary Table -->
              <div class="border rounded-3 p-3 p-md-4 mb-4 bg-white" id="receiptSummary">
                <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom flex-wrap gap-2">
                  <div>
                    <span class="text-muted fs-12 d-block">Official Payment Receipt</span>
                    <strong class="text-dark fs-14">#<?= esc($reference) ?></strong>
                  </div>
                  <span class="badge bg-success-subtle text-success fs-12 fw-bold px-3 py-2">
                    <i class="ti ti-check me-1"></i> PAID &amp; VERIFIED
                  </span>
                </div>

                <!-- Course Name Highlight -->
                <div class="p-3 bg-light-subtle rounded-3 border mb-3">
                  <span class="text-muted d-block fs-11 text-uppercase fw-bold letter-spacing-1">Course Purchased</span>
                  <h6 class="fw-bold text-dark fs-15 mb-0 mt-1">
                    <i class="ti ti-book me-1 text-primary"></i> <?= esc($course->title) ?>
                  </h6>
                </div>

                <div class="row g-3 fs-13 mb-3">
                  <div class="col-sm-6">
                    <span class="text-muted d-block fs-12">Student Name</span>
                    <strong class="text-dark"><?= esc($candidate->full_name ?? ($user->username ?? 'Candidate')) ?></strong>
                  </div>
                  <div class="col-sm-6">
                    <span class="text-muted d-block fs-12">Student Email</span>
                    <strong class="text-dark"><?= esc($user->email ?? '') ?></strong>
                  </div>
                  <div class="col-sm-6">
                    <span class="text-muted d-block fs-12">Payment Method</span>
                    <strong class="text-dark text-capitalize"><?= esc(str_replace('_', ' ', $method)) ?></strong>
                  </div>
                  <div class="col-sm-6">
                    <span class="text-muted d-block fs-12">Transaction Date</span>
                    <strong class="text-dark"><?= date('F j, Y · g:i A') ?></strong>
                  </div>
                </div>

                <!-- Itemized Breakdown -->
                <div class="table-responsive border-top pt-3">
                  <table class="table table-borderless table-sm mb-0 fs-13">
                    <thead>
                      <tr class="text-muted fs-11 text-uppercase border-bottom">
                        <th>Description</th>
                        <th class="text-end">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="fw-semibold text-dark">
                          <?= esc($course->title) ?>
                          <span class="d-block text-muted fs-11 fw-normal">Full Course Lifetime Access &amp; Certification</span>
                        </td>
                        <td class="text-end fw-bold text-dark">₦<?= number_format((float)$amountPaid, 2) ?></td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr class="border-top fw-bold fs-15">
                        <td class="text-dark">Total Amount Paid</td>
                        <td class="text-end text-success">₦<?= number_format((float)$amountPaid, 2) ?></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

              <!-- Action Buttons Grid -->
              <div class="row g-3 align-items-center">
                <div class="col-sm-7">
                  <a href="<?= esc($classroomUrl) ?>" class="btn btn-primary btn-lg w-100 fw-bold d-inline-flex align-items-center justify-content-center shadow-sm">
                    <i class="ti ti-device-laptop me-2 fs-18"></i> Enter Classroom Now
                  </a>
                </div>
                <div class="col-sm-5">
                  <button type="button" onclick="window.print()" class="btn btn-outline-secondary btn-lg w-100 fw-semibold d-inline-flex align-items-center justify-content-center">
                    <i class="ti ti-printer me-2 fs-18"></i> Print Receipt
                  </button>
                </div>
              </div>

              <!-- Additional Help Link -->
              <div class="text-center mt-4">
                <a href="<?= base_url('training/courses') ?>" class="text-muted fs-13 text-decoration-underline">
                  <i class="ti ti-arrow-left me-1"></i> Browse all available courses
                </a>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var countdownSecs = 7;
    var badge = document.getElementById('countdownBadge');
    var targetUrl = '<?= esc($classroomUrl) ?>';

    var timer = setInterval(function() {
        countdownSecs--;
        if (badge) {
            badge.innerText = countdownSecs + 's';
        }
        if (countdownSecs <= 0) {
            clearInterval(timer);
            window.location.href = targetUrl;
        }
    }, 1000);
});
</script>

<style>
@media print {
    header, footer, nav, .btn, #countdownBadge, .bg-success {
        display: none !important;
    }
    body {
        background: #fff !important;
        color: #000 !important;
    }
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>
<?= $this->endSection() ?>
