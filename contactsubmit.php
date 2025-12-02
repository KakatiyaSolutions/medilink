<?php

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php"; // Composer autoload

// ---------------------------
// Collect POST data
// ---------------------------
$name            = $_POST['name'] ?? '';
$email           = $_POST['email'] ?? '';
$mobile          = $_POST['mobile'] ?? '';
$altMobile       = $_POST['alternatemobile'] ?? '';
$userMessage     = $_POST['message'] ?? '';
$captchaOriginal = $_POST['ocaptcha'] ?? '';
$captchaEntered  = $_POST['ecaptcha'] ?? '';

// ---------------------------
// Validate required fields
// ---------------------------
if (!$name || !$email || !$mobile || !$userMessage || !$captchaEntered) {
    echo json_encode([
        'status' => 'error',
        'message' => 'All fields are required.'
    ]);
    exit;
}

// ---------------------------
// (Optional) Validate Captcha
// ---------------------------
// if ($captchaOriginal !== $captchaEntered) {
//     echo json_encode([
//         'status' => 'error',
//         'message' => 'Invalid captcha.'
//     ]);
//     exit;
// }

// ---------------------------
// Email HTML Body
// ---------------------------
$message = "
<div style='font-family: Arial; padding:15px; background:#f4f4f4;'>
    <div style='max-width:600px; background:white; padding:20px; margin:auto; border-radius:6px;'>
        
        <h2 style='color:#333; text-align:center;'>New Contact Form Submission</h2>

        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Mobile:</strong> $mobile</p>
        <p><strong>Alternate Mobile:</strong> $altMobile</p>
        <p><strong>Message:</strong><br>$userMessage</p>

        <p style='margin-top:20px; font-size:12px; color:#666;'>
            This message was sent from the NSR Contact Page.
        </p>

    </div>
</div>";

// ---------------------------
// Send Email
// ---------------------------
$mail = new PHPMailer(true);

try {

    // SMTP settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'noreply.tsos@gmail.com';
    $mail->Password   = 'uqen rrdy qwrs rwwg'; // Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Sender & Receiver
    $mail->setFrom($email, 'Contact Form - NSR School');
    $mail->addAddress('kas242024d@gmail.com'); // Recipient

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'New Contact Message from NSR Website';
    $mail->Body    = $message;

    $mail->send();

    echo json_encode([
        'status' => 'success',
        'message' => 'Thank you! Your message has been sent successfully.'
    ]);
} catch (Exception $e) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Mailer Error: ' . $mail->ErrorInfo
    ]);
}
