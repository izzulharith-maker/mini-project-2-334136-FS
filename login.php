
<?php
session_start();
include 'db.php';

$message = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = "Please fill in all fields.";
    } else {

        $sql = mysqli_prepare($conn, "SELECT id, full_name, password, role FROM users WHERE email = ?");
        mysqli_stmt_bind_param($sql, "s", $email);
        mysqli_stmt_execute($sql);
        mysqli_stmt_bind_result($sql, $id, $full_name, $hashed_password, $role);

        if (mysqli_stmt_fetch($sql) && password_verify($password, $hashed_password)) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['role'] = $role;

            mysqli_stmt_close($sql);

            if ($role == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: student_dashboard.php");
            }
            exit();

        } else {
            $message = "Invalid email or password.";
        }

        mysqli_stmt_close($sql);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Student Portfolio System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card p-4">

                <h3 class="text-center mb-3">Login</h3>

                <?php if ($message != "") { ?>
                    <div class="alert alert-danger">
                        <?php echo $message; ?>
                    </div>
                <?php } ?>

                <form method="POST">

                    <label>Email</label>
                    <input type="email" name="email" class="form-control mb-3" required>

                    <label>Password</label>
                    <input type="password" name="password" class="form-control mb-3" required>

                    <button type="submit" name="login" class="btn btn-primary w-100">
                        Login
                    </button>

                </form>

                <p class="text-center mt-3">
                    Don't have an account?
                    <a href="register.php">Register here</a>
                </p>

            </div>

        </div>
    </div>
</div>

</body>
</html>
