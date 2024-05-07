<?php

if (!empty($_SERVER['HTTPS'])) {
	header("Strict-Transport-Security: max-age=31536000");
}

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);

session_start();
?>
<!DOCTYPE html>
<meta charset="utf-8">
<link rel="stylesheet" href="../css/style.css">
<html>

<head>
	<title>Inscription</title>
    <style>
        a#accueil {
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body>
	<header>
		<h1>Inscription</h1>
		<a id="accueil" href="index.php">Accueil</a>
	</header>
	<form action="" method="post">
		<label for="un">Nom d'utilisateur</label>
		<input type="text" id="un" name="nom_utilisateur" placeholder="Nom d'utilisateur" required><br>
		<label for="pw">Mot de passe</label>
		<input type="password" id="pw" name="mot_de_passe" placeholder="Mot de passe" required><br>
		<label for="em">Adresse mail</label>
		<input type="email" id="em" name="email_utilisateur" placeholder="nom@domaine.com" required><br>
		<label for="pn">Numéro de téléphone</label>
		<input type="tel" id="pn" name="numero_de_telephone" pattern="+32 4[0-9]{2}-[0-9]{3}-[0-9]{3}" placeholder="+32 4XX XXX XXX" required><br>
		<label for="bd">Date de naissance</label>
		<input type="date" id="bd" name="date_de_naissance" required><br>
		<button type="submit">S'inscrire</button>
		<div class="inscription">
			<p>Déjà inscrit ? <a href="login.php">Connectez-vous ici.</a></p>
		</div>
	</form>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$n = isset($_POST['nom_utilisateur']) ? htmlspecialchars($_POST['nom_utilisateur']) : null;
	$password = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : null;
	$p = $password ? password_hash($password, PASSWORD_DEFAULT) : null;
	$e = isset($_POST['email_utilisateur']) ? htmlspecialchars($_POST['email_utilisateur']) : null;
	$f = isset($_POST['numero_de_telephone']) ? htmlspecialchars($_POST['numero_de_telephone']) : null;
	$b = isset($_POST['date_de_naissance']) ? htmlspecialchars($_POST['date_de_naissance']) : null;
	$i = date("y-m-d");

	if (!$n || !$p || !$e || !$f || !$b) {
		echo 'Tous les champs sont requis.';
		exit;
	}

	try {
		$conn = new PDO(
			'mysql:host=localhost;dbname=eiipopolcl55',
			'is21di91plls',
			'd6ta5i:7le'
		);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
		exit;
	}

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
		echo 'Erreur lors de l\'insertion des données : ' . $e->getMessage();
	}
}
?>
