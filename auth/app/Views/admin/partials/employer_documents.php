<?php if (!empty($documents) && is_array($documents)): ?>
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>Document Type</th>
                    <th>File Name</th>
                    <th>Size</th>
                    <th>Uploaded At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $doc): ?>
                    <tr>
                        <td>
                            <?php
                            $docTypes = [
                                'cac_certificate' => 'CAC Certificate',
                                'tax_id' => 'Tax ID',
                                'business_license' => 'Business License',
                                'id_card' => 'ID Card',
                                'other' => 'Other Document'
                            ];
                            echo $docTypes[$doc['document_type']] ?? ucfirst($doc['document_type']);
                            ?>
                        </td>
                        <td>
                            <a href="<?= base_url($doc['file_path']) ?>" target="_blank">
                                <?= esc($doc['file_name']) ?>
                            </a>
                        </td>
                        <td><?= number_format($doc['file_size'] / 1024, 2) ?> KB</td>
                        <td><?= date('M d, Y H:i', strtotime($doc['uploaded_at'])) ?></td>
                        <td>
                            <?php
                            $statusBadges = [
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger'
                            ];
                            $badgeClass = $statusBadges[$doc['status']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $badgeClass ?>-transparent">
                                <?= ucfirst($doc['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="<?= base_url($doc['file_path']) ?>"
                                    class="btn btn-sm btn-primary"
                                    target="_blank"
                                    title="Download">
                                    <i class="ti ti-download"></i>
                                </a>

                                <?php if ($doc['status'] === 'pending'): ?>
                                    <button type="button"
                                        class="btn btn-sm btn-success"
                                        onclick="verifyDocument(<?= $doc['id'] ?>)"
                                        title="Approve">
                                        <i class="ti ti-check"></i>
                                    </button>

                                    <button type="button"
                                        class="btn btn-sm btn-danger"
                                        onclick="rejectDocument(<?= $doc['id'] ?>)"
                                        title="Reject">
                                        <i class="ti ti-x"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function verifyDocument(documentId) {
            if (confirm('Approve this document?')) {
                fetch('<?= base_url("admin/documents/verify") ?>/' + documentId, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            document_id: documentId
                        })
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            toastr.success('Document verified successfully');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            toastr.error(res.message);
                        }
                    });
            }
        }

        function rejectDocument(documentId) {
            if (confirm('Reject this document?')) {
                fetch('<?= base_url("admin/documents/reject") ?>/' + documentId, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            document_id: documentId
                        })
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            toastr.success('Document rejected');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            toastr.error(res.message);
                        }
                    });
            }
        }
    </script>

<?php elseif (empty($documents)): ?>
    <div class="text-center py-5">
        <i class="ti ti-file-text fs-48 text-muted mb-3 d-block"></i>
        <h6 class="text-muted">No documents uploaded yet</h6>
        <p class="text-muted small">This employer hasn't uploaded any verification documents.</p>
    </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="ti ti-alert-circle fs-48 text-warning mb-3 d-block"></i>
        <h6 class="text-warning">Unable to load documents</h6>
        <p class="text-muted small">Please try again later.</p>
    </div>
<?php endif; ?>