
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

$sql = "SELECT projects.id,
               projects.title,
               projects.description,
               projects.tech_stack,
               projects.created_at,
               categories.category_name
        FROM projects
        INNER JOIN categories
            ON projects.category_id = categories.id
        WHERE projects.user_id = ?
        ORDER BY projects.id DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Submissions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="card p-4">
        <h2>My Submissions</h2>
        <hr>

        <a href="student_dashboard.php" class="btn btn-secondary mb-3">
            Back to Dashboard
        </a>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>Project Title</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Technology</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>

                <?php
                $no = 1;

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['tech_stack']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="6" class="text-center">
                            No submissions yet.
                        </td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
<?php mysqli_stmt_close($stmt); ?>
