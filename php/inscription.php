<!DOCTYPE html>
<meta charset="utf-8">
<link rel="stylesheet" href="../css/style.css">
<html>
	<header>
		<h1>Inscription</h1>
	</header>
	<body>
		<form action="" method="post">
			<label for="un, pw">Identifiants</label>
				<input type="text" id="un" name="nom_utilisateur" placeholder="Nom d'utilisateur" required><br>
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
$n = htmlspecialchars($_POST['nom_utilisateur']);
if (!isset($n))
{
	echo 'CODE manquant';
	exit;
}

$password = $_POST['mot_de_passe'];
$p = password_hash($password, PASSWORD_DEFAULT);
$e = htmlspecialchars($_POST['email_utilisateur']);
$f = htmlspecialchars($_POST['numero_de_telephone']);
$b = htmlspecialchars($_POST['date_de_naissance']);
$i = date("y-m-d");

try{
	$conn = new PDO(
		'mysql:host=localhost;dbname=eiipopolcl55',
		'is21di91plls',
		'd6ta5i:7le');

	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
} catch (PDOException $e) {
	echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
	exit;
}


$sql = 'INSERT INTO Utilisateur (nom_utilisateur, mot_de_passe, email_utilisateur, numero_de_telephone, date_de_naissance, date_inscription) VALUES (:un, :up, :ue, :uf, :ub, :ui)';
$statement = $conn->prepare($sql);

try{
	$statement->execute([
		':un' => $n,
		':up' => $p,
		':ue' => $e,
		':uf' => $f,
		':ub' => $b,
		':ui' => $i
		]
	);
	header('index.html?message=OK');
} catch (PDOException $e) {
	echo 'Erreur lors de l\'insertion des données : ' . $e->getMessage();
}
?>
