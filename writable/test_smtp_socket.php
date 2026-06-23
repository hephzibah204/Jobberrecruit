<?php

$user = 'api';
$pass = 'f61d16194a672c3c36fe382d5ebd2f76';

echo "Connecting to live.smtp.mailtrap.io:587 via TCP...\n";
$socket = stream_socket_client("tcp://live.smtp.mailtrap.io:587", $errno, $errstr, 15);

if (!$socket) {
    echo "ERROR: {$errstr} ({$errno})\n";
    exit(1);
}

stream_set_timeout($socket, 5);

function readResponse($socket) {
    $response = "";
    while ($line = fgets($socket, 512)) {
        echo "< " . $line;
        $response .= $line;
        if (substr($line, 3, 1) === " ") {
            break;
        }
    }
    return $response;
}

// 1. Read greeting
readResponse($socket);

// 2. Send EHLO with local hostname "Workstation"
echo "> EHLO Workstation\n";
fwrite($socket, "EHLO Workstation\r\n");
readResponse($socket);

// 3. Send STARTTLS
echo "> STARTTLS\n";
fwrite($socket, "STARTTLS\r\n");
readResponse($socket);

// 4. Enable crypto (TLS)
echo "Enabling TLS crypto on socket...\n";
if (stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT)) {
    echo "TLS crypto enabled successfully!\n";
    
    // 5. Send EHLO again after TLS
    echo "> EHLO Workstation\n";
    fwrite($socket, "EHLO Workstation\r\n");
    readResponse($socket);
    
    // 6. Send AUTH LOGIN
    echo "> AUTH LOGIN\n";
    fwrite($socket, "AUTH LOGIN\r\n");
    readResponse($socket);
    
    // 7. Send base64 username
    $b64User = base64_encode($user);
    echo "> [b64 username: {$b64User}]\n";
    fwrite($socket, $b64User . "\r\n");
    readResponse($socket);
    
    // 8. Send base64 password
    $b64Pass = base64_encode($pass);
    echo "> [b64 password: (hidden)]\n";
    fwrite($socket, $b64Pass . "\r\n");
    readResponse($socket);
    
    // 9. Send MAIL FROM
    echo "> MAIL FROM:<info@jobberrecruit.com>\n";
    fwrite($socket, "MAIL FROM:<info@jobberrecruit.com>\r\n");
    readResponse($socket);
    
    // 10. Send RCPT TO
    echo "> RCPT TO:<hephzibah204@gmail.com>\n";
    fwrite($socket, "RCPT TO:<hephzibah204@gmail.com>\r\n");
    readResponse($socket);
    
    // 11. Send DATA
    echo "> DATA\n";
    fwrite($socket, "DATA\r\n");
    readResponse($socket);
    
    // 12. Send message body
    echo "> [sending message content]\n";
    $msg = "Subject: Raw SMTP Authentication Test\r\n" .
           "From: info@jobberrecruit.com\r\n" .
           "To: hephzibah204@gmail.com\r\n" .
           "Content-Type: text/plain\r\n\r\n" .
           "This is a test of raw SMTP login and send via Mailtrap with Workstation hostname.\r\n.\r\n";
    fwrite($socket, $msg);
    readResponse($socket);
    
    // 13. Send QUIT
    echo "> QUIT\n";
    fwrite($socket, "QUIT\r\n");
    readResponse($socket);
} else {
    echo "ERROR: TLS crypto enable failed!\n";
}

echo "Closing socket.\n";
fclose($socket);
