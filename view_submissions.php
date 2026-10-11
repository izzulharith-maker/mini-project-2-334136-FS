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

$sql = "SELECT
            submissions.id,
            users.name,
            assignments.title,
            submissions.file_path,
            submissions.submitted_at
        FROM submissions
        INNER JOIN users
            ON submissions.user_id = users.id
        INNER JOIN assignments
            ON submissions.assignment_id = assignments.id
        ORDER BY submissions.submitted_at DESC";

$result = $conn->query($sql);
?>

<h2 class="mb-4">All Student Submissions</h2>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Assignment</th>
                    <th>Submitted At</th>
                    <th>File</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($row["name"]); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row["title"]); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row["submitted_at"]); ?>
                            </td>
                            <td>
                                <a href="<?php echo htmlspecialchars($row["file_path"]); ?>"
                                   target="_blank"
                                   class="btn btn-sm btn-primary">
                                    View File
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            No submissions available.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<a href="admin_dashboard.php" class="btn btn-secondary mt-3">
    Back to Dashboard
</a>

<?php require_once "footer.php"; ?>
