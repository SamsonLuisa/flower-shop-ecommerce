<?php
session_start(); 
require_once 'update_trigger.php'; //eu aici trebuie sa pun fisierul in care am facut tabela pentru trigere?
include 'connection.php'; 
include 'clase.php';



$sql1 = "DROP PROCEDURE IF EXISTS CreateFlori";
$sql2 = "CREATE PROCEDURE CreateFlori()
BEGIN
DROP TABLE IF EXISTS flori;
CREATE TABLE flori(
id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
nume VARCHAR(100) NOT NULL,
culoare VARCHAR(100) NOT NULL,
marime VARCHAR(100) NOT NULL,
pret VARCHAR(100) NOT NULL,
denumire VARCHAR(100) NOT NULL,
categorie VARCHAR(100) NOT NULL,
imagine VARCHAR(255) NOT NULL
);
INSERT INTO flori VALUES('1','Decoratiuni','mov','mari','15','Aranjamente','decoratiuni','deco5.jpg');
INSERT INTO flori VALUES('2','Trandafiri','rosu','mici','5','Trandafiri','buchetespeciale','Btrand1.jpg');
INSERT INTO flori VALUES('3','Lalele','galbene','mici','30','Lalele','fire','lalea.jpg');
END";
$stmt1 = $con->prepare($sql1);
$stmt2 = $con->prepare($sql2);
$stmt1->execute();
$stmt2->execute();

$sql2 = "CALL CreateFlori()";
$q1=$con->query($sql2);

///////////////////////////////////////////////////////////////////////////////////////////
$sql3 = "DROP PROCEDURE IF EXISTS CreateConturi";
$sql4 = "CREATE PROCEDURE CreateConturi()
BEGIN
DROP TABLE IF EXISTS conturi;
CREATE TABLE conturi(
id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(100) NOT NULL,
password VARCHAR(100) NOT NULL,
user_type VARCHAR(100) NOT NULL
);
INSERT INTO conturi VALUES('1','admin','parola1','admin');
INSERT INTO conturi VALUES('2','luisa','parola','user');
END";
$stmt3 = $con->prepare($sql3);
$stmt4 = $con->prepare($sql4);
$stmt3->execute();
$stmt4->execute();

$sql4 = "CALL CreateConturi()";
$q3=$con->query($sql4);









/////////////////////////////////////////////////////////////////////

$sql1 = "DROP PROCEDURE IF EXISTS CreateFloriUpdateTable";
$sql2 = "CREATE PROCEDURE CreateFloriUpdateTable()
BEGIN
DROP TABLE IF EXISTS flori_update;
CREATE TABLE flori_update(
id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
nume VARCHAR(100) NOT NULL,
status VARCHAR(100) NOT NULL,
edtime DATETIME NOT NULL
);
END";
$stmt1 = $con->prepare($sql1);
$stmt2 = $con->prepare($sql2);
$stmt1->execute();
$stmt2->execute();

$sql2 = "CALL CreateFloriUpdateTable()";
$q1=$con->query($sql2);


///////////////////////////////////////////////////////////////////////////////////////////////////
 







$sql1 = "DROP PROCEDURE IF EXISTS insertFlori";
$sql2 = "CREATE PROCEDURE insertFlori(
    IN strNume varchar(30),
    IN strCuloare varchar(30),
    IN strMarime varchar(30),
    IN intPret int, 
    IN strCategorie varchar(30)
)
BEGIN
    INSERT INTO flori(nume, culoare, marime, pret, denumire, categorie)
    VALUES(strNume, strCuloare, strMarime, intPret, strNume, strCategorie);
END";

$stmt1 = $con->prepare($sql1);
$stmt2 = $con->prepare($sql2);
$stmt1->execute();
$stmt2->execute();  

//////////////////////////////////////



