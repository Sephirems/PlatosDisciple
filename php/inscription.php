<?php
if (!empty($_SERVER['HTTPS'])) {
    header("Strict-Transport-Security: max-age=31536000");
}
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['nom_utilisateur'];
    $password = $_POST['mot_de_passe'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $email = $_POST['email_utilisateur'];
    $phone = $_POST['numero_de_telephone'];
    $birthdate = $_POST['date_de_naissance'];
    $registration_date = date("Y-m-d");

    try {
        $conn = new PDO(
            'mysql:host=localhost;dbname=eiipopolcl55',
            'is21di91plls',
            'd6ta5i:7le'
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérification de l'existence de l'utilisateur avant l'insertion
        $stmt = $conn->prepare('SELECT COUNT(*) FROM Utilisateur WHERE nom_utilisateur = :un');
        $stmt->execute([':un' => $user]);
        $user_exists = $stmt->fetchColumn();

        if ($user_exists) {
            $_SESSION['message'] = 'Ce nom d\'utilisateur est déjà pris. Veuillez en choisir un autre.';
        } else {
            $sql = 'INSERT INTO Utilisateur (nom_utilisateur, mot_de_passe, email_utilisateur, numero_de_telephone, date_de_naissance, date_inscription) VALUES (:un, :up, :ue, :uf, :ub, :ui)';
            $statement = $conn->prepare($sql);
            $statement->execute([
                ':un' => $user,
                ':up' => $hashed_password,
                ':ue' => $email,
                ':uf' => $phone,
                ':ub' => $birthdate,
                ':ui' => $registration_date
            ]);
            $_SESSION['message'] = 'Inscription réussie !';
            header("Location: index.html?message=OK");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = 'Erreur lors de l\'inscription. Veuillez réessayer plus tard.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
    <title>Bienvenue sur Platos Disciple</title>
</head>
<body>
    <h1>Inscription</h1>
    <form method="post" action="inscription.php">
        <input type="text" name="nom_utilisateur" placeholder="Nom d'utilisateur" required>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
        <input type="email" name="email_utilisateur" placeholder="nom@domaine.com" required>
        <input type="tel" name="numero_de_telephone" pattern="+32 4[0-9]{2}-[0-9]{3}-[0-9]{3}" placeholder="+32 4XX XXX XXX" required>
        <input type="date" name="date_de_naissance" required>
        <button type="submit">S'inscrire</button>
    </form>
    <div class="inscription">
        <p>Déjà inscrit ? <a href="login.php">Connectez-vous ici.</a></p>
    </div>
</body>
</html>
