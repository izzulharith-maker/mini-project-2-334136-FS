<?php
session_start();

if (
    !isset($_SESSION["user_id"]) ||
    $_SESSION["role"] !== "Admin"
) {
    header("Location: login.php");
    exit;
}

require_once "db.php";
require_once "header.php";
?>

<h1 class="mb-3">Admin Dashboard</h1>

<p>
    Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!
</p>

<div class="card p-4">
    <h4>Manage Assignments</h4>
    <p>Create and manage student assignments here.</p>
    <a href="create_assignment.php" class="btn btn-primary">
        Create Assignment
    </a>
</div>

<div class="card p-4">
    <h4>Student Submissions</h4>
    <p>View assignments submitted by students.</p>
    <a href="view_submissions.php" class="btn btn-primary">
        View All Submissions
    </a>
</div>

<a href="logout.php" class="btn btn-danger">Logout</a>

<?php require_once "footer.php"; ?>
