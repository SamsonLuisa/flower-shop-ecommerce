<?php
include 'connection.php';
session_start();






if (isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    $_SESSION['username'] = $_COOKIE['username'];
}

if (isset($_SESSION['username'])) {
    $sql = "SELECT * FROM conturi WHERE username = :username";
    $stmt = $con->prepare($sql);
    $stmt->execute(['username' => $_SESSION['username']]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $pos = $record['user_type'] ?? null;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Acasa</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" /> 
    <script src="js/faraclick.js"></script>
    <script src="js/faraplagiat.js"></script>
    <style>
        .navbar { background-color: rgb(197, 74, 139); }
        .navbar-nav { margin-left: auto; }
        .custom-table {
            width: 80%;
            margin: 50px auto;
            border-collapse: collapse;
            background-color: rgb(197, 74, 139);
            color: white;
            font-family: Arial, sans-serif;
        }
        .custom-table th, .custom-table td {
            border: 1px solid #fff;
            padding: 10px;
            text-align: center;
        }
        .custom-table th { background-color: rgb(197, 74, 139); }
        .custom-table a {
            color: white;
            text-decoration: none;
        }
        .custom-table a:hover {
            text-decoration: underline;
        }
        .container-table {
            z-index: 0;
            position: relative;
        }
    </style>
</head>
<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" style="color: white;" href="index.php">Grădina cu Flori</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" style="color: white;" href="index.php">Acasa</a></li>
                    <li class="nav-item"><a class="nav-link" style="color: white;" href="about.php">Despre noi</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" style="color: white;" href="#" role="button" data-bs-toggle="dropdown">Produse</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="flori.php">Flori</a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item" href="listaflori.php">Lista flori</a></li>
                            <li><a class="dropdown-item" href="buchetespeciale.php">Buchete speciale</a></li>
                            <li><a class="dropdown-item" href="decoratiuni.php">Decoratiuni evenimente</a></li>
                        </ul>
                    </li>
                    <?php 
                    if(isset($_SESSION['username'])) {
                        echo '<li class="nav-item dropdown">';
                        echo '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" style="color: #808080;">' . $_SESSION["username"] . '</a>';
                        echo '<ul class="dropdown-menu">';
                        if ($pos == 'admin') {
                            echo '<li><a class="dropdown-item" href="conturi.php">Conturi</a></li>';
                            echo '<li><a class="dropdown-item" href="bazaDeDateFlori.php">Flori</a></li>';
                            echo '<li><hr class="dropdown-divider" /></li>';
                        }
                        echo '<li><a class="dropdown-item" href="logout.php">Logout</a></li>';
                        echo '</ul></li>';
                    } else {
                        echo '<a class="nav-link" href="login_form.php" style="color: white;">Login</a>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <br/><br/><br/>
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-black">
            <h1 class="display-4 fw-bolder">Flori</h1> 
        </div>
    </div>

    <center>
        <div class="inner">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                Caută: <input type="text" name="search_box" value="">
                <input type="submit" name="search" value="Caută">
            </form>
        </div>
    </center>

    <div class="container mt-5 container-table">
        <?php 
        $search_term = '';
        $sql = "SELECT * FROM flori";

        if (isset($_POST["search"])) {
            $search_term = trim($_POST["search_box"] ?? '');
            if (!empty($search_term)) {
                $sql .= " WHERE LOWER(denumire) LIKE LOWER(:search_term)";

            }
        }

        $stmt = $con->prepare($sql);

        if (!empty($search_term)) {
            $term = "%" . $search_term . "%";
            $stmt->bindParam(':search_term', $term, PDO::PARAM_STR);
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $nr = count($result);
        $nr2 = 0;

        if (empty($result)) {
            echo "<p style='color:red;'>Nu s-au găsit flori pentru categoria: <b>" . htmlspecialchars($search_term) . "</b>.</p>";
        }

        echo "<table class='custom-table'>
        <tr>
            <th>ID</th>
            <th>Denumire</th>
            <th>Imagine</th>
            <th>Culoare</th>
            <th>Categorie Flori</th>
            <th>Pret</th>
            <th>Comenzi</th>
            <th>Upload</th>
        </tr>";

        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['denumire']) . "</td>";
            echo "<td><img style='width:50px; height:50px;' src='multimedia/" . htmlspecialchars($row['imagine']) . "'></td>";
            echo "<td>" . htmlspecialchars($row['culoare']) . "</td>";
            echo "<td>" . htmlspecialchars($row['categorie']) . "</td>";
            echo "<td>" . htmlspecialchars($row['pret']) . "</td>";
            echo "<td>
                <a href='editimage.php?id=" . htmlspecialchars($row['id']) . "'>Editati</a>
                <a href='view.php?id=" . htmlspecialchars($row['id']) . "'>Vizualizati</a>
                <a href='deleteimage.php?id=" . htmlspecialchars($row['id']) . "'>Stergeti</a>
            </td>";

            $nr2++;
            if ($nr % 2 == 1) {
                echo ($nr2 == (int)($nr / 2) + 1) ? "<td><a href='upload.php?id=" . htmlspecialchars($row['id']) . "'>Incarcati</a></td>" : "<td></td>";
            } else {
                echo ($nr2 == (int)($nr / 2)) ? "<td><a href='upload.php?id=" . htmlspecialchars($row['id']) . "'>Incarcati</a></td>" : "<td></td>";
            }
            echo "</tr>";
        }

        if ($nr == 0) {
            echo "<tr><td colspan='7'></td><td><a href='upload.php'>Incarcati</a></td></tr>";
        }

        echo "</table>";
        ?>
    </div>

    <footer class="py-5 bg-dark">
        <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2025</p></div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
