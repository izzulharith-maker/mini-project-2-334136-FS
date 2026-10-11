
<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$message = "";

if (isset($_POST['create_assignment'])) {

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category_id = (int) ($_POST['category_id'] ?? 0);
    $due_date = $_POST['due_date'] ?? '';

    $date = DateTime::createFromFormat('!Y-m-d', $due_date);

    if ($title == '' || $description == '' ||
        $category_id <= 0 || !$date ||
        $date->format('Y-m-d') != $due_date) {

        $message = "Please complete all fields correctly.";

    } else {

        $check = mysqli_prepare($conn,
            "SELECT id FROM categories WHERE id = ?");
        mysqli_stmt_bind_param($check, "i", $category_id);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        $valid_category = mysqli_stmt_num_rows($check) > 0;
        mysqli_stmt_close($check);

        if (!$valid_category) {
            $message = "Invalid category.";
        } else {
            $sql = "INSERT INTO assignments
                    (title, description, category_id, due_date)
                    VALUES (?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param($stmt, "ssis",
                $title, $description, $category_id, $due_date);

            if (mysqli_stmt_execute($stmt)) {
                $message = "Assignment created successfully!";
            } else {
                $message = "Failed to create assignment.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}

$categories = mysqli_query($conn,
    "SELECT id, category_name FROM categories ORDER BY category_name");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Assignment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="card p-4">

        <h2>Create Assignment</h2>
        <hr>

        <?php if ($message != '') { ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php } ?>

        <form method="POST">

            <label>Assignment Title</label>
            <input type="text" name="title"
                   class="form-control mb-3" maxlength="200" required>

            <label>Description</label>
            <textarea name="description"
                      class="form-control mb-3" required></textarea>

            <label>Category</label>
            <select name="category_id"
                    class="form-select mb-3" required>

                <option value="">Select Category</option>

                <?php while ($row = mysqli_fetch_assoc($categories)) { ?>
                    <option value="<?php echo $row['id']; ?>">
                        <?php echo htmlspecialchars($row['category_name']); ?>
                    </option>
                <?php } ?>

            </select>

            <label>Due Date</label>
            <input type="date" name="due_date"
                   class="form-control mb-3" required>

            <button type="submit" name="create_assignment"
                    class="btn btn-success">
                Create Assignment
            </button>

            <a href="admin_dashboard.php" class="btn btn-secondary">
                Back
            </a>

        </form>

    </div>
</div>

</body>
</html>
