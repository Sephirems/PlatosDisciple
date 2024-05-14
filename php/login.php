<?php

if (!empty($_SERVER['HTTPS'])) {
    header("Strict-Transport-Security: max-age=31536000");
}

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);

session_start();
require_once 'mysql.php';
require_once 'databaseconnect.php';
$error_message = ['email' => '', 'password' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email = $_POST['email'] ?? null;
	$password = $_POST['password'] ?? null;

	if (!$email || !$password) {
		$error_message['email'] = 'Tous les champs sont requis.';
		$error_message['password'] = 'Tous les champs sont requis.';
	} else {
		$sql = 'SELECT * FROM Utilisateur WHERE email_utilisateur = :email';
		$statement = $conn->prepare($sql);
		$statement->execute([':email' => $email]);
		$user = $statement->fetch(PDO::FETCH_ASSOC);

		if ($user) {
			if (password_verify($password, $user['mot_de_passe'])) {
				$_SESSION['loggedUser'] = true;
				$_SESSION['email'] = $user['email_utilisateur'];
				$_SESSION['nom_utilisateur'] = $user['nom_utilisateur'];
				$_SESSION['user_id'] = $user['id_utilisateur'];
				header('Location:index.php');
				exit;
			} else {
				$error_message['password'] = 'Mot de passe incorrect.';
			}
		} else {
			$error_message['email'] = 'Email non trouvé.';
		}
	}
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Connexion</title>
</head>

<body>
    <header>
        <h1>Connexion</h1>
        <span id="accueil" onclick="redirectToIndex()">Accueil</span>
    </header>
    <?php if (isset($_SESSION['loggedUser'])) : ?>
        <p>Vous êtes déjà connecté</p>
    <?php else : ?>
        <form action="" method="post">
            <input type="email" id="un" name="email" placeholder="Adresse e-mail" required><br>
            <span style="color: red;"><?php echo $error_message['email']; ?></span><br>
            <input type="password" id="pw" name="password" placeholder="Mot de passe" required><br>
            <span style="color: red;"><?php echo $error_message['password']; ?></span><br>
            <label for="c1">
                <span>Rester connecté</span>
                <input type="checkbox" id="c1" name="c" value="OK">
                <p>Pas encore inscrit ? <a href="inscription.php">Inscrivez-vous</a></p>
            </label>
            <button type="submit">Connexion</button>
            <div class="connexion">
                <button type="button" class="bouton-inscription" onclick="window.location.href='inscription.php'">S'inscrire</button>
            </div>
        </form>
    <?php endif; ?>

    <script>
        function redirectToIndex() {
            window.location.href = 'index.php';
        }
    </script>
</body>

</html>

