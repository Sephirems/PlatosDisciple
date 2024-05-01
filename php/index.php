<?php
if (!empty($_SERVER['HTTPS'])) {
    header("Strict-Transport-Security: max-age=31536000");
}
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
session_start();
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
</body>

</html>
