<?php
$host = 'mysql_db'; 
$dbname = 'florarie';
$username = 'root';
$password = 'toor';


try {
    $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Conexiunea a eșuat: " . $e->getMessage());
}
?>
