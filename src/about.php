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

    if ($record) { // Verifică dacă există un rezultat
        $pos = $record['user_type'];
    } else {
        $pos = null; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Acasa</title>
  
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico"/>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet"/>
    
    <link href="css/styles.css" rel="stylesheet"/>
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
        .table-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            margin-bottom: 40px; 
        }
        table {
            z-index: 2;
            width: 60%;
            border-collapse: collapse;
            border-spacing: 0;
            box-shadow: 0 2px 15px rgba(64, 64, 64, .7);
            border-radius: 12px 12px 0 0;
            overflow: hidden;
        }
        .social-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/ro_RO/sdk.js#xfbml=1&version=v17.0"
        nonce="PmZsr6qH"></script>
<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" style="color: white;" href="index.php">Grădina cu Flori</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" style="color: white;" aria-current="page"
                                        href="index.php">Acasa</a></li>
                <li class="nav-item"><a class="nav-link" style="color: white; href="about.php">Despre noi</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">Produse</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="flori.php">Flori</a></li>
                        <li>
                            <hr class="dropdown-divider"/>
                        </li>
                        <li><a class="dropdown-item" href="listaflori.php">Lista fori</a></li>
                        <li><a class="dropdown-item" href="buchetespeciale.php">Buchete speciale</a></li>
                        <li><a class="dropdown-item" href="decoratiuni.php">Decoratiuni evenimente</a></li>
                    </ul>
                </li>
                <?php
                if (isset($_SESSION['username'])) {
                    echo '<li class="nav-item dropdown">';
                    echo '<a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">' . $_SESSION["username"] . '</a>';
                    echo '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">';
                    if (isset($_SESSION['username'])) {
                        if ($pos == 'admin') {
                            echo '<li><a class="dropdown-item" href="conturi.php">Conturi</a></li>';
                            echo '<li><a class="dropdown-item" href="bazaDeDateFlori.php" style="color: black;">Flori</a></li>';
                            echo '<li>
                                  <hr class="dropdown-divider"/>
                                  </li>';
                        }
                    }
                    echo '<li><a class="dropdown-item" href="logout.php">Logout</a></li>';
                    echo '</ul>';
                    echo '</li>';
                } else {
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

<canvas id="myCanvas" width="300" height="70" class="d-flex"
        style="margin-top: 65px; margin-left: auto; margin-right: auto;"></canvas>
<script>
    var canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext('2d');
    ctx.shadowColor = "rgb(190, 190, 190)";
    ctx.shadowOffsetX = 10;
    ctx.shadowOffsetY = 10;
    ctx.shadowBlur = 10;
    ctx.font = "50px arial";
    var gradient = ctx.createLinearGradient(0, 0, 150, 100);
    gradient.addColorStop(0, "rgb(0, 0, 0)");
    gradient.addColorStop(1, "rgb(0, 0, 0)");
    ctx.fillStyle = gradient;
    ctx.fillText("Despre noi", 10, 50);
</script>
<br><br>
<div class="container px-4 px-lg-5 my-5">
    <p class="lead fw-normal text-black-50 mb-0">La Grădina cu Flori, misiunea noastră este să aducem frumusețea și parfumul naturii în viața fiecărui client. Ne dedicăm pasiunii pentru flori și creăm aranjamente care spun povești și transmit emoții.</p>
    <br/>
    <p class="lead fw-normal text-black-50 mb-0">Credem în prospețimea și calitatea florilor, motiv pentru care selectăm cu grijă fiecare floare și fiecare plantă. Fiecare buchet este realizat manual, cu atenție la detalii și respect pentru natură.</p>
    <br/>
    <p class="lead fw-normal text-black-50 mb-0">Echipa noastră este formată din florari talentați și creativi, gata să transforme orice ocazie într-un moment special. De la aniversări și nunți, până la simple gesturi de iubire sau recunoștință, noi suntem aici pentru tine.</p>
    <br/>
    <p class="lead fw-normal text-black-50 mb-0">Te invităm să ne vizitezi și să descoperi farmecul florilor proaspete, aranjamentele noastre inspirate și atmosfera caldă a florăriei noastre. La Grădina cu Flori, fiecare floare este o declarație de suflet.</p>
</div>

<center>
    <div class="container m-auto">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/N_61_oXmfvQ"
                title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen></iframe>
    </div>
</center>
<br/>

<div style="margin-left:30px;" class="text-center text-black">
    <h4 class="display-4 fw-bolder">Unde ne puteti gasi?</h4>
</div>
<center>
<div class="container">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2712.1317023402635!2d27.569325211691755!3d47.17485791757176!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40cafb61af5ef507%3A0x95f1e37c73c23e74!2sAlexandru%20Ioan%20Cuza%20University!5e0!3m2!1sen!2sro!4v1684851506872!5m2!1sen!2sro"
            width="560" height="315" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>    
</div>
    </center>
<?php
include 'clase.php';
$angajat1 = new angajati();
$angajat2 = new angajati();
$angajat1->setNume('Evelina');
$angajat2->setNume('Sofia');
$angajat1->setFunctie('angajat');
$angajat2->setFunctie('angajat');


?>
<div class="container px-4 px-lg-5 my-5">
    <div class="text-center text-black">
        <h4 class="display-4 fw-bolder">Angajatii nostrii</h4>
    </div>
</div>

<div class="table-container">
    <table border="1">
        <tr>
            <th>Nume</th>
            <th>Functie</th>
        </tr>
        <tr>
            <td><?php $angajat1->afisareNume(); ?></td>
            <td><?php $angajat1->afisareFunctie(); ?></td>
            
        </tr>
        <tr>
        <td><?php $angajat2->afisareNume(); ?></td>
            <td><?php $angajat2->afisareFunctie(); ?></td>
        </tr>
       
    </table>
</div>


<footer class="footer">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; Your Website 2025</p></div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="js/scripts.js"></script>
</body>
</html>
