<?php
include 'connection.php';
session_start();


if (!isset($_SESSION['username'])) {
    die("Eroare: Nu aveți permisiunea de a accesa această pagină.");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Eroare: ID-ul utilizatorului nu este valid.");
}

$id = intval($_GET['id']);

try {
    $sql = "SELECT * FROM conturi WHERE id = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Eroare: Utilizatorul nu există.");
    }
} catch (PDOException $e) {
    die("Eroare la interogare: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editare utilizator</title>
</head>
<body>
    <h2>Editare utilizator</h2>
    <form action="edit.php" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">

        <label for="user_type">Tip Utilizator:</label>
        <select name="user_type" required>
            <option value="admin" <?php if ($user['user_type'] == 'admin') echo 'selected'; ?>>Admin</option>
            <option value="user" <?php if ($user['user_type'] == 'user') echo 'selected'; ?>>User</option>
        </select>

        <button type="submit">Salvează</button>
    </form>
    <pre>
<?php print_r($_POST); ?>
</pre>
</body>
</html>
