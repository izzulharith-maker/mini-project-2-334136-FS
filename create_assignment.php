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

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $due_date = $_POST["due_date"] ?? "";

    if ($title === "" || $description === "" || $due_date === "") {
        $error = "Please complete all fields.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO assignments (title, description, due_date, created_by)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssi",
            $title,
            $description,
            $due_date,
            $_SESSION["user_id"]
        );

        if ($stmt->execute()) {
            $success = "Assignment created successfully.";
        } else {
            $error = "Unable to create assignment.";
        }

        $stmt->close();
    }
}
?>

<h2>Create Assignment</h2>

<?php if ($error !== ""): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<?php if ($success !== ""): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<div class="card p-4">
    <form method="POST" onsubmit="return validateAssignment()">
        <div class="mb-3">
            <label class="form-label">Assignment Title</label>
            <input type="text" name="title" id="title"
                   class="form-control" maxlength="150" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" id="description"
                      class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" id="due_date"
                   class="form-control" min="<?php echo date('Y-m-d'); ?>"
                   required>
        </div>

        <button type="submit" class="btn btn-primary">
            Create Assignment
        </button>

        <a href="admin_dashboard.php" class="btn btn-secondary">
            Cancel
        </a>
    </form>
</div>

<script>
function validateAssignment() {
    const title = document.getElementById("title").value.trim();
    const description = document.getElementById("description").value.trim();
    const dueDate = document.getElementById("due_date").value;

    if (!title || !description || !dueDate) {
        alert("Please complete all fields.");
        return false;
    }

    return true;
}
</script>

<?php require_once "footer.php"; ?>
