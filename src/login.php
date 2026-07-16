<?php
require_once('connection.php');
session_start();





if (isset($_POST['Login'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        header("Location: login_form.php"); 
        exit();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Verificăm utilizatorul în baza de date
        $query = "SELECT * FROM conturi WHERE username = :username";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($password === $user['password']) { // Verificare parolă exact cum o ai în DB
                $_SESSION['username'] = $username;
                $_SESSION['usertype'] = $user['user_type'];

                // Setăm cookie dacă a bifat "Remember Me"
                if (isset($_POST['rememberme'])) {
                    setcookie('username', $username, time() + (86400 * 30), "/");
                }

                header("Location: index.php"); // Redirect după login reușit
                exit();
            } else {
                echo "Parola este greșită!";
            }
        } else {
            echo "Utilizatorul nu există!";
        }
    } catch (PDOException $e) {
        echo " Eroare de conexiune: " . $e->getMessage();
    }
} else {
    echo "Introduceți datele de autentificare!";
}
?>
