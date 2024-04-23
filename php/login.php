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
				<p>Pas encore inscrit ?  <a href="inscription.php">Inscrivez-vous ici.</a></p>
			</div>
		</form>
	</body>
</html>

<?php
session_start();

try{
if(isset($_POST['email']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $password =$_POST['password'];

    $db = new PDO('mysql:host=localhost;dbname=eiipopolcl55',
    'is21di91plls',
    'd6ta5i:7le');

    $query = $db->prepare('SELECT * FROM Utilisateur where email_utilisateur = :email');
    $query->execute(['email' => $email]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user['mot_de_passe'])){
        header('Location: /index.html');
        echo 'Connexion réussie';
        exit;
    } else {
        echo 'Identifiants incorrects. Veuillez réessayer.';
    }
}
} catch(PDOException $e) {
    echo 'Erreur de base de données : ' . $e->getMessage();
} catch(Exception $e) {
    echo 'Une erreur est survenue : ' . $e->getMessage();
}
?>
