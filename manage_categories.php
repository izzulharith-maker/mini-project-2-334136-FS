<?php
require_once 'db.php';

$message = "";

if (isset($_POST['add_category'])) {
    $category_name = trim($_POST['category_name']);

    if (!empty($category_name)) {
        $stmt = $conn->prepare(
            "INSERT INTO categories (category_name) VALUES (?)"
        );
        $stmt->bind_param("s", $category_name);

        if ($stmt->execute()) {
            $message = "Category added successfully!";
        } else {
            $message = "Unable to add category.";
        }

        $stmt->close();
    } else {
        $message = "Please enter a category name.";
    }
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    $stmt = $conn->prepare(
        "DELETE FROM categories WHERE category_id = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_categories.php");
    exit;
}

$result = $conn->query(
    "SELECT * FROM categories ORDER BY category_id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Manage Categories</h2>
    <p>Add and manage assignment categories.</p>

    <?php if ($message != ""): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="card card-body mb-4">
        <label for="category_name" class="form-label">
            Category Name
        </label>

        <input type="text" id="category_name"
               name="category_name"
               class="form-control mb-3"
               placeholder="Enter category name"
               required>

        <button type="submit" name="add_category"
                class="btn btn-primary">
            Add Category
        </button>
    </form>

    <h4>Category List</h4>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= (int) $row['category_id'] ?></td>
                    <td><?= htmlspecialchars($row['category_name']) ?></td>
                    <td>
                        <a href="?delete=<?= (int) $row['category_id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this category?')">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No categories found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php" class="btn btn-secondary">
        Back to Dashboard
    </a>
</div>

</body>
</html>