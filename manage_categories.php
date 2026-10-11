
<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$message = "";

if (isset($_POST['add_category'])) {
    $name = trim($_POST['category_name'] ?? '');

    if ($name != '') {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO categories (category_name) VALUES (?)");

        mysqli_stmt_bind_param($stmt, "s", $name);

        if (mysqli_stmt_execute($stmt)) {
            $message = "Category added successfully!";
        } else {
            $message = "Unable to add category.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $message = "Please enter a category name.";
    }
}

if (isset($_POST['delete'])) {
    $id = (int) $_POST['delete'];

    $sql = "DELETE FROM categories
            WHERE id = ?
            AND NOT EXISTS (
                SELECT 1 FROM projects WHERE category_id = ?
            )
            AND NOT EXISTS (
                SELECT 1 FROM assignments WHERE category_id = ?
            )";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $id, $id, $id);
    mysqli_stmt_execute($stmt);

    $message = mysqli_stmt_affected_rows($stmt) > 0
        ? "Category deleted."
        : "Category is being used and cannot be deleted.";

    mysqli_stmt_close($stmt);
}

$result = mysqli_query($conn,
    "SELECT id, category_name FROM categories ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="card p-4">

        <h2>Manage Categories</h2>
        <hr>

        <?php if ($message != '') { ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php } ?>

        <form method="POST">
            <label>Category Name</label>
            <input type="text" name="category_name"
                   class="form-control mb-3" maxlength="100" required>

            <button type="submit" name="add_category"
                    class="btn btn-primary">
                Add Category
            </button>
        </form>

        <hr>

        <h4>Category List</h4>

        <table class="table table-bordered">
            <tr class="table-dark">
                <th>ID</th>
                <th>Category Name</th>
                <th>Action</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td>
                        <?php echo htmlspecialchars($row['category_name']); ?>
                    </td>
                    <td>
                        <form method="POST"
                              onsubmit="return confirm('Delete category?')">

                            <input type="hidden" name="delete"
                                   value="<?php echo $row['id']; ?>">

                            <button type="submit"
                                    class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <a href="admin_dashboard.php" class="btn btn-secondary">
            Back to Dashboard
        </a>

    </div>
</div>

</body>
</html>
