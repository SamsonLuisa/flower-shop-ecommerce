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
    
    if ($record) {
        $pos = $record['user_type'];
    } else {
        $pos = null; // Setați o valoare implicită dacă nu se găsește utilizatorul
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Produse noi</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <style>
            .navbar {
                background-color: rgb(197, 74, 139);
            }
            .navbar-nav {
                margin-left: auto;
            }
            header {
                background: rgb(125, 12, 72);
                color: white;
                padding: 60px 0;
                text-align: center;
            }
            .footer {
                background-color: rgb(197, 74, 139);
                color: white;
                padding: 20px 0;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" style="color: white;" href="index.php">Grădina cu Flori</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" style="color: white;" aria-current="page" href="index.php">Acasa</a></li>
                        <li class="nav-item"><a class="nav-link" style="color: white;" href="about.php">Despre noi</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #808080;">Produse</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="flori.php">Flori</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a class="dropdown-item" href="listaflori.php">Lista fori</a></li>
                                <li><a class="dropdown-item" href="buchetespeciale.php">Buchete speciale</a></li>
                                <li><a class="dropdown-item" href="decoratiuni.php">Decoratiuni evenimente</a></li>
                            </ul>
                        </li>
                        <?php 
                        if(isset($_SESSION['username'])){
                        echo '<li class="nav-item dropdown">';
                            echo '<a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">'.$_SESSION["username"].'</a>';
                            echo '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">';
                                
                                if(isset($_SESSION['username'])){
                                    if($pos == 'admin' ){
                                       echo '<li><a class="dropdown-item" href="conturi.php">Conturi</a></li>';
                                       echo '<li><a class="dropdown-item" href="bazaDeDateFlori.php" style="color: black;">Flori</a></li>';
                                       echo '<li><hr class="dropdown-divider" /></li>';
                                        }
                                }
                                
                                //echo '<li><hr class="dropdown-divider" /></li>';
                                echo '<li><a class="dropdown-item" href="logout.php">Logout</a></li>';
                            echo '</ul>';
                        echo '</li>';
                        }
                        else{
                            echo '<a class="nav-link" href="login_form.php" style="color: white;">Login</a>';
                        }
                        ?> 
                    </ul>
                    <div class="d-flex align-items-center ms-3">
                        <a href="https://facebook.com" target="_blank" class="text-white me-2">
                            <i class="bi bi-facebook" style="font-size: 1.5rem;"></i>
                        </a>
                        <a href="https://instagram.com" target="_blank" class="text-white me-2">
                            <i class="bi bi-instagram" style="font-size: 1.5rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Header-->
        <header class="py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Alegeti florile iar noi iti vom creea buchetul</h1> 
                </div>
            </div>
        </header>
        <!-- Section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <?php
$stmt = $con->query("SELECT * FROM flori");
$flori = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($flori as $row) {
    if ($row['categorie'] == 'fire') {
        echo '<div class="col mb-5">
                <div class="card h-100">
                    <!-- Product image -->
                    <img class="card-img-top" src="multimedia/' . htmlspecialchars($row["imagine"]) . '" alt="..." />
                    <!-- Product details -->
                    <div class="card-body p-4">
                        <div class="text-center">
                            <!-- Product name -->
                            <h5 class="fw-bolder">' . htmlspecialchars($row["denumire"]) . '</h5>
                            <p class="fw-bolder m-auto">' . htmlspecialchars($row["culoare"]) . '</p>
                            <!-- Product price -->
                            <br/><br/><p class="fw-bolder m-auto">Pret: ' . htmlspecialchars($row['pret']) . ' lei</p>
                        </div>
                    </div>
                </div>
            </div>';
    }
}
?>
                </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="footer">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2025</p></div>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
