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

        $db = new PDO(
            'mysql:host=localhost;dbname=eiipopolcl55',
            'is21di91plls',
            'd6ta5i:7le'
        );

        $query = $db->prepare('SELECT * FROM Utilisateur where email_utilisateur = :email');
        $query->execute(['email' => $email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Utilisation d'un chemin absolu pour la redirection
            $_SESSION['loggedUser'] = true;
            $_SESSION['email'] = $user['email_utilisateur'];
            $_SESSION['nom_utilisateur'] = $user['nom_utilisateur'];
            $_SESSION['user_id'] = $user['id_utilisateur'];
            header('Location:index.php');
            exit;
        } else {
            $message = 'Identifiants incorrects. Veuillez réessayer.';
        }
    }
} catch (PDOException $e) {
    $message = 'Erreur de base de données : ' . $e->getMessage();
} catch (Exception $e) {
    $message = 'Une erreur est survenue : ' . $e->getMessage();
}

// HTML et sortie après la gestion des exceptions et la redirection
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
    <title>Connexion</title>
</head>

<body>
    <header>
        <h1>Connexion</h1>
        <a href="index.php">Accueil</a>
    </header>
    <?php if (isset($_SESSION['loggedUser'])) : ?>
        <p>Vous êtes déjà connecter</p>
    <?php else : ?>
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
    <?php endif; ?>
    <?php if (isset($message)) echo $message; ?>
</body>

</html>
