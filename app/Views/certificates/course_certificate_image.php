<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Generating Certificate Image...</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: #f0f2f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: 'Helvetica', 'Arial', sans-serif;
        }
        .loading-text {
            margin-bottom: 20px;
            font-size: 18px;
            color: #555;
        }
        
        /* EXACT same styles as course_certificate.php */
        .cert-wrapper {
            width: 1122px; /* A4 width at 96 DPI */
            height: 793px; /* A4 height at 96 DPI */
            position: relative;
            background: #fff;
            color: <?= $template['text_color'] ?? '#333' ?>;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .cert-container {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #fff;
            <?php if (!empty($template['background_image'])): ?>
            background-image: url('<?= base_url($template['background_image']) ?>');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            border: none;
            <?php else: ?>
            border: 20px solid <?= $template['primary_color'] ?? '#0D609E' ?>;
            <?php endif; ?>
            overflow: hidden;
            box-sizing: border-box;
        }
        .cert-border-inner {
            position: absolute;
            top: 10px; left: 10px; right: 10px; bottom: 10px;
            <?php if (empty($template['background_image'])): ?>
            border: 2px solid <?= $template['secondary_color'] ?? '#F3921D' ?>;
            <?php endif; ?>
            box-sizing: border-box;
        }
        .cert-element {
            position: absolute;
            display: block;
        }
        .logo img { height: auto; }
        .signature img { height: auto; }
        .qr-code img { width: 100%; height: auto; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Inter:wght@400;600;700&family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Include html2canvas -->
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
</head>
<body>
    <div class="loading-text" id="statusText">Generating your high-quality JPEG certificate... Please wait.</div>

    <div class="cert-wrapper" id="captureTarget">
        <div class="cert-container">
            <?php if (($template['template_mode'] ?? 'builder') === 'builder' || ($template['template_mode'] ?? '') === 'image'): ?>
                <div class="cert-border-inner"></div>
                
                <?php foreach ($layout as $key => $props): ?>
                    <?php if (!isset($props['visible']) || $props['visible'] === true): ?>
                        <div class="cert-element <?= $key ?>" style="
                            <?php if (isset($props['top']) || !isset($props['bottom'])) echo "top: " . ($props['top'] ?? '0px') . ";"; ?>
                            <?php if (isset($props['left']) || !isset($props['right'])) echo "left: " . ($props['left'] ?? '0px') . ";"; ?>
                            <?php if (isset($props['bottom'])) echo "bottom: " . $props['bottom'] . ";"; ?>
                            <?php if (isset($props['right'])) echo "right: " . $props['right'] . ";"; ?>
                            <?php if (isset($props['transform'])) echo "transform: " . $props['transform'] . ";"; ?>
                            <?php if (isset($props['font_size'])) echo "font-size: " . $props['font_size'] . ";"; ?>
                            <?php if (isset($props['font_family'])) echo "font-family: " . $props['font_family'] . ";"; ?>
                            <?php if ($key === 'title' || $key === 'course_title') echo "color: " . ($template['primary_color'] ?? '#0D609E') . "; font-weight: bold;"; ?>
                            <?php if ($key === 'recipient_name') echo "color: #333; font-weight: bold; border-bottom: 2px solid " . ($template['secondary_color'] ?? '#F3921D') . ";"; ?>
                        ">
                            <?php if ($key === 'logo'): ?>
                                <img src="<?= base_url('auth/img/logo.png') ?>" style="width: <?= $props['width'] ?? '120px' ?>;" crossorigin="anonymous">
                            <?php elseif ($key === 'title'): ?>
                                CERTIFICATE
                            <?php elseif ($key === 'subtitle'): ?>
                                OF COURSE COMPLETION
                            <?php elseif ($key === 'recipient_name'): ?>
                                <?= esc($user->certificate_name ?? $user->full_name ?? $user->username ?? 'Participant') ?>
                            <?php elseif ($key === 'course_title'): ?>
                                <?= esc($course->title) ?>
                            <?php elseif ($key === 'date_issued'): ?>
                                <?= date('F j, Y', strtotime($certificate['issued_at'])) ?>
                            <?php elseif ($key === 'signature'): ?>
                                <div class="text-center">
                                    <?php if (setting('Elearning.certificate_signature')): ?>
                                        <img src="<?= base_url(setting('Elearning.certificate_signature')) ?>" style="width: <?= $props['width'] ?? '150px' ?>;" crossorigin="anonymous">
                                    <?php endif; ?>
                                    <div class="w-100" style="border-top: 1px solid #ccc; margin-top: 5px"></div>
                                    <div style="font-size: 12px;">Authorized Signature</div>
                                </div>
                            <?php elseif ($key === 'qr_code'): ?>
                                <div class="qr-code" style="width: <?= $props['width'] ?? '80px' ?>;">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode(base_url('verify/' . $certificate['certificate_code'])) ?>" crossorigin="anonymous">
                                </div>
                            <?php elseif ($key === 'certificate_code'): ?>
                                Code: <?= esc($certificate['certificate_code']) ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- HTML Mode Fallback -->
                <?php
                    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode(base_url('verify/' . $certificate['certificate_code']));
                    $signatureUrl = setting('Elearning.certificate_signature') ? base_url(setting('Elearning.certificate_signature')) : '';
                    
                    $placeholders = [
                        '{{name}}'      => esc($user->certificate_name ?? $user->full_name ?? $user->username ?? 'Participant'),
                        '{{course}}'    => esc($course->title),
                        '{{date}}'      => date('F j, Y', strtotime($certificate['issued_at'])),
                        '{{code}}'      => esc($certificate['certificate_code']),
                        '{{qr_code}}'   => '<img src="'.$qrCodeUrl.'" style="width:100px;" crossorigin="anonymous" alt="">',
                        '{{signature}}' => $signatureUrl ? '<img src="'.$signatureUrl.'" style="width:150px;" crossorigin="anonymous" alt="">' : '',
                    ];
                    
                    echo str_replace(array_keys($placeholders), array_values($placeholders), $template['custom_html']);
                ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                const target = document.getElementById('captureTarget');
                html2canvas(target, {
                    scale: 2, // High resolution (retina display equivalent)
                    useCORS: true, // Allow loading external images like QR code
                    logging: false,
                    backgroundColor: null
                }).then(canvas => {
                    // Create image URL
                    const image = canvas.toDataURL("image/jpeg", 1.0);
                    
                    // Create fake anchor to trigger download
                    const link = document.createElement('a');
                    link.download = 'Certificate-<?= esc($certificate['certificate_code']) ?>.jpg';
                    link.href = image;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    document.getElementById('statusText').innerHTML = 'Download complete! You can close this tab.';
                    document.getElementById('statusText').style.color = 'green';
                }).catch(err => {
                    document.getElementById('statusText').innerHTML = 'An error occurred while generating the image.';
                    document.getElementById('statusText').style.color = 'red';
                    console.error(err);
                });
            }, 1000); // Give images 1 second to load completely
        };
    </script>
</body>
</html>
