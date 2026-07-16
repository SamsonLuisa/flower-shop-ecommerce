<?php
session_start();
$status = 0;
require_once('connection.php'); // Conexiunea la baza de date

if (isset($_POST['Register'])) {
    $nume = $_POST['nume'];
    $pass = $_POST['pass'];
    $usertype = 'user';

    try {
        // Verificăm dacă username-ul există deja
        $query = "SELECT * FROM conturi WHERE username = :username";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':username', $nume, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $status = 1; // Username deja existent
        }

        if ($status == 0) {
            if (!empty($nume) && !empty($pass)) {
                // Adăugăm utilizatorul în baza de date
                $query = "INSERT INTO conturi (username, password, user_type) VALUES (:username, :password, :usertype)";
                $stmt = $con->prepare($query);
                $stmt->bindParam(':username', $nume, PDO::PARAM_STR);
                $stmt->bindParam(':password', $pass, PDO::PARAM_STR); // Parola nu este criptată, la fel ca în login.php
                $stmt->bindParam(':usertype', $usertype, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    header('Location: login_form.php'); // Redirect după înregistrare reușită
                    exit();
                } else {
                    echo "Eroare la înregistrare!";
                }
            } else {
                header('Location: registerform.php'); // Dacă nu sunt completate câmpurile
                exit();
            }
        }
    } catch (PDOException $e) {
        echo "Eroare de conexiune: " . $e->getMessage();
    }
}
?>
