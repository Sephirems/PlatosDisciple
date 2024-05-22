<!DOCTYPE html>
<html>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Texturina:ital,opsz,wght@0,12..72,100..900;1,12..72,100..900&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
    <title>Bienvenue sur Platos Disciple</title>
</head>
<!--
<body class="body-header">
    <div class="logo">
        <a href="index.php">
            <img src="PLATOS/images/logo.webp" alt="Logo Platos Disciple">
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
    -->
    <body class="body-header">
    <div class="nav-container">
        <nav>
            <div class="logo">
                <a href="index.php">
                    <img src="images/logoplatos.png" alt="Logo Platos Disciple">
                </a>
            </div>

            <div class="menu-header">
                <span class="index" onclick="window.location.href='index.php'">Accueil</span>
                <span class="search" onclick="window.location.href='recherche_oeuvre.php'">Recherche</span>
                <?php
                if (isset($_SESSION['loggedUser'])) {
                    echo "<span class='user' onclick=\"window.location.href='profile.php'\">Utilisateur</span>";
                }
                ?>
            </div>

        <div class="connexion">
                <?php
                if (isset($_SESSION['loggedUser'])) {
                    echo "<div class='menu-button-container'>";
                    echo "<span class='menu-link' onclick=\"window.location.href='profile.php'\"><img src='images/greek.png' alt='Utilisateur' class='user-icon'" . $_SESSION['nom_utilisateur'] . "</span>";
                    echo "</div>";
                    echo "<div class='menu-button-container'>";
                    echo "<span class='menu-link' onclick=\"window.location.href='src/logout.php'\"><img src='images/logout.png' alt='Déconnexion' class='logout-icon'></span>";
                    echo "</div>";
                } else {
                    echo "<div class='menu-button-container'>";
                    echo "<span class='menu-link' onclick=\"window.location.href='login.php'\"><img src='images/login.png' alt='Connexion' class='login-icon'></span>";
                    echo "</div>";
                    echo "<div class='menu-button-container'>";
                    echo "<span class='menu-link' onclick=\"window.location.href='inscription.php'\"><img src='images/signup.png' alt='Inscription' class='signin-icon'></span>";
                    echo "</div>";
                }
                ?>
        </div>
        </nav>
    </div>

    <h1>Platos Disciple</h1>

    
</body>
</body>

</html>
