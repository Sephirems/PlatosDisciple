<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Footer Exemple</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<footer class="footer">
  <div class="footer-content">
    <div>Projet de fin d'année d'EBusiness bloc 2</div>
    <div class="copyright">
       <a href="https://www.hepl.be">© hepl</a>
    </div>
  </div>
  <div class="menu">
    <a href="index.php">Accueil</a>
    <a href="recherche_oeuvre.php">Recherche</a>
    <?php
    if (isset($_SESSION['loggedUser'])) {
        echo "<a href='profile.php'>Utilisateur</a>";
    }
    ?>
  </div>
  <br>
  <div class="bottom">
    © Platos-Disciple
  </div>
</footer>
</body>
</html>
