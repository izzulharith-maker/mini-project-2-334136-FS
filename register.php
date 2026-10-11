
<?php
include 'db.php';

$message = "";

if (isset($_POST['register'])) {

    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($full_name) || empty($email) || empty($password)) {
        $message = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address.";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";
    } else {

        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $message = "Email already registered.";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = mysqli_prepare($conn, "INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'student')");
            mysqli_stmt_bind_param($sql, "sss", $full_name, $email, $hashed_password);

            if (mysqli_stmt_execute($sql)) {
                $message = "Registration successful! You can login now.";
            } else {
                $message = "Registration failed.";
            }
            mysqli_stmt_close($sql);
        }
        mysqli_stmt_close($check);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card p-4">
                <h3 class="text-center mb-3">Student Registration</h3>

                <?php if ($message != "") { ?>
                    <div class="alert alert-info">
                        <?php echo $message; ?>
                    </div>
                <?php } ?>

                <form method="POST">

                    <label>Full Name</label>
                    <input type="text" name="full_name" class="form-control mb-3" required>

                    <label>Email</label>
                    <input type="email" name="email" class="form-control mb-3" required>

                    <label>Password</label>
                    <input type="password" name="password" class="form-control mb-3" minlength="6" required>

                    <button type="submit" name="register" class="btn btn-primary w-100">
                        Register
                    </button>

                </form>

                <p class="text-center mt-3">
                    Already have an account?
                    <a href="login.php">Login here</a>
                </p>

            </div>

        </div>
    </div>
</div>

</body>
</html>
