<?php
// api/contact-action.php - Contact Form Handler
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contact.php');
    exit;
}

$name    = sanitize($_POST['name'] ?? '');
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$phone   = sanitize($_POST['phone'] ?? '');
$project = sanitize($_POST['project'] ?? '');
$subject = sanitize($_POST['subject'] ?? '');
$message = sanitize($_POST['message'] ?? '');

if (!$name || !$email || !$subject || !$message) {
    setFlash('contact_error', 'Please fill in your Name, a valid Email, Subject, and Message.', 'danger');
    header('Location: ../contact.php');
    exit;
}

try {
    $db = getDB();
    $stmt = $db->prepare("
        INSERT INTO contact_messages (name, email, phone, project, subject, message)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$name, $email, $phone, $project, $subject, $message]);

    setFlash('contact_success', 'Thank you! Your message has been sent successfully. We will get back to you shortly.');
} catch (Exception $e) {
    setFlash('contact_error', 'Failed to submit message: ' . htmlspecialchars($e->getMessage()), 'danger');
}

header('Location: ../contact.php');
exit;
