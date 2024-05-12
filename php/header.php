<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
    <title>Bienvenue sur Platos Disciple</title>
</head>

<body class="body-header">
    <div class="logo">
        <a href="index.php">
            <img src="https://media.discordapp.net/attachments/876511185464328314/1239186975571836998/3429208b-f579-4e2c-94b8-a8ac08d523f7-removebg.png?ex=6642024d&is=6640b0cd&hm=60f2be3fe9ce91b9192fb10a3ac195760231830e0294c2532c0089e9e00d5232&=&format=webp&quality=lossless" alt="Logo Platos Disciple">
        </a>
    </div>

    <h5 style="text-align: center;">Bienvenue sur Platos Disciple</h5>
    <div class="header">
        <div class="menu-header">
            <span class="menu-link" onclick="window.location.href='index.php'">Accueil</span>
            <span class="menu-link" onclick="window.location.href='recherche_oeuvre.php'">Recherche</span>
            <?php
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
