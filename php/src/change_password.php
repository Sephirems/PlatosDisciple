<?php
session_start();

require_once(__DIR__ . '/../config/mysql.php');
require_once(__DIR__ . '/../config/databaseconnect.php');

if(isset($_SESSION['user_id']) && isset($_POST['ancien_mot_de_passe'], $_POST['nouveau_mot_de_passe'], $_POST['confirmation_mot_de_passe'])) {
    $idUtilisateur = $_SESSION['user_id'];
    $ancienMotDePasse = $_POST['ancien_mot_de_passe'];
    $nouveauMotDePasse = $_POST['nouveau_mot_de_passe'];
    $confirmationMotDePasse = $_POST['confirmation_mot_de_passe'];

    if($nouveauMotDePasse === $confirmationMotDePasse) {

        $sql = 'SELECT mot_de_passe FROM utilisateur WHERE id_utilisateur = :id_utilisateur';
        $statement = $conn->prepare($sql);
        $statement->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($ancienMotDePasse, $user['mot_de_passe'])) {
            $nouveauMotDePasseHash = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
            $updateSql = 'UPDATE utilisateur SET mot_de_passe = :nouveauMotDePasse WHERE id_utilisateur = :id_utilisateur';
            $updateStatement = $conn->prepare($updateSql);
            $updateStatement->bindParam(':nouveauMotDePasse', $nouveauMotDePasseHash, PDO::PARAM_STR);
            $updateStatement->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
            $updateStatement->execute();

            if (isset($_SESSION['origine'])) {
                header('Location: ' . $_SESSION['origine']);
                unset($_SESSION['origine']);
            } else {
                header('Location: ../index.php');
            }
            exit;
            
            if($updateStatement->rowCount() > 0) {
                echo "Le mot de passe a été modifié avec succès.";
            } else {
                echo "Erreur lors de la mise à jour du mot de passe.";
            }
        } else {
            echo "L'ancien mot de passe est incorrect.";
        }
    } else {
        echo "Le nouveau mot de passe et la confirmation ne correspondent pas.";
    }
} else {
    echo "Veuillez vous connecter pour modifier votre mot de passe.";
}
?>