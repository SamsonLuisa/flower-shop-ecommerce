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
        } else {
            die("Eroare: Utilizatorul nu a fost găsit.");
        }
    } catch (PDOException $e) {
        die("Eroare la interogare: " . $e->getMessage());
    }
    
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id > 0 && !isset($_POST["submit"])) {
        try {
            
            $sql = "SELECT * FROM conturi WHERE id = :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $record = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$record) {
                die("Eroare: Utilizatorul nu există.");
            }
        } catch (PDOException $e) {
            die("Eroare la interogare: " . $e->getMessage());
        }
    } elseif (isset($_POST["submit"])) {
        // Verificăm dacă toate datele sunt setate corect
        if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['usertype']) && !empty($_POST['id'])) {
            try {
                $id = intval($_POST['id']);
                $username = $_POST['username'];
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Criptare parola
                $usertype = $_POST['usertype'];

                // Salvăm valorile vechi pentru logare
                $old_username = $record['username'];
                $old_user_type = $record['user_type'];

                // Update în baza de date
                $sql1 = "UPDATE conturi SET username = :username, password = :password, user_type = :usertype WHERE id = :id";
                $stmt1 = $con->prepare($sql1);
                $stmt1->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt1->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt1->bindParam(':usertype', $usertype, PDO::PARAM_STR);
                $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt1->execute();

                // Apelăm procedura pentru logare
                $stmt_log = $con->prepare("CALL update_si_logheaza_cont(:id, :old_username, :new_username, :old_user_type, :new_user_type)");
                $stmt_log->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt_log->bindParam(':old_username', $old_username, PDO::PARAM_STR);
                $stmt_log->bindParam(':new_username', $username, PDO::PARAM_STR);
                $stmt_log->bindParam(':old_user_type', $old_user_type, PDO::PARAM_STR);
                $stmt_log->bindParam(':new_user_type', $usertype, PDO::PARAM_STR);
                $stmt_log->execute();

                
                header("Location: conturi.php");
                exit();
            } catch (PDOException $e) {
                echo "Eroare la actualizare: " . $e->getMessage();
            }
        } else {
            echo "Toate câmpurile sunt obligatorii!";
        }
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
    <title>Editare Cont</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <script src="js/faraclick.js"></script>
    <script src="js/faraplagiat.js"></script>
    <style>
        .navbar { background-color: rgb(197, 74, 139); }
        .navbar-nav { margin-left: auto; }
        .form-login { background-color: #f8f9fa; border-radius: 10px; padding: 40px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 500px; }
        .form-control { border-radius: 5px; font-size: 1.2rem; padding: 10px; }
        .btn-custom { background-color: rgb(197, 74, 139); color: white; font-size: 1.2rem; padding: 10px 20px; }
        .btn-custom:hover { background-color: rgb(125, 12, 72); }
        .container-form { display: flex; justify-content: center; align-items: center; height: 100vh; }
        .form-header { margin-bottom: 20px; font-size: 2rem; }
        .footer { background-color: rgb(197, 74, 139); color: white; padding: 20px 0; text-align: center; }
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
                    if (isset($_SESSION['username'])) {
                        echo '<li class="nav-item dropdown">';
                        echo '<a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">' . $_SESSION["username"] . '</a>';
                        echo '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">';
                        if (isset($_SESSION['username'])) {
                            if ($pos == 'admin') {
                                echo '<li><a class="dropdown-item" href="conturi.php">Conturi</a></li>';
                                echo '<li><a class="dropdown-item" href="bazaDeDateFlori.php" style="color: black;">Flori</a></li>';
                                echo '<li><hr class="dropdown-divider" /></li>';
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
            </div>
        </div>
    </nav>
    <div class="container container-form">
        <form method="post" action="edit.php" class="form-login">
            <h2 class="form-header text-center">Editare cont</h2> 
            <center>Utilizator:</center>
            <input class="form-control" style="width: 50%; margin-left: auto; margin-right: auto;" type="text" name="username" value="<?php echo $record['username']; ?>"><br>           
            <center>Parola:</center>
            <input class="form-control" style="width: 50%; margin-left: auto; margin-right: auto;" type="text" name="password" value="<?php echo $record['password']; ?>"><br>
            <center>Tipul de utilizator:</center>
            <input class="form-control" style="width: 50%; margin-left: auto; margin-right: auto;" type="text" name="usertype" value="<?php echo $record['user_type']; ?>"><br>
            <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
            <center><input type="Submit" name="submit" value="Submit" class="btn btn-primary btn-outline"></center>
        </form> 
    </div>   

    <footer class="footer">
        <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2025</p></div>
    </footer>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
