<?php

session_start();

include "config/database.php";

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password != $confirm_password) {

        $error = "Passwords do not match.";

    } else {

        $check_sql = "SELECT id FROM users WHERE email = ?";

        $check_stmt = mysqli_prepare($conn, $check_sql);

        mysqli_stmt_bind_param(
            $check_stmt,
            "s",
            $email
        );

        mysqli_stmt_execute($check_stmt);

        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) > 0) {

            $error = "Email already registered.";

        } else {

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $sql = "INSERT INTO users
                    (name, email, password)
                    VALUES (?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $name,
                $email,
                $hashed_password
            );

            if (mysqli_stmt_execute($stmt)) {

                $message = "Registration successful. You can login now.";

            } else {

                $error = "Registration failed.";

            }

        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>User Registration</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<div class="login-container">

    <div class="login-box">

        <h1>📚 Study Notes</h1>

        <h2>Create Account</h2>

        <?php if ($error != "") { ?>

            <p class="error">
                <?php echo $error; ?>
            </p>

        <?php } ?>

        <?php if ($message != "") { ?>

            <p class="success">
                <?php echo $message; ?>
            </p>

        <?php } ?>


        <form method="POST">

            <label>
                Full Name
            </label>

            <input
                type="text"
                name="name"
                placeholder="Enter your name"
                required
            >


            <label>
                Email
            </label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                required
            >


            <label>
                Password
            </label>

            <input
                type="password"
                name="password"
                placeholder="Create password"
                required
            >


            <label>
                Confirm Password
            </label>

            <input
                type="password"
                name="confirm_password"
                placeholder="Confirm password"
                required
            >


            <button type="submit">
                Register
            </button>

        </form>


        <a href="user_login.php">
            Already have an account? Login
        </a>

        <a href="index.php">
            ← Back to Home
        </a>

    </div>

</div>

</body>

</html>