
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT assignments.title,
               assignments.description,
               assignments.due_date,
               categories.category_name
        FROM assignments
        LEFT JOIN categories
        ON assignments.category_id = categories.id
        ORDER BY assignments.due_date ASC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Assignments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

<div class="container mt-4">
    <div class="card p-4">

        <h2>Available Assignments</h2>
        <hr>

        <div class="table-responsive">
            <table class="table table-bordered">

                <tr class="table-dark">
                    <th>Title</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Due Date</th>
                </tr>

                <?php if ($result && mysqli_num_rows($result) > 0) { ?>

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr>
                        <td>
                            <?php echo htmlspecialchars($row['title']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['description']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['category_name'] ?? ''); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['due_date']); ?>
                        </td>
                    </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="4">No assignments available.</td>
                    </tr>

                <?php } ?>

            </table>
        </div>

        <a href="submit_project.php"
           class="btn btn-primary">
            Submit Project
        </a>

        <a href="student_dashboard.php"
           class="btn btn-secondary">
            Back to Dashboard
        </a>

    </div>
</div>

</body>
</html>
