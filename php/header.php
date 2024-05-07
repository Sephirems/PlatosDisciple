<?php
if (!empty($_SERVER['HTTPS'])) {
    header("Strict-Transport-Security: max-age=31536000");
}
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
    <title>Bienvenue sur Platos Disciple</title>
</head>
<body class="body-header">
    <div class="logo">
        <img src="https://www.arthistoryproject.com/site/assets/files/17974/ancient-greece-head-of-plato-370-obelisk-art-history.png" alt="Logo Platos Disciple">
    </div>

    <h5 style="text-align: center;">Bienvenue sur Platos Disciple</h5>
    <div class="header">
        <div class="menu-header">
            <span class="menu-link" onclick="window.location.href='index.php'">Accueil</span>
            <span class="menu-link" onclick="window.location.href='recherche_oeuvre.php'">Recherche</span>
            <?php
            session_start();
            if (isset($_SESSION['loggedUser'])) {
                echo "<span class='menu-link' onclick=\"window.location.href='profile.php'\">Utilisateur</span>";
            }
            ?>
        </div>
    </div>

    <div class="connexion">
        <?php
        if (isset($_SESSION['loggedUser'])) {
            echo "<div class='menu-button-container'>";
            echo "<button class='menu-button' onclick=\"window.location.href='profile.php'\">" . $_SESSION['nom_utilisateur'] . "</button>";
            echo "</div>";
            echo "<div class='menu-button-container'>";
            echo "<button class='menu-button' onclick=\"window.location.href='src/logout.php'\">Déconnexion</button>";
            echo "</div>";
        } else {
            echo "<div class='menu-button-container'>";
            echo "<button class='menu-button' onclick=\"window.location.href='login.php'\">Connexion</button>";
            echo "</div>";
            echo "<div class='menu-button-container'>";
            echo "<button class='menu-button' onclick=\"window.location.href='inscription.php'\">Inscription</button>";
            echo "</div>";
        }
        ?>
    </div>

</body>
</html>
