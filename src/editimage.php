<?php
include 'connection.php';

session_start();

if (isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    $_SESSION['username'] = $_COOKIE['username'];
}

if (isset($_SESSION['username'])) {
    try {
        $sql = "SELECT * FROM conturi WHERE username = :username";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($record) {
            $pos = $record['user_type'];
        }
    } catch (PDOException $e) {
        die("Eroare la interogare: " . $e->getMessage());
    }
}

// Verificăm dacă formularul a fost trimis
if (!isset($_POST['submit'])) {
    if (isset($_GET['id'])) {
        try {
            $sql = "SELECT * FROM flori WHERE ID = :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
            $stmt->execute();
            $record = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Eroare la interogare: " . $e->getMessage());
        }
    }
} else {
    try {
        $sql2 = "SELECT * FROM flori WHERE ID = :id";
        $stmt2 = $con->prepare($sql2);
        $stmt2->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        $stmt2->execute();
        $rec = $stmt2->fetch(PDO::FETCH_ASSOC);

        if ($rec) { 
            $denumire = $_POST['denumire'];
            $culoare = $_POST['culoare'];
            $pret = $_POST['pret'];
            $categorie = $_POST['categorie'];

            // Verificăm dacă s-a încărcat o nouă imagine
            if (!empty($_FILES['image']['name'])) {
                $extensie = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $numeNou = uniqid('floare_') . '.' . $extensie;
                $targetFolder = 'multimedia/';
                $targetPath = $targetFolder . $numeNou;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    die("Eroare la mutarea fișierului.");
                }

                $target = $numeNou; // salvăm DOAR numele în baza de date
            } else {
                $target = $rec['imagine']; // folosim ce e deja salvat în DB
            }


            // Actualizăm în baza de date
            $sql1 = "UPDATE flori SET denumire = :denumire, culoare = :culoare, pret = :pret, imagine = :imagine, categorie = :categorie WHERE id = :id";
            $stmt1 = $con->prepare($sql1);
            $stmt1->bindParam(':denumire', $denumire, PDO::PARAM_STR);
            $stmt1->bindParam(':culoare', $culoare, PDO::PARAM_STR);
            $stmt1->bindParam(':pret', $pret, PDO::PARAM_STR);
            $stmt1->bindParam(':imagine', $target, PDO::PARAM_STR);
            $stmt1->bindParam(':categorie', $categorie, PDO::PARAM_STR);
            $stmt1->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
            $stmt1->execute();

            // Mutăm fișierul încărcat dacă este cazul
            if (!empty($_FILES['image']['name'])) {
                move_uploaded_file($_FILES['image']['tmp_name'], $target);
            }

            // Redirecționare după actualizare
            header('Location:bazaDeDateFlori.php');
            exit();
        }
    } catch (PDOException $e) {
        die("Eroare la actualizare: " . $e->getMessage());
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
                background-color: rgb(197, 74, 139); 
            }
            .navbar-nav {
                margin-left: auto; 
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
                </div>
            </div>
        </nav>
        <header>
            <div class="container px-4 px-lg-5 my-5">
                
                <div class="text-center text-black">
                    <h1 class="display-4 fw-bolder">Editare</h1>                             
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
                    Nume floare:<input class="form-control" style="width: 50%; margin-left: auto; margin-right: auto;" type="text" name="denumire" value="<?php echo $record['denumire'];?>"><br/>
                    Imagine:
                    <input class="form-control" style="width: 50%; margin-left: auto; margin-right: auto;" type="file" name="image"><br/>
                    <?php
                    $imagine = $record['imagine'] ?? '';

                    if (!empty($imagine)) {
                        // Extragem doar numele fișierului
                        $numeFisier = basename($imagine);
                        $caleFinala = './multimedia/' . $numeFisier;
                    } else {
                        $caleFinala = './multimedia/default.jpg'; // fallback dacă e goală imaginea
                    }
                    ?>
                    <img style="width:200px; height:200px;" src="<?php echo htmlspecialchars($caleFinala); ?>" alt="Imagine floare"><br/>


                    Culoare:<input class="form-control" style="width: 50%; margin-left: auto; margin-right: auto;" type="text" name="culoare" value="<?php echo $record['culoare'];?>"><br/>
                    Categorie:<input class="form-control" style="width: 50%; margin-left: auto; margin-right: auto;" type="text" name="categorie" value="<?php echo $record['categorie'];?>"><br/>
                    Pret:<input class="form-control" style="width: 50%; margin-left: auto; margin-right: auto;" type="text" name="pret" value="<?php echo $record['pret'];?>"><br/>
                    <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
                    <input type="Submit" name="submit" value="Submit" class="btn btn-primary btn-outline">
                    </form>
                </div>
            </div>
        </header> 
        <footer class="footer">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2025</p></div>
        </footer>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
       
        
    </body>
</html>