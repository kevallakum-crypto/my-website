<?php

session_start();

include "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM admin
            WHERE username = ?
            AND password = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $username,
        $password
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $admin = mysqli_fetch_assoc($result);

        $_SESSION["admin_id"] = $admin["id"];
        $_SESSION["admin_username"] = $admin["username"];

        header("Location: admin/dashboard.php");
        exit();

    } else {

        $error = "Invalid username or password.";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Login</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<div class="login-container">

    <div class="login-box">

        <h1>📚 Study Notes</h1>

        <h2>Admin Login</h2>

        <?php if ($error != "") { ?>

            <p class="error">
                <?php echo $error; ?>
            </p>

        <?php } ?>

        <form method="POST">

            <label>
                Username
            </label>

            <input
                type="text"
                name="username"
                placeholder="Enter username"
                required
            >

            <label>
                Password
            </label>

            <input
                type="password"
                name="password"
                placeholder="Enter password"
                required
            >

            <button type="submit">
                Login
            </button>

        </form>

        <a href="index.php">
            ← Back to Home
        </a>

    </div>

</div>

</body>

</html>