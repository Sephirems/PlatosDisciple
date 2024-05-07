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
    <title>Bienvenue sur Platos Disciple</title>
    <style>
        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin-bottom: -10px;
            position: relative;
        }

        .menu-header {
            display: flex;
            gap: 20px;
            font-size: 30px;
        }

        .connexion {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .connexion p,
        .connexion a,
        .lien a {
            color: #fff;
            font-size: 25px;
        }

        .lien {
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 25px;
            top: 0px;
        }

        .menu-link {
            color: white;
            cursor: pointer;
        }

        .menu-button-container {
            text-align: center;
            margin-bottom: 10px;
        }

        .menu-button {
            background-color: white;
            color: black;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }

        .menu-button:hover {
            background-color: #f1f1f1;
        }

        .logo {
            position: absolute;
            left: 50px;
            top: 40px;
        }

        .logo img {
            height: 100px;
            width: 100px;
        }
    </style>
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
