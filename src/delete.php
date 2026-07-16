<?php
include 'connection.php'; 



if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    try {
        // Obținem username-ul utilizatorului înainte de ștergere
        $stmt = $con->prepare("SELECT username FROM conturi WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $username = $user['username']; // Stocăm username-ul pentru logare

            // Apelăm procedura stocată pentru ștergere și logare
            $stmt = $con->prepare("CALL sterge_si_logheaza_cont(:id, :username)");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();
            echo "Utilizator șters cu succes!";
            
            header("Location: conturi.php");
            exit();
        } else {
            echo "Eroare: Utilizatorul nu există!";
        }
    } catch (PDOException $e) {
        echo "Eroare la execuția procedurii: " . $e->getMessage();
    }
} else {
    echo "Eroare: ID utilizator invalid!";
}

?>
