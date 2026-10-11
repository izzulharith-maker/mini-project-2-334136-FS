
<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$data = mysqli_fetch_assoc($result);

$total_users = $data['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card p-4">
        <h2>Admin Dashboard</h2>
        <hr>

        <h4>Welcome, Admin!</h4>
        <p>You have successfully logged in as an Admin.</p>

        <div class="alert alert-info">
            Total Registered Users: <?php echo $total_users; ?>
        </div>

        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

</body>
</html>

