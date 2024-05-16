<?php
if (!empty($_SERVER['HTTPS'])) {
    header("Strict-Transport-Security: max-age=31536000");
}
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
session_start();

require_once(__DIR__ . 'mysql.php');
require_once(__DIR__ . 'databaseconnect.php');
require_once(__DIR__ . 'functions.php');

$_SESSION['origine'] = $_SERVER['REQUEST_URI'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['ancien_mot_de_passe'];
    $newPassword = $_POST['nouveau_mot_de_passe'];
    $confirmPassword = $_POST['confirmation_mot_de_passe'];

    $query = $conn->prepare("SELECT mot_de_passe FROM Utilisateur WHERE id_utilisateur = :id");
    $query->bindParam(':id', $_SESSION['user_id']);
    $query->execute();
    $user = $query->fetch();

    if (password_verify($oldPassword, $user['mot_de_passe'])) {
        if ($newPassword === $confirmPassword) {
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateQuery = $conn->prepare("UPDATE Utilisateur SET mot_de_passe = :password WHERE id_utilisateur = :id");
            $updateQuery->bindParam(':password', $newPasswordHash);
            $updateQuery->bindParam(':id', $_SESSION['user_id']);
            $updateQuery->execute();
            $updateMessage = 'Le mot de passe a été mis à jour avec succès.';
        } else {
            $errorMessage = 'Le nouveau mot de passe et la confirmation ne correspondent pas.';
        }
    } else {
        $errorMessage = 'Le mot de passe est incorrect.';
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Bienvenue sur Platos Disciple</title>
</head>

<body>
    <header>
        <?php include 'header.php'; ?>
    </header>

    <?php

    if (isset($_SESSION['user_id'])) {

        $idUtilisateur = $_SESSION['user_id'];
        $userData = show_user_data($conn, $idUtilisateur);
        $results = show_user_likes($conn, $idUtilisateur);
    ?>

        <div class="user-data">
            <h2>Vos données</h2>
            <?php foreach ($userData as $data) ?>
            <p><strong>Nom d'utilisateur:</strong> <?php echo $data['nom_utilisateur']; ?></p>
            <p><strong>Adresse mail:</strong> <?php echo $data['email_utilisateur'] ?></p>
            <p><strong>Numéro de téléphone:</strong> <?php echo $data['numero_de_telephone'] ?></p>
            <p><strong>Date de naissance:</strong> <?php echo $data['date_de_naissance'] ?></p>
        </div>

        <div class="change-password">
            <h2>Modifier votre mot de passe</h2>
            <?php
            if (isset($updateMessage)) {
                echo '<p style="color: green; text-align: center;">' . $updateMessage . '</p>';
            }
            ?>
            <form action="" method="post">
                <label for="ancien_mot_de_passe">Ancien mot de passe :</label>
                <input type="password" id="old_pw" name="ancien_mot_de_passe"  required><br>
                <?php
                if (isset($errorMessage) && $errorMessage === 'Le mot de passe est incorrect.') {
                    echo '<p style="color: red;">' . $errorMessage . '</p>';
                }
                ?>
                <label for="nouveau_mot_de_passe">Nouveau mot de passe :</label>
                <input type="password" id="new_pw" name="nouveau_mot_de_passe" required><br>

                <label for="confirmation_mot_de_passe">Confirmez le nouveau mot de passe :</label>
                <input type="password" id="conf_new_pw" name="confirmation_mot_de_passe" required><br>
                <?php
                if (isset($errorMessage) && $errorMessage === 'Le nouveau mot de passe et la confirmation ne correspondent pas.') {
                    echo '<p style="color: red;">' . $errorMessage . '</p>';
                }
                ?>

                <input type="submit" value="Modifier le mot de passe">
            </form>
        </div>
        <h2>Vos oeuvres favorites</h2>
        <div class="artwork-container-profile"> 
            <?php foreach ($results as $row) { ?>
                <div class="result-item-profile profile">
                    <h2><?php afficherValeurOuDefaut($row['titre'], 'Titre'); ?></h2>
                    <?php echo "<img class='search-result-image' src='" . $row['image'] . "' alt='" . $row['titre'] . "' />"; ?>
                    <p>Artiste: <?php afficherValeurOuDefaut($row['nom_artiste'], 'Artiste'); ?></p>
                    <p>Année de fin: <?php afficherValeurOuDefaut($row['date_fin'], 'Année de fin'); ?></p>
                    <p>Dimensions: <?php afficherValeurOuDefaut($row['taille'], 'Dimensions'); ?></p>

                    <p class="culture hidden">Culture: <?php afficherValeurOuDefaut($row['culture'], 'Culture'); ?></p>
                    <p class="bio-artiste hidden">Bio de l'artiste: <?php afficherValeurOuDefaut($row['bio_artiste'], 'Bio de l\'artiste'); ?></p>
                    <p class="pays hidden">Pays: <?php afficherValeurOuDefaut($row['pays'], 'Pays'); ?></p>
                    <p class="classification hidden">Classification: <?php afficherValeurOuDefaut($row['classification'], 'Classification'); ?></p>

                    <button class="voir-plus">Voir plus</button>
                    <button class="voir-moins hidden">Voir moins</button>
                    <br>

                    <?php
                    if (isset($_SESSION['loggedUser'])) {
                        $idOeuvre = $row['id_oeuvre'];
                        $dejalike = check_like_status($conn, $idUtilisateur, $idOeuvre);
                        if ($dejalike) {
                            echo '<form class="like-form" method="POST" action="unlike.php">
                                <input type="hidden" name="objectID" value="' . $idOeuvre . '">
                                <input type="submit" name="unlike" value="UNLIKE">
                            </form>';
                        } else {
                            echo '<form class="like-form" method="POST" action="insertion_oeuvre.php">
                                <input type="hidden" name="objectID" value="' . $idOeuvre . '">
                                <input type="hidden" name="image" value="' . $row['image'] . '">
                                <input type="hidden" name="title" value="' . $row['titre'] . '">
                                <input type="hidden" name="culture" value="' . $row['culture'] . '">
                                <input type="hidden" name="artistDisplayName" value="' . $row['nom_artiste'] . '">
                                <input type="hidden" name="artistDisplayBio" value="' . $row['bio_artiste'] . '">
                                <input type="hidden" name="artistNationality" value="' . $row['nationalite_artiste'] . '">
                                <input type="hidden" name="objectEndDate" value="' . $row['date_fin'] . '">
                                <input type="hidden" name="dimensions" value="' . $row['taille'] . '">
                                <input type="hidden" name="country" value="' . $row['pays'] . '">
                                <input type="hidden" name="classification" value="' . $row['classification'] . '">
                                <input type="submit" value="LIKE">
                            </form>';
                        }
                    } else {
                        echo "<p>Connectez-vous pour liker cette oeuvre. <a href='login.php'>Se connecter</a></p>";
                    }
                    ?>
                </div>
            <?php } ?>
        </div>
    <?php } else {
        echo "Veuillez vous connecter pour voir vos œuvres. <br>";
        echo '<a href="login.php">Se connecter</a> | <a href="inscription.php">Créer un compte</a>';
    }
    ?>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var voirPlusButtons = document.querySelectorAll('.voir-plus');
            var voirMoinsButtons = document.querySelectorAll('.voir-moins');

            voirPlusButtons.forEach(function(button, index) {
                button.addEventListener('click', function() {
                    var parent = this.parentNode;
                    var hiddenElements = parent.querySelectorAll('.hidden');
                    hiddenElements.forEach(function(element) {
                        element.style.display = 'block';
                    });
                    button.style.display = 'none';
                    voirMoinsButtons[index].style.display = 'inline-block';
                });
            });

            voirMoinsButtons.forEach(function(button, index) {
                button.addEventListener('click', function() {
                    var parent = this.parentNode;
                    var hiddenElements = parent.querySelectorAll('.hidden');
                    hiddenElements.forEach(function(element) {
                        element.style.display = 'none';
                    });
                    button.style.display = 'none';
                    voirPlusButtons[index].style.display = 'inline-block';
                });
            });


            document.getElementById('passwordForm').addEventListener('submit', function(event) {
                event.preventDefault();
                var oldPassword = document.getElementById('old_pw').value;

                if (oldPassword !== 'votreAncienMotDePasse') {
                    document.getElementById('errorMessage').textContent = 'Le mot de passe est incorrect.';
                } else {
                    document.getElementById('errorMessage').textContent = '';
                }
            });
        });
    </script>
</body>

<footer>
        <?php include 'footer.php'; ?>
    </footer>
    
</html>
