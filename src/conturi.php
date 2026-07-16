<?php
include 'connection.php';
session_start();




$sql1 = "DROP TRIGGER IF EXISTS BeforeUpdateTrigger";
$sql2 = "CREATE TRIGGER BeforeUpdateTrigger BEFORE UPDATE ON flori_update FOR EACH ROW
BEGIN 
SET NEW.nume=LOWER(NEW.nume);
END;";
 
$stmt1 = $con->prepare($sql1);
$stmt2 = $con->prepare($sql2);
$stmt1->execute();
$stmt2->execute();  






 
$sql1 = "DROP PROCEDURE IF EXISTS UpdateFlori";
$sql2 = "CREATE PROCEDURE updateFlori(
IN strNume varchar(80),
IN strCuloare varchar(80),
IN strMarime varchar(80),
IN intPret int
)
BEGIN
UPDATE flori SET nume = strNume, culoare = strCuloare, marime = strMarime WHERE pret = intPret;
END";
 
$stmt1 = $con->prepare($sql1);
$stmt2 = $con->prepare($sql2);
$stmt1->execute();
$stmt2->execute();  
 
$nume='trandafiri';
$culoare='roz';
$marime='mari';
$pret='75';
$sql="CALL updateFlori('$nume', '$culoare', '$marime', $pret)";
$q=$con->query($sql);




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
        $pos = null; 
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
        
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
       
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        
        <link href="css/styles.css" rel="stylesheet" />
        <script src="js/faraclick.js"></script>
        <script src="js/faraplagiat.js"></script>
        <style>
            .navbar {
                background-color: rgb(197, 74, 139);
            }
            .navbar-nav {
                margin-left: auto;
            }
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
            .custom-table th {
                background-color: rgb(125, 12, 72);
            }
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
                            echo '<a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #808080;">'.$_SESSION["username"].'</a>';
                            echo '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">';

                                if(isset($_SESSION['username'])){
                                    if($pos == 'admin' ){
                                       echo '<li><a class="dropdown-item" href="conturi.php" style="color: black;">Conturi</a></li>';
                                       echo '<li><a class="dropdown-item" href="bazaDeDateFlori.php">Flori</a></li>';
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
        <br/><br/><br/>
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-black">
                <h1 class="display-4 fw-bolder">Utilizatori</h1> 
            </div>
        </div>
        <div class="container mt-5 container-table">
        <?php
// Selecția utilizatorilor din baza de date cu PDO
$sql = "SELECT id, username, user_type FROM conturi";
$stmt = $con->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table class='custom-table' style='max-height: 500px; overflow-y: auto;'>
<tr>
<th>ID</th>
<th>Utilizator</th>
<th>Pozitie</th>
<th>Comenzi</th>
</tr>";

foreach ($result as $row) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
    echo "<td>" . htmlspecialchars($row['user_type']) . "</td>";
    echo "<td>
        <a href=\"edit.php?id=" . urlencode($row['id']) . "\">Editati</a> |
        <a href=\"delete.php?id=" . urlencode($row['id']) . "\" style=\"\">Stergeti</a>
    </td>";
    echo "</tr>";
}
echo "</table>";
?>

        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <script src="js/scripts.js"></script>
    </body>
</html>
