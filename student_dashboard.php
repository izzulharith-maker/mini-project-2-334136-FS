
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$name = htmlspecialchars($_SESSION['full_name']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card p-4">

        <h2>Student Dashboard</h2>

        <hr>

        <h4>Welcome, <?php echo $name; ?>!</h4>

        <p>You have successfully logged in as a Student.</p>

        <a href="logout.php" class="btn btn-danger">
            Logout
        </a>

    </div>

</div>

</body>
</html>
