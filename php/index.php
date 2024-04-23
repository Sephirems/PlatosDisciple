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
    </header>
    <div class="lien">
        <p><a href="inscription.php">Inscription</a></p>
        <p><a href="login.php">Connexion</a></p>
    </div>
</body>
</html>
