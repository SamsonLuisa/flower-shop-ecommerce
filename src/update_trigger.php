<?php
include 'connection.php';

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

?>