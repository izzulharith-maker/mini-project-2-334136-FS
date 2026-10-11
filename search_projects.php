
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    http_response_code(403);
    exit("Access denied.");
}

$search = $_GET['search'] ?? "";
$keyword = "%" . $search . "%";

$sql = "SELECT projects.title,
               projects.description,
               users.full_name,
               categories.category_name
        FROM projects
        INNER JOIN users
            ON projects.user_id = users.id
        INNER JOIN categories
            ON projects.category_id = categories.id
        WHERE projects.title LIKE ?
        ORDER BY projects.id DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $keyword);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
?>
    <div class="border rounded p-3 mb-2">
        <h5><?php echo htmlspecialchars($row['title']); ?></h5>

        <p><?php echo htmlspecialchars($row['description']); ?></p>

        <small>
            Student:
            <?php echo htmlspecialchars($row['full_name']); ?>
        </small>
        <br>
        <small>
            Category:
            <?php echo htmlspecialchars($row['category_name']); ?>
        </small>
    </div>
<?php
    }
} else {
    echo "<p>No projects found.</p>";
}

mysqli_stmt_close($stmt);
?>
