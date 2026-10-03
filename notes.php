<?php
session_start();

include "config/database.php";

$search = "";
$subject_filter = "";

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}

if (isset($_GET["subject"])) {
    $subject_filter = intval($_GET["subject"]);
}


// Get all subjects
$subject_sql = "SELECT * FROM subjects ORDER BY subject_name ASC";
$subject_result = mysqli_query($conn, $subject_sql);


// Get notes
$sql = "SELECT notes.*, subjects.subject_name
        FROM notes
        INNER JOIN subjects
        ON notes.subject_id = subjects.id
        WHERE 1=1";


// Search
if (!empty($search)) {

    $sql .= " AND (
                notes.title LIKE ?
                OR notes.chapter LIKE ?
                OR notes.description LIKE ?
                OR subjects.subject_name LIKE ?
              )";
}


// Subject filter
if ($subject_filter > 0) {
    $sql .= " AND notes.subject_id = ?";
}


$sql .= " ORDER BY notes.id DESC";


$stmt = mysqli_prepare($conn, $sql);


if (!empty($search) && $subject_filter > 0) {

    $search_value = "%" . $search . "%";

    mysqli_stmt_bind_param(
        $stmt,
        "ssssi",
        $search_value,
        $search_value,
        $search_value,
        $search_value,
        $subject_filter
    );

} elseif (!empty($search)) {

    $search_value = "%" . $search . "%";

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $search_value,
        $search_value,
        $search_value,
        $search_value
    );

} elseif ($subject_filter > 0) {

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $subject_filter
    );
}


mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Browse Notes - Study Notes</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>


<header>

    <nav class="navbar">

        <div class="logo">
            Study Notes
        </div>

        <ul class="nav-links">

            <li>
                <a href="index.html">Home</a>
            </li>

            <?php if (isset($_SESSION["user_id"])) { ?>

                <li>
                    <a href="user_dashboard.php">Dashboard</a>
                </li>

                <li>
                    <a href="user_logout.php">Logout</a>
                </li>

            <?php } else { ?>

                <li>
                    <a href="user_login.php">Login</a>
                </li>

                <li>
                    <a href="register.php">Register</a>
                </li>

            <?php } ?>

        </ul>

    </nav>

</header>


<section class="dashboard">

    <h1>Study Notes</h1>

    <p>
        Search and find your study notes easily.
    </p>


    <!-- Search and Filter -->

    <form method="GET" class="search-box">

        <input
            type="text"
            name="search"
            placeholder="Search notes..."
            value="<?php echo htmlspecialchars($search); ?>"
        >


        <select name="subject">

            <option value="0">
                All Subjects
            </option>

            <?php while ($subject = mysqli_fetch_assoc($subject_result)) { ?>

                <option
                    value="<?php echo $subject["id"]; ?>"
                    <?php
                    if ($subject_filter == $subject["id"]) {
                        echo "selected";
                    }
                    ?>
                >

                    <?php echo htmlspecialchars($subject["subject_name"]); ?>

                </option>

            <?php } ?>

        </select>


        <button type="submit" class="btn">
            Search
        </button>

    </form>


    <br>


    <!-- Notes -->

    <div class="dashboard-cards">

        <?php if (mysqli_num_rows($result) > 0) { ?>

            <?php while ($note = mysqli_fetch_assoc($result)) { ?>

                <div class="dashboard-card">

                    <h2>
                        <?php echo htmlspecialchars($note["title"]); ?>
                    </h2>


                    <p>
                        <strong>Subject:</strong>

                        <?php
                        echo htmlspecialchars(
                            $note["subject_name"]
                        );
                        ?>
                    </p>


                    <?php if (!empty($note["chapter"])) { ?>

                        <p>
                            <strong>Chapter:</strong>

                            <?php
                            echo htmlspecialchars(
                                $note["chapter"]
                            );
                            ?>
                        </p>

                    <?php } ?>


                    <?php if (!empty($note["description"])) { ?>

                        <p>

                            <?php
                            echo htmlspecialchars(
                                $note["description"]
                            );
                            ?>

                        </p>

                    <?php } ?>


                    <a
                        href="view_note.php?id=<?php echo $note["id"]; ?>"
                        class="btn"
                    >
                        View Note
                    </a>

                </div>

            <?php } ?>

        <?php } else { ?>

            <div class="dashboard-card">

                <h2>No Notes Found</h2>

                <p>
                    No notes match your search.
                </p>

            </div>

        <?php } ?>

    </div>

</section>


</body>

</html>