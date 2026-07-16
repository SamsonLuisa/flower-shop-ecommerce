<?php
include 'connection.php';
session_start();

// Verificăm dacă există un cookie activ și inițializăm sesiunea
if (isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    $_SESSION['username'] = $_COOKIE['username'];
}

if (isset($_SESSION['username'])) {
    $sql = "SELECT * FROM conturi WHERE username = :username";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $pos = $record['user_type'];
}

// Dacă formularul **NU** a fost trimis, preluăm datele băuturii pentru editare
if (!isset($_POST['submit'])) {
    $sql = "SELECT * FROM flori WHERE ID = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Dacă formularul a fost trimis, actualizăm datele
    $sql2 = "SELECT * FROM flori WHERE ID = :id";
    $stmt = $con->prepare($sql2);
    $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
    $stmt->execute();
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

    // Preluăm datele din formular
    $denumire = $_POST['denumire'];
    $culoare = $_POST['culoare'];
    $pret = $_POST['pret'];
    $categorie = $_POST['categorie'];

    // Gestionăm imaginea (dacă s-a încărcat o nouă imagine)
    if (!empty($_FILES['imagine']['name'])) {
        $target = "multimedia/" . basename($_FILES['imagine']['name']);
        move_uploaded_file($_FILES['imagine']['tmp_name'], $target);
    } else {
        $target = $rec['imagine']; // Păstrăm imaginea existentă
    }

    // Actualizăm baza de date


    $sql1 = "UPDATE flori SET denumire = :denumire, imagine = :imagine, culoare = :culoare, categorie = :categorie, pret = :pret WHERE id = :id";
    $stmt = $con->prepare($sql1);
    $stmt->bindParam(':denumire', $denumire, PDO::PARAM_STR);
    $stmt->bindParam(':imagine', $target, PDO::PARAM_STR);
    $stmt->bindParam(':culoare', $culoare, PDO::PARAM_STR);
    $stmt->bindParam(':categorie', $categorie, PDO::PARAM_STR);
    $stmt->bindParam(':pret', $pret, PDO::PARAM_STR);
    $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);

    $stmt->execute();

    // Redirecționăm utilizatorul înapoi la pagina bazei de date
    header('Location: bazaDeDateCFlori.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Acasa</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/style.css" rel="stylesheet" />
        <script src="js/faraclick.js"></script>
        <script src="js/faraplagiat.js"></script>
        <style>
            .navbar {
                background-color:rgb(197, 74, 139); /* Maro */
            }
            .navbar-nav {
                margin-left: auto; /* Mută elementele la dreapta */
            }
            .form-login {
                background-color: #f8f9fa; /* Fundal alb */
                border-radius: 10px; /* Colțuri rotunjite */
                padding: 40px; /* Padding mărit */
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Umbră */
                width: 100%; /* Lățime */
                max-width: 500px; /* Lățime maximă */
            }
            .form-control {
                border-radius: 5px; /* Colțuri rotunjite */
                font-size: 1.2rem; /* Dimensiunea fontului mărită */
                padding: 10px; /* Padding mărit */
            }
            .btn-custom {
                background-color:rgb(197, 74, 139); /* Maro */
                color: white;
                font-size: 1.2rem; /* Dimensiunea fontului mărită */
                padding: 10px 20px; /* Padding mărit */
            }
            .btn-custom:hover {
                background-color: rgb(197, 74, 139); /* Maro mai închis */
            }
            .container-form {
                display: flex;
                justify-content: center;
                align-items: center;
                
                min-height: calc(100vh - 160px);
            }
            .form-header {
                margin-bottom: 20px; /* Spațiu sub header */
                font-size: 2rem; /* Dimensiunea fontului mărită */
            }
            .footer {
                background-color: rgb(197, 74, 139);
                color: white;
                padding: 20px 0;
                text-align: center;
            }
            body {
                padding-top: 100px; 
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
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">Produse</a>
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
                                       echo '<li><a class="dropdown-item" href="bazaDeDateFlori.php" style="color: black;">Bauturi</a></li>';
                                       echo '<li><hr class="dropdown-divider" /></li>';
                                         }
                                } 
                                echo '<li><a class="dropdown-item" href="logout.php">Logout</a></li>';
                            echo '</ul>';
                        echo '</li>';
                        }
                        else{
                            echo '<a class="nav-link" href="login_form.php" style="color: white;">Login</a>';
                        }
                        ?> 
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container container-form"> 
            <form method="post" enctype="multipart/form-data" class="form-login">
                <h2 class="form-header text-center">Vizualizare inregistrare</h2> 
                <center>Nume floare:</center>
                <input class="form-control mb-3" style="width: 30%; margin-left: auto; margin-right: auto;" type="text" name="denumire" value="<?php echo $record['denumire'];?>" readonly><br/>
                <center>Imagine:<br/>
                <img style="width:200px; height:200px;" src="multimedia/<?php echo $record['imagine']; ?>">
                </center>   
                <center>Culoare:</center>
                <input class="form-control mb-3" style="width: 30%; margin-left: auto; margin-right: auto;" type="text" name="culoare" value="<?php echo $record['culoare'];?>" readonly><br/>
                <center>Categorie:</center>
                <input class="form-control mb-3" style="width: 30%; margin-left: auto; margin-right: auto;" type="text" name="categorie" value="<?php echo $record['categorie'];?>" readonly><br/>
                <center>Pret:</center>
                <input class="form-control mb-3" style="width: 30%; margin-left: auto; margin-right: auto;" type="text" name="pret" value="<?php echo $record['pret'];?>" readonly><br/>
                <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
                <center><a href="bazaDeDateFlori.php" class="navbar-brand">Inapoi la tabel</a></center>
            </form>
        </div>   
        <footer class="footer">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2025</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        
    </body>
</html>