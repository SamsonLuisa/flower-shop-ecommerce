<?php
require_once "connection.php";




if (isset($_POST['upload'])) {
    $filename = md5(uniqid(time())) . basename($_FILES['image']['name']);
    $target = "multimedia/" . $filename;

    // Preluăm datele din formular
    $denumire = $_POST['denumire'];
    $culoare = $_POST['culoare'];
    $marime = "medie";
    $pret = $_POST['pret'];
    $categorie = $_POST['categorie'];
    

    // Verificăm dacă imaginea a fost încărcată corect
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        try {
            // Pregătim interogarea SQL cu PDO
            $sql = "INSERT INTO flori (nume, denumire, culoare, marime, pret, imagine, categorie) 
                VALUES (:denumire, :denumire, :culoare, :marime, :pret, :imagine, :categorie)";
            $stmt = $con->prepare($sql);

            // Legam parametrii pentru a preveni SQL Injection
            $stmt = $con->prepare($sql);

            $stmt->bindParam(':denumire', $nume);
            $stmt->bindParam(':denumire', $denumire);
            $stmt->bindParam(':culoare', $culoare);
            $stmt->bindParam(':marime', $marime);
            $stmt->bindParam(':pret', $pret);
            $stmt->bindParam(':imagine', $filename);
            $stmt->bindParam(':categorie', $categorie);

            // Executăm interogarea
            $stmt->execute();

            // Redirecționăm utilizatorul
            header('Location: bazaDeDateFlori.php');
            exit();
        } catch (PDOException $e) {
            die("Eroare la încărcarea imaginii: " . $e->getMessage());
        }
    } else {
        die("Eroare la mutarea fișierului.");
    }
}
?>
