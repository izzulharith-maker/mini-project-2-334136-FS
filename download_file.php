<?php
require_once 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit("Invalid project ID.");
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare(
    "SELECT file_path FROM projects WHERE id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$project = $result->fetch_assoc();
$stmt->close();

if (!$project || empty($project['file_path'])) {
    exit("File not found.");
}

$file_path = $project['file_path'];

if (!is_file($file_path)) {
    exit("The file does not exist on the server.");
}

$file_name = basename($file_path);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $file_name . '"');
header('Content-Length: ' . filesize($file_path));

readfile($file_path);
exit;
?>