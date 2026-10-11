
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$data = mysqli_fetch_assoc($result);
$total_users = $data['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

<div class="container mt-4">
    <div class="card p-4">

        <h2>Admin Dashboard</h2>
        <hr>

        <h4>
            Welcome,
            <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Admin'); ?>!
        </h4>

        <p>Student Portfolio & FYP Showcase System</p>

        <div class="alert alert-info">
            Total Registered Users:
            <?php echo $total_users; ?>
        </div>

        <h4>Admin Menu</h4>

        <div class="mb-3">

            <a href="manage_categories.php"
               class="btn btn-primary">
                Manage Categories
            </a>

            <a href="create_assignment.php"
               class="btn btn-success">
                Create Assignment
            </a>

            <a href="view_submissions.php"
               class="btn btn-info">
                View Submissions
            </a>

            <a href="logout.php"
               class="btn btn-danger">
                Logout
            </a>

        </div>

    </div>
</div>

</body>
</html>
