<?php

session_start();

if (!isset($_SESSION["user_id"])) {

    header("Location: user_login.php");

    exit();

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>User Dashboard</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<header>

    <div class="logo">
        📚 Study Notes
    </div>

    <nav>

        <a href="index.php">
            Home
        </a>

        <a href="user_logout.php">
            Logout
        </a>

    </nav>

</header>


<div class="dashboard">

    <h1>
        Welcome,
        <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
        👋
    </h1>

    <p>
        Welcome to your Study Notes Dashboard.
    </p>


    <div class="dashboard-cards">

        <div class="dashboard-card">

            <h2>📚 Browse Notes</h2>

            <p>
                Find notes by subject.
            </p>

            <a href="notes.php">
                View Notes
            </a>

        </div>


        <div class="dashboard-card">

            <h2>🔍 Search Notes</h2>

            <p>
                Search your required notes.
            </p>

            <a href="notes.php">
                Search
            </a>

        </div>


        <div class="dashboard-card">

            <h2>👤 My Profile</h2>

            <p>
                View your account information.
            </p>

            <a href="#">
                Profile
            </a>

        </div>

    </div>

</div>

</body>

</html>