
<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    exit("Access denied.");
}

include 'db.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = mysqli_prepare($conn,
    "SELECT file_path FROM projects WHERE id = ?");

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row || empty($row['file_path'])) {
    exit("File not found.");
}

$folder = realpath(__DIR__ . "/uploads");
$file = realpath(__DIR__ . "/" . $row['file_path']);

if (!$folder || !$file ||
    dirname($file) !== $folder || !is_file($file)) {
    exit("Invalid file.");
}

$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

$types = [
    'pdf' => 'application/pdf',
    'txt' => 'text/plain',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
];

if (!isset($types[$extension])) {
    exit("File type not allowed.");
}

header("Content-Type: " . $types[$extension]);
header("X-Content-Type-Options: nosniff");
header("Content-Disposition: attachment; filename=\"" . basename($file) . "\"");
header("Content-Length: " . filesize($file));

readfile($file);
exit();
?>
