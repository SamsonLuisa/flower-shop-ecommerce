<?php
include 'connection.php';


$sql1 = "DROP PROCEDURE IF EXISTS deleteFLori";
$sql2 = "CREATE PROCEDURE deleteFlori(
IN intid int
)
BEGIN 
DELETE FROM flori WHERE id = intid;
END";

$stmt1 = $con->prepare($sql1);
$stmt2 = $con->prepare($sql2);
$stmt1->execute();
$stmt2->execute();  




$sql1 = "DROP TRIGGER IF EXISTS BeforeDeleteTrigger";
$sql2 = "CREATE TRIGGER BeforeDeleteTrigger BEFORE DELETE ON flori FOR EACH ROW
BEGIN 
INSERT INTO flori_update(nume,status,edtime)VALUES(OLD.nume,'DELETED',NOW());
END;";
 
$stmt1 = $con->prepare($sql1);
$stmt2 = $con->prepare($sql2);
$stmt1->execute();
$stmt2->execute();  









if ((isset($_GET['produs_id']) && is_numeric($_GET['produs_id'])) || 
    (isset($_GET['id']) && is_numeric($_GET['id']))) {

    $id = isset($_GET['produs_id']) ? intval($_GET['produs_id']) : intval($_GET['id']);

    $sql = "CALL deleteFlori(:id)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: bazaDeDateFlori.php");
    exit();
} else {
    echo "Parametrul 'produs_id' nu a fost furnizat sau nu este valid!";
}
?>