$sql1 = "DROP TRIGGER IF EXISTS BeforeInsertTrigger";
$sql2 = "CREATE TRIGGER BeforeInsertTrigger BEFORE INSERT ON flori FOR EACH ROW
BEGIN 
INSERT INTO flori_update(nume,status,edtime)VALUES(NEW.nume,'Before INSERTED',NOW());
SET NEW.nume=UPPER(NEW.nume);
END;";
 
$stmt1 = $con->prepare($sql1);
$stmt2 = $con->prepare($sql2);
$stmt1->execute();
$stmt2->execute();  



$sql3 = "DROP TRIGGER IF EXISTS AfterInsertTrigger";
$sql4 = "CREATE TRIGGER AfterInsertTrigger AFTER INSERT ON flori FOR EACH ROW
BEGIN 
INSERT INTO flori_update(nume,status,edtime)VALUES(NEW.nume,'INSERTED',NOW());
END;";
 
$stmt3 = $con->prepare($sql3);
$stmt4 = $con->prepare($sql4);
$stmt3->execute();
$stmt4->execute();  









// Verifică dacă cookie-ul 'username' este setat și sesiunea 'username' nu este setată
//Dacă utilizatorul are cookie cu numele username (ex: „remember me”) dar sesiunea nu e setată, atunci îl loghează automat.
if(isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    $_SESSION['username'] = $_COOKIE['username']; 
}

// Verifică dacă sesiunea 'username' este setată
if(isset($_SESSION['username'])) {
    $sql = "SELECT * FROM conturi WHERE username = :username"; 
    $stmt = $con->prepare($sql);
    $stmt->execute(['username' => $_SESSION['username']]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC); 

    //Se salvează tipul de utilizator în $pos
    if ($record) { // Verifică dacă utilizatorul există
        $pos = $record['user_type'];
    } else {
        $pos = null; // Evită erori dacă utilizatorul nu există
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
        <title>Acasă</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="js/faraclick.js"></script>
        <script src="js/faraplagiat.js"></script>
        <style>
            .navbar {
                background-color:rgb(197, 74, 139);
            }
            .navbar-nav {
                margin-left: auto;
            }
            .split-container {
                display: flex;
                height: 70vh;
                width: 100%;
                margin-top: 0px; /* sau chiar 0 dacă navbar-ul nu e prea mare */
            }

            .split-left, .split-right {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .split-left {
                background-color: #f7f7f7;
                flex-direction: column;
                padding: 20px;
                text-align: left;
            }

            .split-right {
                overflow: hidden;
                position: relative;
            }

            .video-box {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-left: 5px solid rgb(197, 74, 139);
            }

            .btn-custom {
                margin-top: 20px;
                padding: 7px 20px;
                background-color: rgb(197, 74, 139);
                color: white;
                font-size: 1.1rem;
                border: none;
                border-radius: 8px;
                text-decoration: none;
                transition: background-color 0.3s ease;
            }

            .btn-custom:hover {
                background-color: rgb(170, 50, 120);
            }

        </style>
    </head>
    <body>
        <audio autoplay loop hidden>
            <source src="multimedia/flowers.mp4" type="audio/mpeg">
        </audio>
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
        <br/><br/>
        
        <div class="split-container">
        <div class="split-left">
            <svg height="130" width="100%" xmlns="http://www.w3.org/2000/svg">
            <text x="20" y="80" style="stroke: rgb(253, 0, 253); stroke-width: 2; fill: rgb(253, 0, 253); font-size: 48px; font-family: Georgia, serif;">Grădina cu Flori</text>
            </svg>
            <p class="invitation-text">Descoperă un colț de liniște și culoare în Grădina cu Flori. Aici, fiecare petală are o poveste, iar fiecare buchet îți aduce un zâmbet.</p>
            <a href="flori.php" class="btn-custom" style="align-self: flex-start;">Descoperă mai mult</a>
        </div>

        <div class="split-right">
            <video autoplay muted loop playsinline class="video-box">
            <source src="multimedia/videoFlori.mp4" type="video/mp4">
            </video>
        </div>
    </div>

        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        
    </body>
</html>
