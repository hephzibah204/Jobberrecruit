<?php

function getDocumentTypeLabel($type)
{
    $labels = [
        'cac_certificate' => 'CAC Certificate',
        'tax_id' => 'Tax Identification Number',
        'business_license' => 'Business License',
        'id_card' => 'Government ID Card',
        'proof_of_address' => 'Proof of Address',
        'bank_statement' => 'Bank Statement',
        'other' => 'Other Document'
    ];
    
    return $labels[$type] ?? ucfirst(str_replace('_', ' ', $type));
}

function getDocumentIcon($type)
{
    $icons = [
        'cac_certificate' => 'ti-certificate',
        'tax_id' => 'ti-receipt-tax',
        'business_license' => 'ti-license',
        'id_card' => 'ti-id',
        'proof_of_address' => 'ti-home',
        'bank_statement' => 'ti-receipt',
        'other' => 'ti-file'
    ];
    
    return $icons[$type] ?? 'ti-file';
}

function formatFileSize($bytes)
{
    if ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    }
    
    return $bytes . ' bytes';
}