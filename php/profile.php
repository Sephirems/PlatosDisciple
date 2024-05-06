<?php
if (!empty($_SERVER['HTTPS'])) {
    header("Strict-Transport-Security: max-age=31536000");
}
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
session_start();

require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/config/databaseconnect.php');
require_once(__DIR__ . '/src/show_user_likes.php');
require_once(__DIR__ . '/src/check_like_status.php');
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
        <h1>Bienvenue sur Platos Disciple</h1>
        <a href="recherche_oeuvre.php">Recherche</a>
        <?php if (isset($_SESSION['loggedUser'])) : ?>
            <p><?php echo $_SESSION['nom_utilisateur']; ?></p>
            <a class="nav-link" href="src/logout.php">Déconnexion</a>
        <?php else : ?>
            <div class="lien">
                <p><a href="inscription.php">Inscription</a></p>
                <p><a href="login.php">Connexion</a></p>
            </div>
        <?php endif; ?>
    </header>

    <?php 
    $idUtilisateur = $_SESSION['user_id'];
    $results = show_user_likes($conn, $idUtilisateur);
    foreach($results as $row) { ?>
    <div class="result-item">
                                <h2><?php afficherValeurOuDefaut($row['titre'], 'Titre'); ?></h2>
                                <?php echo "<img class='search-result-image' src='" . $row['image'] . "' alt='" . $row['titre'] . "' />"; ?>
                                <p>Culture: <?php afficherValeurOuDefaut($row['culture'], 'Culture'); ?></p>
                                <p>Artiste: <?php afficherValeurOuDefaut($row['nom_artiste'], 'Artiste'); ?></p>
                                <p>Bio de l'artiste: <?php afficherValeurOuDefaut($row['bio_artiste'], 'Bio de l\'artiste'); ?></p>
                                <p>Année de fin: <?php afficherValeurOuDefaut($row['date_fin'], 'Année de fin'); ?></p>
                                <p>Dimensions: <?php afficherValeurOuDefaut($row['taille'], 'Dimensions'); ?></p>
                                <p>Pays: <?php afficherValeurOuDefaut($row['pays'], 'Pays'); ?></p>
                                <p>Classification: <?php afficherValeurOuDefaut($row['classification'], 'Classification'); ?></p>

                                <?php
                                if (isset($_SESSION['loggedUser'])) {
                                    $idOeuvre = $row['id_oeuvre'];
                                    $dejalike = check_like_status($conn, $idUtilisateur, $idOeuvre);
                                    if ($dejalike) {
                                        echo '<form method="POST" action="src/unlike.php">
                                            <input type="hidden" name="objectID" value="' . $idOeuvre . '">
                                            <input type="submit" name="unlike" value="UNLIKE">
                                        </form>';
                                    } else {
                                        echo '<form method="POST" action="src/insertion_oeuvre.php">
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
                            }
                                ?>
                            </div>
                            </div>
                            </div>
</body>

</html>
<footer>
<?php include 'footer.php'; ?>
</footer>