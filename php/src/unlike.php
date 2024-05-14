<?php

session_start();

require_once 'mysql.php';
require_once 'databaseconnect.php';

$sql = 'DELETE FROM Favoris WHERE id_utilisateur = :idu AND id_oeuvre = :ido';
$statement = $conn->prepare($sql);

try {

    $statement->execute([
        ':idu' => $_SESSION['user_id'],
        ':ido' => htmlspecialchars($_POST['objectID'])
    ]);
    if (isset($_SESSION['origine'])) {
        header('Location: ' . $_SESSION['origine']);
        unset($_SESSION['origine']);
    } else {
        header('Location: index.php');
    }
    exit;
} catch (PDOException $e) {
    echo 'Erreur lors de la suppression du like : ' . $e->getMessage();
}
