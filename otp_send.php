<?php
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
include 'koneksi.php';

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Check request method
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $response = array();

    // Check if email is set
    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        // Sanitize email input
        $email = mysqli_real_escape_string($koneksi, $email);

        // Check if email exists
        $checkEmailQuery = "SELECT * FROM users WHERE email='$email'";
        $emailResult = mysqli_query($koneksi, $checkEmailQuery);

        if (mysqli_num_rows($emailResult) > 0) {
            // Generate OTP
            $otp = rand(1000, 9999);

            // Store OTP in the database
            $updateQuery = "UPDATE users SET code_verification='$otp' WHERE email='$email'";
            if (mysqli_query($koneksi, $updateQuery)) {
                // Send OTP via email
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Set the SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'careshareclient@gmail.com'; // SMTP username
                    $mail->Password = 'iwtf kprh krql tklu'; // SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    //Recipients
                    $mail->setFrom('otp@verif.com', 'CareShare Client');
                    $mail->addAddress($email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'CareShare [' . $otp . ']';
                    $mail->Body = 'Berikut adalah kode otp untuk memverifikasi akun anda: ' . $otp;

                    $mail->send();
                    $response['value'] = 1;
                    $response['message'] = 'OTP berhasil dikirim';
                } catch (Exception $e) {
                    $response['value'] = 0;
                    $response['message'] = 'Gagal mengirim OTP: ' . $mail->ErrorInfo;
                }
            } else {
                $response['value'] = 0;
                $response['message'] = 'Gagal menyimpan OTP: ' . mysqli_error($koneksi);
            }
        } else {
            $response['value'] = 0;
            $response['message'] = 'Email tidak ditemukan';
        }
    } else {
        $response['value'] = 0;
        $response['message'] = 'Parameter yang diperlukan tidak ada';
    }

    echo json_encode($response);
} else {
    $response['value'] = 0;
    $response['message'] = 'Metode permintaan tidak valid';
    echo json_encode($response);
}

mysqli_close($koneksi);
?>