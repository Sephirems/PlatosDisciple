<?php
if (!empty($_SERVER['HTTPS'])) {
    header("Strict-Transport-Security: max-age=31536000");
}
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
session_start();

require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/config/databaseconnect.php');
require_once(__DIR__ . '/src/functions.php');

$_SESSION['origine'] = $_SERVER['REQUEST_URI'];

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
    <title>Bienvenue sur Platos Disciple</title>
</head>

<body>
    <header>
        <?php include 'header.php'; ?>
    </header>

    <?php 
    // Vérifie si l'utilisateur est connecté
    if(isset($_SESSION['user_id'])) {
        // L'utilisateur est connecté, affichez le contenu de la page utilisateur normalement
        $idUtilisateur = $_SESSION['user_id'];
        $userData = show_user_data($conn, $idUtilisateur);
        $results = show_user_likes($conn, $idUtilisateur);
        ?>

        <div class="user-data">
            <h2>Vos données</h2>
            <?php foreach($userData as $data) ?>
            <p>Nom d'utilisateur: <?php echo $data['nom_utilisateur']; ?></p>
            <p>Adresse mail: <?php echo $data['email_utilisateur'] ?></p>
            <p>Numéro de téléphone: <?php echo $data['numero_de_telephone'] ?></p>
            <p>Date de naissance: <?php echo $data['date_de_naissance'] ?></p>
        </div>

        <div class="artwork-container-profile">
            <h2>Vos oeuvres favorites</h2>
            <?php foreach($results as $row) { ?>
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
                            echo '<form class="like-form" method="POST" action="src/unlike.php">
                                <input type="hidden" name="objectID" value="' . $idOeuvre . '">
                                <input type="submit" name="unlike" value="UNLIKE">
                            </form>';
                        } else {
                            echo '<form class="like-form" method="POST" action="src/insertion_oeuvre.php">
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
        // L'utilisateur n'est pas connecté, affichez le message et les liens de connexion et d'inscription
        echo "Veuillez vous connecter pour voir vos œuvres. <br>";
        echo '<a href="login.php">Se connecter</a> | <a href="inscription.php">Créer un compte</a>';
    }
    ?>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>

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
        });
    </script>
</body>

</html>
