<?php
require_once 'db.php';

$message = "";

$categories = $conn->query(
    "SELECT category_id, category_name FROM categories ORDER BY category_name"
);

if (isset($_POST['create_assignment'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category_id = (int) $_POST['category_id'];
    $due_date = $_POST['due_date'];

    if ($title !== "" && $description !== "" && $category_id > 0 && $due_date !== "") {
        $stmt = $conn->prepare(
            "INSERT INTO assignments
            (title, description, category_id, due_date)
            VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssis",
            $title,
            $description,
            $category_id,
            $due_date
        );

        if ($stmt->execute()) {
            $message = "Assignment created successfully!";
        } else {
            $message = "Failed to create assignment.";
        }

        $stmt->close();
    } else {
        $message = "Please complete all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Assignment</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Create Assignment</h2>
    <p>Create a new assignment for students.</p>

    <?php if ($message !== ""): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="card card-body">
        <label for="title" class="form-label">Assignment Title</label>
        <input type="text" name="title" id="title"
               class="form-control mb-3" required>

        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description"
                  class="form-control mb-3" rows="4"
                  required></textarea>

        <label for="category_id" class="form-label">Category</label>
        <select name="category_id" id="category_id"
                class="form-select mb-3" required>
            <option value="">Select Category</option>

            <?php if ($categories): ?>
                <?php while ($category = $categories->fetch_assoc()): ?>
                    <option value="<?= (int) $category['category_id'] ?>">
                        <?= htmlspecialchars($category['category_name']) ?>
                    </option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>

        <label for="due_date" class="form-label">Due Date</label>
        <input type="date" name="due_date" id="due_date"
               class="form-control mb-3" required>

        <button type="submit" name="create_assignment"
                class="btn btn-success">
            Create Assignment
        </button>
    </form>

    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">
        Back to Dashboard
    </a>
</div>

</body>
</html>