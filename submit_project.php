
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$message = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = (int) $_SESSION['user_id'];
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $tech_stack = trim($_POST['tech_stack'] ?? '');
    $category_id = (int) ($_POST['category_id'] ?? 0);

    if ($title == "" || $description == "" ||
        $tech_stack == "" || $category_id <= 0) {

        $message = "Please complete all fields.";

    } elseif (strlen($title) > 200 || strlen($tech_stack) > 255) {

        $message = "Title or technology field is too long.";

    } elseif (!isset($_FILES['project_file']) ||
              $_FILES['project_file']['error'] != UPLOAD_ERR_OK) {

        $message = "Please upload a valid file.";

    } else {

        $file = $_FILES['project_file'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['pdf', 'docx', 'txt'];
        $max_size = 5 * 1024 * 1024;

        if (!in_array($extension, $allowed)) {

            $message = "Only PDF, DOCX and TXT are allowed.";

        } elseif ($file['size'] <= 0 || $file['size'] > $max_size) {

            $message = "File must not exceed 5 MB.";

        } else {

            $valid = false;
            $mime = mime_content_type($file['tmp_name']);

            if ($extension == 'pdf' && $mime == 'application/pdf') {
                $valid = true;
            }

            if ($extension == 'txt' && $mime == 'text/plain') {
                $valid = true;
            }

            if ($extension == 'docx' && class_exists('ZipArchive')) {
                $zip = new ZipArchive();

                if ($zip->open($file['tmp_name']) === true) {
                    if ($zip->locateName('[Content_Types].xml') !== false &&
                        $zip->locateName('word/document.xml') !== false) {
                        $valid = true;
                    }
                    $zip->close();
                }
            }

            if (!$valid) {

                $message = "Invalid file content.";

            } else {

                $check = mysqli_prepare(
                    $conn,
                    "SELECT id FROM categories WHERE id = ?"
                );

                mysqli_stmt_bind_param($check, "i", $category_id);
                mysqli_stmt_execute($check);
                mysqli_stmt_store_result($check);

                $exists = mysqli_stmt_num_rows($check) > 0;
                mysqli_stmt_close($check);

                if (!$exists) {

                    $message = "Invalid category.";

                } else {

                    $folder = "uploads/";

                    if (!is_dir($folder)) {
                        mkdir($folder, 0755, true);
                    }

                    $filename = bin2hex(random_bytes(12)) . "." . $extension;
                    $path = $folder . $filename;

                    if (move_uploaded_file($file['tmp_name'], $path)) {

                        $sql = "INSERT INTO projects
                                (user_id, category_id, title,
                                 description, tech_stack, file_path)
                                VALUES (?, ?, ?, ?, ?, ?)";

                        $stmt = mysqli_prepare($conn, $sql);

                        mysqli_stmt_bind_param(
                            $stmt,
                            "iissss",
                            $user_id,
                            $category_id,
                            $title,
                            $description,
                            $tech_stack,
                            $path
                        );

                        if (mysqli_stmt_execute($stmt)) {
                            $success = true;
                            $message = "Project submitted successfully!";
                        } else {
                            unlink($path);
                            $message = "Failed to save project.";
                        }

                        mysqli_stmt_close($stmt);

                    } else {
                        $message = "File upload failed.";
                    }
                }
            }
        }
    }
}

$categories = mysqli_query(
    $conn,
    "SELECT id, category_name FROM categories ORDER BY category_name"
);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="card p-4">
        <h2>Submit Project</h2>
        <hr>

        <?php if ($message != "") { ?>
            <div class="alert <?php echo $success ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data"
              onsubmit="return validateForm()">

            <label>Project Title</label>
            <input type="text" name="title" id="title"
                   class="form-control mb-3" maxlength="200" required>

            <label>Description</label>
            <textarea name="description" id="description"
                      class="form-control mb-3" required></textarea>

            <label>Technology Stack</label>
            <input type="text" name="tech_stack" id="tech_stack"
                   class="form-control mb-3" maxlength="255" required>

            <label>Category</label>
            <select name="category_id" id="category"
                    class="form-select mb-3" required>
                <option value="">Select Category</option>

                <?php while ($row = mysqli_fetch_assoc($categories)) { ?>
                    <option value="<?php echo $row['id']; ?>">
                        <?php echo htmlspecialchars($row['category_name']); ?>
                    </option>
                <?php } ?>
            </select>

            <label>Upload File (PDF, DOCX, TXT - Max 5 MB)</label>
            <input type="file" name="project_file" id="project_file"
                   class="form-control mb-3"
                   accept=".pdf,.docx,.txt" required>

            <button type="submit" class="btn btn-primary">
                Submit Project
            </button>

            <a href="student_dashboard.php" class="btn btn-secondary">
                Back
            </a>
        </form>
    </div>
</div>

<script>
function validateForm() {
    var title = document.getElementById("title").value.trim();
    var description = document.getElementById("description").value.trim();
    var tech = document.getElementById("tech_stack").value.trim();
    var category = document.getElementById("category").value;
    var file = document.getElementById("project_file").files[0];

    if (!title || !description || !tech || !category || !file) {
        alert("Please complete all fields.");
        return false;
    }

    var extension = file.name.split(".").pop().toLowerCase();

    if (!["pdf", "docx", "txt"].includes(extension)) {
        alert("Only PDF, DOCX and TXT files are allowed.");
        return false;
    }

    if (file.size == 0 || file.size > 5 * 1024 * 1024) {
        alert("File must not exceed 5 MB.");
        return false;
    }

    return true;
}
</script>
</body>
</html>
