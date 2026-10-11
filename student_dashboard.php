
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

        <h4>Student Menu</h4>

        <div class="mb-3">

            <a href="view_assignments.php"
               class="btn btn-info mb-2">
                View Assignments
            </a>

            <a href="submit_project.php"
               class="btn btn-primary mb-2">
                Submit Project
            </a>

            <a href="my_submissions.php"
               class="btn btn-success mb-2">
                My Submissions
            </a>

            <a href="logout.php"
               class="btn btn-danger mb-2">
                Logout
            </a>

        </div>

        <hr>

        <h4>Search Projects</h4>

        <input type="text"
               id="search"
               class="form-control mb-3"
               placeholder="Search project title...">

        <div id="results"></div>

    </div>
</div>

<script>
function loadProjects() {

    var keyword = document.getElementById("search").value.trim();

    if (keyword == "") {
        document.getElementById("results").innerHTML = "";
        return;
    }

    fetch("search_projects.php?search=" + encodeURIComponent(keyword))

    .then(function(response) {
        if (!response.ok) {
            throw new Error("Search failed");
        }
        return response.text();
    })

    .then(function(data) {
        document.getElementById("results").innerHTML = data;
    })

    .catch(function() {
        document.getElementById("results").textContent =
            "Unable to search projects.";
    });
}

document.getElementById("search").addEventListener("input", loadProjects);
</script>

</body>
</html>
