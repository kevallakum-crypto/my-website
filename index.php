<?php
session_start();

include "config/database.php";


// Get total notes
$note_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM notes"
);

$note_data = mysqli_fetch_assoc($note_query);

$total_notes = $note_data["total"];


// Get total subjects
$subject_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM subjects"
);

$subject_data = mysqli_fetch_assoc($subject_query);

$total_subjects = $subject_data["total"];


// Get latest notes
$latest_sql = "SELECT notes.*, subjects.subject_name
               FROM notes
               INNER JOIN subjects
               ON notes.subject_id = subjects.id
               ORDER BY notes.id DESC
               LIMIT 6";

$latest_result = mysqli_query($conn, $latest_sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Study Notes</title>

    <link rel="stylesheet"
          href="style.css">

</head>


<body>


<!-- =========================
     NAVIGATION
========================= -->

<header>

    <div class="logo">
        📚 Study Notes
    </div>


    <nav>

        <a href="index.php">
            Home
        </a>

        <a href="notes.php">
            Notes
        </a>


        <?php if (isset($_SESSION["user_id"])) { ?>

            <a href="user_dashboard.php">
                Dashboard
            </a>

            <a href="user_logout.php">
                Logout
            </a>

        <?php } else { ?>

            <a href="user_login.php">
                User Login
            </a>

            <a href="register.php">
                Register
            </a>

        <?php } ?>


        <a href="login.php"
           class="admin-link">
            Admin Login
        </a>

    </nav>

</header>



<!-- =========================
     HERO
========================= -->

<section class="hero">

    <div class="hero-content">


        <p class="small-title">
            WELCOME TO
        </p>


        <h1>
            Study Notes
        </h1>


        <p class="hero-text">

            Find your study notes easily,
            search by subject and learn better.

        </p>


        <!-- SEARCH -->

        <form
            action="notes.php"
            method="GET"
            class="search-box"
        >

            <input
                type="text"
                name="search"
                placeholder="Search your notes..."
            >


            <button type="submit">
                🔍 Search
            </button>

        </form>


        <!-- BUTTONS -->

        <div class="hero-buttons">

            <a
                href="notes.php"
                class="primary-btn"
            >
                Browse Notes
            </a>


            <?php if (isset($_SESSION["user_id"])) { ?>

                <a
                    href="user_dashboard.php"
                    class="secondary-btn"
                >
                    My Dashboard
                </a>

            <?php } else { ?>

                <a
                    href="register.php"
                    class="secondary-btn"
                >
                    Create Account
                </a>

            <?php } ?>

        </div>

    </div>

</section>



<!-- =========================
     STATISTICS
========================= -->

<section class="features">

    <div class="section-heading">

        <p>
            STUDY SMART
        </p>

        <h2>
            Our Notes Platform
        </h2>

    </div>


    <div class="feature-container">


        <!-- TOTAL NOTES -->

        <div class="feature-card">

            <div class="feature-icon">
                📚
            </div>

            <h3>
                <?php echo $total_notes; ?>+
            </h3>

            <p>
                Study Notes
            </p>

        </div>


        <!-- SUBJECTS -->

        <div class="feature-card">

            <div class="feature-icon">
                📖
            </div>

            <h3>
                <?php echo $total_subjects; ?>+
            </h3>

            <p>
                Subjects
            </p>

        </div>


        <!-- SEARCH -->

        <div class="feature-card">

            <div class="feature-icon">
                🔍
            </div>

            <h3>
                Easy Search
            </h3>

            <p>
                Find notes quickly
                using search.
            </p>

        </div>


        <!-- PDF -->

        <div class="feature-card">

            <div class="feature-icon">
                📄
            </div>

            <h3>
                PDF Notes
            </h3>

            <p>
                View and download
                study materials.
            </p>

        </div>

    </div>

</section>



<!-- =========================
     LATEST NOTES
========================= -->

<section class="features">

    <div class="section-heading">

        <p>
            RECENTLY ADDED
        </p>

        <h2>
            Latest Notes
        </h2>

    </div>


    <div class="feature-container">


        <?php if (mysqli_num_rows($latest_result) > 0) { ?>


            <?php while ($note = mysqli_fetch_assoc($latest_result)) { ?>


                <div class="feature-card">


                    <div class="feature-icon">
                        📄
                    </div>


                    <h3>

                        <?php
                        echo htmlspecialchars(
                            $note["title"]
                        );
                        ?>

                    </h3>


                    <p>

                        <strong>
                            Subject:
                        </strong>

                        <?php
                        echo htmlspecialchars(
                            $note["subject_name"]
                        );
                        ?>

                    </p>


                    <?php if (!empty($note["chapter"])) { ?>

                        <p>

                            <strong>
                                Chapter:
                            </strong>

                            <?php
                            echo htmlspecialchars(
                                $note["chapter"]
                            );
                            ?>

                        </p>

                    <?php } ?>


                    <br>


                    <a
                        href="view_note.php?id=<?php echo $note["id"]; ?>"
                        class="btn"
                    >
                        View Note
                    </a>


                </div>


            <?php } ?>


        <?php } else { ?>


            <div class="feature-card">

                <div class="feature-icon">
                    📚
                </div>

                <h3>
                    No Notes Yet
                </h3>

                <p>
                    Notes will appear here
                    when admin adds them.
                </p>

            </div>


        <?php } ?>


    </div>

</section>



<!-- =========================
     CTA
========================= -->

<section class="cta">


    <h2>
        Ready to Start Learning?
    </h2>


    <p>
        Create your account and explore
        your study notes.
    </p>


    <?php if (isset($_SESSION["user_id"])) { ?>


        <a
            href="notes.php"
            class="primary-btn"
        >
            Browse Notes
        </a>


    <?php } else { ?>


        <a
            href="register.php"
            class="primary-btn"
        >
            Get Started
        </a>


    <?php } ?>


</section>



<!-- =========================
     FOOTER
========================= -->

<footer>


    <div class="footer-content">


        <h3>
            📚 Study Notes
        </h3>


        <p>
            Simple platform for managing
            and accessing study notes.
        </p>


        <div class="footer-links">


            <a href="index.php">
                Home
            </a>


            <a href="notes.php">
                Notes
            </a>


            <a href="user_login.php">
                User Login
            </a>


            <a href="register.php">
                Register
            </a>


            <a href="login.php">
                Admin
            </a>


        </div>


    </div>


    <div class="copyright">


        <p>
            © 2026 Study Notes.
            All Rights Reserved.
        </p>


    </div>


</footer>


</body>

</html>