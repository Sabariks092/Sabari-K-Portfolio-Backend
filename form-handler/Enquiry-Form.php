<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Allow access from frontend
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include PHPMailer classes
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

// Collect and sanitize inputs
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$enquiry = trim($_POST['enquiry'] ?? '');
$subject = trim($_POST['subject'] ?? 'Portfolio Enquiry');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$enquiry || !$message) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Please fill in all required fields."
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email address."
    ]);
    exit;
}

$mail = new PHPMailer(true);

try {
    // SMTP setup
    $mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'sabariks092@gmail.com';
$mail->Password   = 'clbvonwwuqpobkku';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SSL
$mail->Port       = 465; // SSL port

    // Sender & recipient
    $mail->setFrom('sabariks092@gmail.com', 'Portfolio Contact');
    $mail->addReplyTo($email, $name);
    $mail->addAddress('sabariks092@gmail.com'); // 🔹 Where you want to receive messages

    // Email content
    $mail->isHTML(true);
    $mail->Subject = "📩 New Portfolio Enquiry - {$enquiry}";
    $mail->Body = "
        <h2>New Portfolio Enquiry</h2>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Enquiry For:</strong> {$enquiry}</p>
        <p><strong>Subject:</strong> {$subject}</p>
        <p><strong>Message:</strong><br>{$message}</p>
        <hr>
        <p style='font-size:12px;color:#666;'>This message was sent from your portfolio contact form.</p>
    ";
    $mail->AltBody = "New Portfolio Enquiry\n\nName: {$name}\nEmail: {$email}\nEnquiry For: {$enquiry}\nSubject: {$subject}\nMessage: {$message}";

    // Send email
    $mail->send();
    echo json_encode([
        "status" => "success",
        "message" => "✅ Your enquiry has been sent successfully!"
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Mailer Error: {$mail->ErrorInfo}"
    ]);
}
?>
