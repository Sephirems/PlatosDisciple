<?php 

session_start();

require_once(__DIR__ . '/../config/mysql.php');
require_once(__DIR__ . '/../config/databaseconnect.php');

$sql = 'DELETE FROM favoris WHERE id_utilisateur = :idu AND id_oeuvre = :ido';
$statement = $conn->prepare($sql);

try{

$statement->execute([
    ':idu' => $_SESSION['user_id'],
    ':ido' => htmlspecialchars($_POST['objectID'])
]);
if(isset($_SESSION['search_url'])) {
    header('Location: ' . $_SESSION['search_url']);
    unset($_SESSION['search_url']);
} else {
    header('Location: recherche_oeuvre.php');
}
exit;
} catch (PDOException $e){
    echo 'Erreur lors de la suppression du like : ' . $e->getMessage();
}
?>