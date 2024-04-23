<?php

if (!empty($_SERVER['HTTPS'])) {
    header("Strict-Transport-Security: max-age=31536000");
}

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);

session_start();

try {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $db_host = 'localhost';
        $db_name = 'eiipopolcl55';
        $db_user = 'is21di91plls';
        $db_pass = 'd6ta5i:7le';

  
        $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);

        $query = $db->prepare('SELECT * FROM Utilisateur WHERE email_utilisateur = :email');
        $query->execute(['email' => $email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            header('Location: /index.html');
            exit;
        } else {
            echo 'Les informations d\'identification sont incorrectes. Veuillez réessayer.';
        }
    }
} catch (PDOException $e) {
    echo 'Erreur de base de données : ' . $e->getMessage();
} catch (Exception $e) {
    echo 'Une erreur est survenue : ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Connexion</h1>
    </header>
    <form action="" method="post">
        <input type="email" id="un" name="email" placeholder="Adresse e-mail" required><br>
        <input type="password" id="pw" name="password" placeholder="mot de passe" required><br>
        <label for="c1">
            <span>Rester connecté</span>
            <input type="checkbox" id="c1" name="c" value="OK">
        </label>
        <button type="submit">Connexion</button>
        <div class="connexion">
            <p>Pas encore inscrit ? <a href="inscription.php">Inscrivez-vous ici.</a></p>
        </div>
    </form>
</body>
</html>
