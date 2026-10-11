<?php
require_once 'db.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    exit("Invalid submission ID.");
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare(
    "SELECT file_name, file_path FROM submissions WHERE submission_id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$submission = $result->fetch_assoc();
$stmt->close();

if (!$submission) {
    exit("Submission not found.");
}

$filePath = $submission['file_path'];
$fileName = basename($submission['file_name']);

if (!is_file($filePath)) {
    exit("File not found on the server.");
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Length: ' . filesize($filePath));
header('X-Content-Type-Options: nosniff');

readfile($filePath);
exit;