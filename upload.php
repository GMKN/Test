<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// reCAPTCHA Secret Key
$secretKey = '6Le-BjkqAAAAAGcpQpT0qju0AGZK9mn7iKp2sNgI';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate reCAPTCHA
    $recaptchaResponse = $_POST['recaptcha_response'];
    $recaptchaVerify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
    $recaptchaResult = json_decode($recaptchaVerify);

    if (!$recaptchaResult->success) {
        echo json_encode(['message' => 'CAPTCHA validation failed.']);
        exit;
    }

    // Validate register number
    $regNumber = $_POST['regNumber'];
    if (!is_numeric($regNumber) || $regNumber < 511323243001 || $regNumber >= 511323243065) {
        echo json_encode(['message' => 'Invalid register number.']);
        exit;
    }

    // Directory to save the uploaded file
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Path to save the uploaded file
    $uploadFile = $uploadDir . basename($_FILES['file']['name']);
    $name = $_POST['name'];

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = '2ndyearaids@gmail.com'; // Your Gmail address
            $mail->Password = 'pbcsonugbaomgikp';       // Your Gmail app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('2ndyearaids@gmail.com', 'Document Upload');
            $mail->addAddress('gmars.gmr@gmail.com'); // Receiver's email address

            // Content
            $mail->isHTML(false);
            $mail->Subject = 'New Document Uploaded';
            $mail->Body = "Name: $name\nRegister Number: $regNumber\n\nFile: " . basename($uploadFile);

            // Attach the uploaded file
            $mail->addAttachment($uploadFile);

            // Send the email
            $mail->send();
            echo json_encode(['message' => 'File uploaded and email sent successfully!']);
        } catch (Exception $e) {
            echo json_encode(['message' => 'File uploaded but email sending failed. Error: ' . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['message' => 'File upload failed.']);
    }
} else {
    echo json_encode(['message' => 'Invalid request method.']);
}
?>
