<?php
session_start();

if (!isset($_SESSION["user_id"]) ||
    $_SESSION["role"] !== "Student") {
    header("Location: login.php");
    exit;
}

require_once "db.php";
require_once "header.php";
?>

<h1 class="mb-3">Student Dashboard</h1>

<p>
    Welcome,
    <?php echo htmlspecialchars($_SESSION["name"]); ?>!
</p>

<div class="card p-4">
    <h4>Available Assignments</h4>
    <p>Assignments will appear here.</p>
</div>

<div class="card p-4">
    <h4>My Submissions</h4>
    <p>You can view your submitted assignments here.</p>
</div>

<a href="logout.php" class="btn btn-danger">Logout</a>

<?php require_once "footer.php"; ?>
