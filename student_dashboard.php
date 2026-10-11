

<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$name = htmlspecialchars($_SESSION['full_name'] ?? 'Student');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="card p-4">
        <h2>Student Dashboard</h2>
        <hr>

        <h4>Welcome, <?php echo $name; ?>!</h4>
        <p>Student Portfolio & FYP Showcase System</p>

        <div class="mb-3">
            <a href="submit_project.php" class="btn btn-primary">
                Submit Project
            </a>
            <a href="my_submissions.php" class="btn btn-success">
                My Submissions
            </a>
            <a href="logout.php" class="btn btn-danger">
                Logout
            </a>
        </div>

        <hr>
        <h4>AJAX Live Search</h4>

        <input type="text" id="search"
               class="form-control mb-3"
               placeholder="Search project title...">

        <div id="results">Loading projects...</div>
    </div>
</div>

<script>
function loadProjects() {
    var keyword = document.getElementById("search").value;

    fetch("search_projects.php?search=" + encodeURIComponent(keyword))
    .then(function(response) {
        if (!response.ok) {
            throw new Error("Unable to load projects");
        }
        return response.text();
    })
    .then(function(data) {
        document.getElementById("results").innerHTML = data;
    })
    .catch(function() {
        document.getElementById("results").textContent =
            "Unable to load projects.";
    });
}

document.getElementById("search").addEventListener("input", loadProjects);
loadProjects();
</script>

</body>
</html>
