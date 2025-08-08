<?php
// send_email.php
header('Content-Type: application/json');

// Allow large uploads if invoice images are big
ini_set('upload_max_filesize','10M');
ini_set('post_max_size','12M');

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(['success'=>false,'error'=>'Invalid request']);
    exit;
}

// check file
if(!isset($_FILES['invoice_pdf']) || $_FILES['invoice_pdf']['error'] !== UPLOAD_ERR_OK){
    echo json_encode(['success'=>false,'error'=>'PDF missing']);
    exit;
}

$cust_name = isset($_POST['cust_name']) ? trim($_POST['cust_name']) : 'Customer';
$cust_mobile = isset($_POST['cust_mobile']) ? trim($_POST['cust_mobile']) : '';
$cust_email = isset($_POST['cust_email']) ? trim($_POST['cust_email']) : '';

// move uploaded file to a temp location
$tmpPath = $_FILES['invoice_pdf']['tmp_name'];
$origName = basename($_FILES['invoice_pdf']['name']);
$savePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $origName;
if(!move_uploaded_file($tmpPath, $savePath)){
    echo json_encode(['success'=>false,'error'=>'Failed to save uploaded PDF']);
    exit;
}

// --- PHPMailer include (you must have PHPMailer files in smtp-libs/src folder) ---
require 'smtp-libs/src/Exception.php';
require 'smtp-libs/src/PHPMailer.php';
require 'smtp-libs/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    $mail = new PHPMailer(true);
    // SMTP config
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'zencyinfo@gmail.com';         // your Gmail
    $mail->Password = 'vczb faju eqwy ihaf';        // Gmail app password (keep secret!)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // From / To
    $mail->setFrom('zencyinfo@gmail.com', 'Zency Creation');
    // Send to admin first (you). Also optionally CC customer if provided.
    $mail->addAddress('zencyinfo@gmail.com', 'Zency Admin');
    if(!empty($cust_email) && filter_var($cust_email, FILTER_VALIDATE_EMAIL)){
        $mail->addAddress($cust_email); // send to customer too
    }

    // Attachment
    $mail->addAttachment($savePath, $origName);

    // Email body (HTML)
    $mail->isHTML(true);
    $mail->Subject = "Invoice for {$cust_name} — Zency Creation";
    $body = "<h3>Zency Creation — Invoice</h3>
             <p><strong>Customer:</strong> {$cust_name}<br/>
             <strong>Mobile:</strong> {$cust_mobile}</p>
             <p>Invoice is attached as PDF.</p>
             <p style='font-size:12px;color:#666'>Returns only if damaged.</p>";
    $mail->Body = $body;
    $mail->AltBody = "Invoice attached for {$cust_name}";

    $mail->send();

    // delete temporary file
    @unlink($savePath);

    echo json_encode(['success'=>true]);
} catch (Exception $e) {
    // cleanup
    @unlink($savePath);
    echo json_encode(['success'=>false,'error'=>$mail->ErrorInfo ?: $e->getMessage()]);
}
