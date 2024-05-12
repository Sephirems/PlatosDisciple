<?php

if (!empty($_SERVER['HTTPS'])) {
    header("Strict-Transport-Security: max-age=31536000");
}

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);

session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/config/databaseconnect.php');
$error_message = ['username' => '', 'email' => '', 'phone' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$n = isset($_POST['nom_utilisateur']) ? htmlspecialchars($_POST['nom_utilisateur']) : null;
	$password = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : null;
	$p = $password ? password_hash($password, PASSWORD_DEFAULT) : null;
	$e = isset($_POST['email_utilisateur']) ? htmlspecialchars($_POST['email_utilisateur']) : null;
	$f = isset($_POST['numero_de_telephone']) ? htmlspecialchars($_POST['numero_de_telephone']) : null;
	$b = isset($_POST['date_de_naissance']) ? htmlspecialchars($_POST['date_de_naissance']) : null;
	$i = date("y-m-d");

	if (!$n || !$p || !$e || !$f || !$b) {
		$error_message['username'] = 'Tous les champs sont requis.';
		$error_message['email'] = 'Tous les champs sont requis.';
		$error_message['phone'] = 'Tous les champs sont requis.';
	} else {
		$sql = 'SELECT * FROM Utilisateur WHERE nom_utilisateur = :un';
		$statement = $conn->prepare($sql);
		$statement->execute([':un' => $n]);
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		if ($result) {
			$error_message['username'] = 'Le nom d\'utilisateur que vous avez entré existe déjà.';
		}

		$sql = 'SELECT * FROM Utilisateur WHERE email_utilisateur = :ue';
		$statement = $conn->prepare($sql);
		$statement->execute([':ue' => $e]);
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		if ($result) {
			$error_message['email'] = 'L\'email que vous avez entré existe déjà.';
		}

		$sql = 'SELECT * FROM Utilisateur WHERE numero_de_telephone = :uf';
		$statement = $conn->prepare($sql);
		$statement->execute([':uf' => $f]);
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		if ($result) {
			$error_message['phone'] = 'Le numéro de téléphone que vous avez entré existe déjà.';
		}

		if ($error_message['username'] === '' && $error_message['email'] === '' && $error_message['phone'] === '') {
			$sql = 'INSERT INTO Utilisateur (nom_utilisateur, mot_de_passe, email_utilisateur, numero_de_telephone, date_de_naissance, date_inscription) VALUES (:un, :up, :ue, :uf, :ub, :ui)';
			$statement = $conn->prepare($sql);

			try {
				$statement->execute([
					':un' => $n,
					':up' => $p,
					':ue' => $e,
					':uf' => $f,
					':ub' => $b,
					':ui' => $i
				]);
				header('Location: index.php?message=OK');
			} catch (PDOException $e) {
				$error_message['username'] = 'Erreur lors de l\'insertion des données : ' . $e->getMessage();
				$error_message['email'] = 'Erreur lors de l\'insertion des données : ' . $e->getMessage();
				$error_message['phone'] = 'Erreur lors de l\'insertion des données : ' . $e->getMessage();
			}
		}
	}
}
?>
<!DOCTYPE html>
<meta charset="utf-8">
<link rel="stylesheet" href="../css/style.css">
<html>

<head>
	<title>Inscription</title>
</head>

<body>
	<header>
		<h1>Inscription</h1>
		<a id="accueil" href="index.php">Accueil</a>
        <button class="bouton-connexion" onclick="window.location.href='login.php'">Se connecter</button>
	</header>
	<form action="" method="post">
		<label for="un">Nom d'utilisateur</label>
		<input type="text" id="un" name="nom_utilisateur" placeholder="Nom d'utilisateur" required><br>
		<span id="username-error" style="color: red;"><?php echo $error_message['username']; ?></span><br>
		<label for="pw">Mot de passe</label>
		<input type="password" id="pw" name="mot_de_passe" placeholder="Mot de passe" required><br>
		<label for="em">Adresse mail</label>
		<input type="email" id="em" name="email_utilisateur" placeholder="nom@domaine.com" required><br>
		<span id="email-error" style="color: red;"><?php echo $error_message['email']; ?></span><br>
		<label for="pn">Numéro de téléphone</label>
		<input type="tel" id="pn" name="numero_de_telephone" pattern="+32 4[0-9]{2}-[0-9]{3}-[0-9]{3}" placeholder="+32 4XX XXX XXX" required><br>
		<span id="phone-error" style="color: red;"><?php echo $error_message['phone']; ?></span><br>
		<label for="bd">Date de naissance</label>
		<input type="date" id="bd" name="date_de_naissance" required><br>
		<button type="submit">S'inscrire</button>
		<div class="inscription">
			<p>Déjà inscrit ? <a href="login.php">Connectez-vous ici.</a></p>
		</div>
	</form>
</body>

</html>
