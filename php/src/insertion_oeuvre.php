<?php

session_start();

require_once(__DIR__ . '/../config/mysql.php');
require_once(__DIR__ . '/../config/databaseconnect.php');

$sql = 'SELECT COUNT(*) FROM oeuvre WHERE id_oeuvre = :id';
$statement = $conn->prepare($sql);
$statement->execute([':id' => htmlspecialchars($_POST['objectID'])]);
$exists = $statement->fetchColumn() > 0;

if (!$exists) {
    $sql = 'INSERT INTO oeuvre(id_oeuvre, image, titre, culture, nom_artiste, bio_artiste, nationalite_artiste, date_fin, taille, pays, classification) VALUES (:id, :im, :ti, :cu, :adn, :adb, :an, :oed, :di, :co, :cl)';
    $statement = $conn->prepare($sql);

    try {
        $statement->execute([
            ':id' => htmlspecialchars($_POST['objectID']),
            ':im' => htmlspecialchars($_POST['image']),
            ':ti' => htmlspecialchars($_POST['title']),
            ':cu' => htmlspecialchars($_POST['culture']),
            ':adn' => htmlspecialchars($_POST['artistDisplayName']),
            ':adb' => htmlspecialchars($_POST['artistDisplayBio']),
            ':an' => htmlspecialchars($_POST['artistNationality']),
            ':oed' => htmlspecialchars($_POST['objectEndDate']),
            ':di' => htmlspecialchars($_POST['dimensions']),
            ':co' => htmlspecialchars($_POST['country']),
            ':cl' => htmlspecialchars($_POST['classification'])
        ]);
    } catch (PDOException $e) {
        echo 'Erreur lors de l\'insertion des données de l\'oeuvre : ' . $e->getMessage();
    }
}

$sql = 'INSERT INTO favoris(id_utilisateur, id_oeuvre) VALUES (:idu, :ido)';
$statement = $conn->prepare($sql);

try {
    $statement->execute([
        ':idu' => $_SESSION['user_id'],
        ':ido' => htmlspecialchars($_POST['objectID'])
    ]);
    if (isset($_SESSION['search_url'])) {
        header('Location: ' . $_SESSION['search_url']);
        unset($_SESSION['search_url']);
    } else {
        header('Location: recherche_oeuvre.php');
    }
    exit;
} catch (PDOException $e) {
    echo 'Erreur lors de l\'insertion des données dans favoris : ' . $e->getMessage();
}
