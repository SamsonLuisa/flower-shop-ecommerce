<?php
require_once "update_trigger.php";
include 'connection.php';
session_start();






$sql3 = "DROP PROCEDURE IF EXISTS updateFlori";
$sql4 = "CREATE PROCEDURE updateFlori(
    IN p_id INT,
    IN p_nume VARCHAR(100),
    IN p_culoare VARCHAR(100),
    IN p_marime VARCHAR(100),
    IN p_pret VARCHAR(100),
    IN p_denumire VARCHAR(100),
    IN p_categorie VARCHAR(100),
    IN p_imagine VARCHAR(255)
)
BEGIN
    UPDATE flori SET 
        nume = p_nume,
        culoare = p_culoare,
        marime = p_marime,
        pret = p_pret,
        denumire = p_denumire,
        categorie = p_categorie,
        imagine = p_imagine
    WHERE id = p_id;
END;";

$con->exec($sql3);
$con->exec($sql4);



$sql1 = "DROP TRIGGER IF EXISTS BeforeUpdateTrigger";
$sql2 = "CREATE TRIGGER BeforeUpdateTrigger BEFORE UPDATE ON flori FOR EACH ROW
BEGIN 

INSERT INTO flori_update(nume,status,edtime)VALUES(NEW.nume,'Before UPDATED',NOW());
SET NEW.nume=LOWER(NEW.nume);

END;";
 
$stmt1 = $con->prepare($sql1);
$stmt2 = $con->prepare($sql2);
$stmt1->execute();
$stmt2->execute();  
 
///////////////////////////////
 
$sql3 = "DROP TRIGGER IF EXISTS AfterUpdateTrigger";
$sql4 = "CREATE TRIGGER AfterUpdateTrigger AFTER UPDATE ON flori FOR EACH ROW
BEGIN 
INSERT INTO flori_update(nume,status,edtime)VALUES(NEW.nume,'UPDATED',NOW());
END;";
 
$stmt3 = $con->prepare($sql3);
$stmt4 = $con->prepare($sql4);
$stmt3->execute();
$stmt4->execute();  






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
        } else {
            $pos = null; 
        }
    } catch (PDOException $e) {
        die("Eroare la interogare: " . $e->getMessage());
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
                background-color:rgb(197, 74, 139); 
            }
            .navbar-nav {
                margin-left: auto; 
            }
            .form-login {
                background-color: #f8f9fa; 
                border-radius: 10px; 
                padding: 40px; 
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); 
                width: 100%; 
                max-width: 500px; 
            }
            .form-control {
                border-radius: 5px; 
                font-size: 1.2rem; 
                padding: 10px; 
            }
            .btn-custom {
                background-color: rgb(197, 74, 139); 
                color: white;
                font-size: 1.2rem; 
                padding: 10px 20px; 
            }
            .btn-custom:hover {
                background-color: rgb(197, 74, 139); 
            }
            .container-form {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .form-header {
                margin-bottom: 20px; 
                font-size: 2rem;
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
            <form method="post" action="save.php"  enctype="multipart/form-data">
                <h2 class="form-header text-center">Incarcati o intrare</h2>
                <input type="hidden" name="size" value="1000000">
                <div>
                    Floare:<textarea class="form-control" style="width: 40%; height:20px; margin-left: auto; margin-right: auto;" name="denumire"></textarea> 
                </div>
                <br/>
                <div>
                    Imagine:<input class="form-control" style="width: 40%; margin-left: auto; margin-right: auto;" type="file" name="image" > 
                </div>
                <br/>
                <div>
                    Culoare:<textarea class="form-control" style="width: 40%; height:20px; margin-left: auto; margin-right: auto;" name="culoare"></textarea> 
                </div>
                <br/>
                <div>
                    Categorie:<textarea class="form-control" style="width: 40%; height:20px; margin-left: auto; margin-right: auto;" name="categorie"></textarea> 
                </div>
                <br/>
                <div>
                    Pret:<textarea class="form-control" style="width: 40%; height:20px; margin-left: auto; margin-right: auto;" name="pret"></textarea> 
                </div>
                <br/>
                <div>
                    <input class="btn btn-primary btn-outline" type="submit" name="upload" value="Incarcati">
                </div>
            </form>
        </div> 
        <footer class="footer">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2025</p></div>
        </footer>
       
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        
        
    </body>
</html>