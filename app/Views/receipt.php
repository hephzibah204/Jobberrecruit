<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Receipt · <?= esc($receipt['reference'] ?? 'JobberRecruit') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --brand: #0861A9;
      --brand-deep: #0A2F57;
      --brand-light: #EBF4FC;
      --accent: #FF8A00;
      --accent-light: #FFF5EB;
      --success: #10B981;
      --success-light: #ECFDF5;
      --text: #0F172A;
      --muted: #64748B;
      --border: #E2E8F0;
      --bg: #F8FAFC;
      --card-bg: #FFFFFF;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
      background-color: var(--bg);
      color: var(--text);
      line-height: 1.5;
      padding: 24px 16px 48px;
    }

    .receipt-wrapper {
      max-width: 760px;
      margin: 0 auto;
    }

    /* Action bar */
    .receipt-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      gap: 12px;
      flex-wrap: wrap;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      font-size: 0.9rem;
      font-weight: 600;
      border-radius: 10px;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.2s ease;
      border: 1px solid transparent;
    }

    .btn-primary {
      background: var(--brand);
      color: #fff;
    }

    .btn-primary:hover {
      background: var(--brand-deep);
    }

    .btn-outline {
      background: #fff;
      color: var(--muted);
      border-color: var(--border);
    }

    .btn-outline:hover {
      background: var(--bg);
      color: var(--text);
    }

    /* Receipt card */
    .receipt-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
      overflow: hidden;
      position: relative;
    }

    /* Top decorative bar */
    .receipt-top-bar {
      height: 6px;
      background: linear-gradient(90deg, var(--brand) 0%, var(--accent) 100%);
    }

    /* Receipt header */
    .receipt-header {
      padding: 32px 36px 24px;
      border-bottom: 1px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 20px;
      flex-wrap: wrap;
    }

    .brand-logo-wrap {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .brand-logo-img {
      max-height: 44px;
      width: auto;
      object-fit: contain;
    }

    .brand-text {
      font-family: 'Sora', sans-serif;
      font-weight: 800;
      font-size: 1.4rem;
      color: var(--brand-deep);
      letter-spacing: -0.5px;
    }

    .company-meta {
      font-size: 0.78rem;
      color: var(--muted);
      margin-top: 4px;
      line-height: 1.4;
    }

    .receipt-meta-box {
      text-align: right;
    }

    .receipt-title {
      font-family: 'Sora', sans-serif;
      font-size: 1.35rem;
      font-weight: 700;
      color: var(--brand-deep);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .receipt-ref {
      font-family: 'Sora', monospace;
      font-weight: 700;
      font-size: 0.88rem;
      color: var(--muted);
      margin-top: 4px;
    }

    .status-badge {
      display: inline-block;
      margin-top: 8px;
      padding: 4px 14px;
      font-size: 0.75rem;
      font-weight: 700;
      border-radius: 9999px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-badge.success {
      background: var(--success-light);
      color: #047857;
      border: 1px solid rgba(16, 185, 129, 0.25);
    }

    .status-badge.pending {
      background: var(--accent-light);
      color: #B45309;
      border: 1px solid rgba(255, 138, 0, 0.25);
    }

    .status-badge.failed {
      background: #FEF2F2;
      color: #DC2626;
      border: 1px solid rgba(239, 68, 68, 0.25);
    }

    /* Body sections */
    .receipt-body {
      padding: 28px 36px 36px;
    }

    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
      margin-bottom: 28px;
      padding-bottom: 24px;
      border-bottom: 1px dashed var(--border);
    }

    .info-col-title {
      font-size: 0.72rem;
      font-weight: 700;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.8px;
      margin-bottom: 8px;
    }

    .info-col-name {
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--brand-deep);
      margin-bottom: 4px;
    }

    .info-col-sub {
      font-size: 0.85rem;
      color: var(--muted);
      line-height: 1.45;
    }

    /* Items table */
    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 24px;
    }

    .items-table th {
      background: var(--bg);
      text-align: left;
      padding: 12px 16px;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      color: var(--muted);
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
    }

    .items-table th.text-right,
    .items-table td.text-right {
      text-align: right;
    }

    .items-table td {
      padding: 18px 16px;
      border-bottom: 1px solid var(--border);
      font-size: 0.9rem;
      vertical-align: top;
    }

    .item-name {
      font-weight: 700;
      color: var(--text);
      font-size: 0.95rem;
    }

    .item-desc {
      font-size: 0.8rem;
      color: var(--muted);
      margin-top: 4px;
      line-height: 1.4;
    }

    /* Summary section */
    .summary-box {
      margin-left: auto;
      max-width: 320px;
      background: var(--bg);
      border-radius: 12px;
      padding: 18px 20px;
      border: 1px solid var(--border);
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.85rem;
      margin-bottom: 8px;
      color: var(--muted);
    }

    .summary-row.total-row {
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px dashed var(--border);
      color: var(--brand-deep);
      font-weight: 700;
      font-size: 1.15rem;
    }

    .total-val {
      font-family: 'Sora', sans-serif;
      font-weight: 800;
      color: var(--brand);
      font-size: 1.35rem;
    }

    /* Footer verification */
    .receipt-footer {
      padding: 24px 36px;
      background: var(--bg);
      border-top: 1px solid var(--border);
      font-size: 0.78rem;
      color: var(--muted);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
    }

    .receipt-footer-left {
      max-width: 480px;
      line-height: 1.5;
    }

    .receipt-watermark-tag {
      font-size: 0.72rem;
      font-weight: 700;
      color: var(--brand);
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    /* Print styling */
    @media print {
      body {
        background: #fff !important;
        padding: 0 !important;
      }
      .no-print {
        display: none !important;
      }
      .receipt-card {
        border: 1px solid #ccc !important;
        box-shadow: none !important;
        border-radius: 0 !important;
      }
      .receipt-header,
      .receipt-body,
      .receipt-footer {
        padding-left: 20px !important;
        padding-right: 20px !important;
      }
    }

    @media (max-width: 640px) {
      .receipt-header {
        padding: 24px 20px;
        flex-direction: column;
        align-items: flex-start;
      }
      .receipt-meta-box {
        text-align: left;
        margin-top: 8px;
      }
      .receipt-body {
        padding: 20px;
      }
      .info-grid {
        grid-template-columns: 1fr;
        gap: 16px;
      }
      .summary-box {
        max-width: 100%;
      }
      .receipt-footer {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<div class="receipt-wrapper">

  <!-- Action Bar (hidden when printing) -->
  <div class="receipt-actions no-print">
    <a href="<?= esc($backUrl ?? base_url('employer/transactions')) ?>" class="btn btn-outline">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Back to Transactions
    </a>
    <button type="button" onclick="window.print()" class="btn btn-primary">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
      Print / Save Receipt
    </button>
  </div>

  <!-- Receipt Card -->
  <div class="receipt-card">
    <div class="receipt-top-bar"></div>

    <!-- Header -->
    <div class="receipt-header">
      <div>
        <div class="brand-logo-wrap">
          <img src="<?= base_url('auth/img/logo.png') ?>" alt="JobberRecruit" class="brand-logo-img" onerror="this.style.display='none';">
          <span class="brand-text">JobberRecruit</span>
        </div>
        <div class="company-meta">
          <strong>JobberRecruit Global Services Limited</strong><br>
          Official Recruitment &amp; Career Development Platform<br>
          Support: support@jobberrecruit.com · https://jobberrecruit.com
        </div>
      </div>

      <div class="receipt-meta-box">
        <div class="receipt-title">Payment Receipt</div>
        <div class="receipt-ref">Ref: <?= esc($receipt['reference'] ?? 'N/A') ?></div>
        <?php
          $statusLower = strtolower(trim($receipt['status'] ?? 'pending'));
          $isPaid = in_array($statusLower, ['success', 'successful', 'completed', 'paid', 'approved', 'active'], true);
          $badgeClass = 'success';
          $statusText = 'Completed';
          if (in_array($statusLower, ['success', 'successful', 'completed', 'paid', 'approved', 'active'])) {
              $badgeClass = 'success';
              $statusText = 'Paid & Verified';
          } elseif (in_array($statusLower, ['pending', 'open'])) {
              $badgeClass = 'pending';
              $statusText = 'Pending';
          } else {
              $badgeClass = 'failed';
              $statusText = ucfirst($statusLower);
          }
        ?>
        <span class="status-badge <?= $badgeClass ?>"><?= esc($statusText) ?></span>
      </div>
    </div>

    <!-- Body -->
    <div class="receipt-body">

      <!-- Payer & Transaction Meta Grid -->
      <div class="info-grid">
        <div>
          <div class="info-col-title">Billed To (Customer)</div>
          <div class="info-col-name"><?= esc($receipt['customer_name'] ?? 'Valued Customer') ?></div>
          <?php if (!empty($receipt['company_name'])): ?>
            <div class="info-col-sub"><strong>Company:</strong> <?= esc($receipt['company_name']) ?></div>
          <?php endif; ?>
          <?php if (!empty($receipt['customer_email'])): ?>
            <div class="info-col-sub"><?= esc($receipt['customer_email']) ?></div>
          <?php endif; ?>
          <?php if (!empty($receipt['customer_phone'])): ?>
            <div class="info-col-sub"><?= esc($receipt['customer_phone']) ?></div>
          <?php endif; ?>
        </div>

        <div>
          <div class="info-col-title">Transaction Details</div>
          <div class="info-col-sub"><strong><?= $isPaid ? 'Payment Date:' : 'Transaction Date:' ?></strong> <?= esc(!empty($receipt['date']) ? date('M d, Y · h:i A', strtotime($receipt['date'])) : date('M d, Y')) ?></div>
          <div class="info-col-sub"><strong>Payment Channel:</strong> <?= esc(ucfirst(str_replace('_', ' ', $receipt['payment_method'] ?? 'Wallet / Paystack'))) ?></div>
          <div class="info-col-sub"><strong>Transaction Type:</strong> <?= esc(ucfirst(str_replace('_', ' ', $receipt['type'] ?? 'Payment'))) ?></div>
          <div class="info-col-sub"><strong>Currency:</strong> Nigerian Naira (NGN)</div>
        </div>
      </div>

      <!-- Item Table -->
      <table class="items-table">
        <thead>
          <tr>
            <th>Item &amp; Description</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Amount</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <div class="item-name"><?= esc($receipt['description'] ?? 'JobberRecruit Service') ?></div>
              <div class="item-desc"><?= esc($receipt['item_details'] ?? 'Transaction processed securely through JobberRecruit platform.') ?></div>
            </td>
            <td class="text-right">1</td>
            <td class="text-right"><b>&#8358;<?= number_format((float)($receipt['amount'] ?? 0), 2) ?></b></td>
          </tr>
        </tbody>
      </table>

      <!-- Summary -->
      <div class="summary-box">
        <div class="summary-row">
          <span>Subtotal</span>
          <span>&#8358;<?= number_format((float)($receipt['amount'] ?? 0), 2) ?></span>
        </div>
        <div class="summary-row">
          <span>Tax / VAT (0%)</span>
          <span>&#8358;0.00</span>
        </div>
        <div class="summary-row total-row">
          <span><?= $isPaid ? 'Total Paid' : 'Transaction Amount' ?></span>
          <span class="total-val">&#8358;<?= number_format((float)($receipt['amount'] ?? 0), 2) ?></span>
        </div>
      </div>

    </div>

    <!-- Footer -->
    <div class="receipt-footer">
      <div class="receipt-footer-left">
        <span class="receipt-watermark-tag">&#10003; Official Electronic Receipt</span><br>
        <?= $isPaid
            ? 'This receipt confirms the recorded completed transaction on JobberRecruit.'
            : 'This document records the transaction status shown above and is not proof of completed payment.' ?>
        It was electronically generated and is valid without physical signature.
      </div>
      <div>
        <small style="color:var(--muted)">Printed: <?= date('M d, Y · H:i') ?></small>
      </div>
    </div>

  </div>

</div>

</body>
</html>
