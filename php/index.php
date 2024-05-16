<?php

if (!empty($_SERVER['HTTPS'])) {
    header("Strict-Transport-Security: max-age=31536000");
}
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
session_start();

require_once(__DIR__ . 'mysql.php');
require_once(__DIR__ . 'databaseconnect.php');
require_once(__DIR__ . 'functions.php');


$_SESSION['origine'] = $_SERVER['REQUEST_URI'];

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Bienvenue sur Platos Disciple</title>

</head>

<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <h3>Découvrez Platos Disciple, explorez des œuvres d’art uniques.</h3>
    <div class="container">
        <div class="image3">
        <img src="platon-status.webp" alt="Platon thinking" usemap="#lien-map" />
    <map name="lien-map">
        <area shape="rect" coords="100,0,300,100" href="egg1.php" alt="Lien vers la page secrète" />
    </map>
        </div>
        <div class="text">
            <h4>Bienvenue sur <strong>Platos Disciple</strong>, l'endroit idéal pour explorer l'art. Découvrez les œuvres favorites de notre communauté dès la page d'accueil et laissez-vous guider par notre présentation intuitive.</h4>

            <h4>Rejoignez-nous en vous <a href="inscription.php">inscrivant</a> ou <a href="login.php">connectez-vous</a> pour une expérience complète, ou naviguez librement à travers les merveilles artistiques que nous avons à offrir.</h4>

            <h4>Exprimez vos coups de cœur artistiques en <a href="recherche_oeuvre.php">"likant"</a> les œuvres et retrouvez-les dans votre <a href="profile.php">espace personnel</a>. Chez <strong>Platos Disciple</strong>, chaque visite est une nouvelle découverte qui vous attend.</h4>

            <h4>Nous sommes là pour enrichir votre passion pour l'art. Laissez-vous inspirer et transformez votre regard sur l'art avec nous. </h4>
        </div>
        <div class="image1">
            <img src="platon-disciple.jpg" alt="Platon and his disciple">
        </div>
    </div>

</body>

</html>
<br>
<h3>Les oeuvres les plus tendances du moment </h3>
<div class="row artwork-container">
    <?php
    $results = show_most_liked($conn);
    $counter = 0;
    foreach ($results as $row) {
        if ($counter < 3) {
    ?>
            <div class="result-item-a">
                <h2><?php afficherValeurOuDefaut($row['titre'], 'Titre'); ?></h2>
                <?php echo "<img class='search-result-image' src='" . $row['image'] . "' alt='" . $row['titre'] . "' />"; ?>
                <p>Artiste: <?php afficherValeurOuDefaut($row['nom_artiste'], 'Artiste'); ?></p>
                <p>Année de fin: <?php afficherValeurOuDefaut($row['date_fin'], 'Année de fin'); ?></p>
                <p>Dimensions: <?php afficherValeurOuDefaut($row['taille'], 'Dimensions'); ?></p>
                <p class="see-more">Voir plus</p>
                <div class="hidden">
                    <p>Pays: <?php afficherValeurOuDefaut($row['pays'], 'Pays'); ?></p>
                    <p>Bio de l'artiste: <?php afficherValeurOuDefaut($row['bio_artiste'], 'Bio de l\'artiste'); ?></p>
                    <p>Culture: <?php afficherValeurOuDefaut($row['culture'], 'Culture'); ?></p>
                    <p>Classification: <?php afficherValeurOuDefaut($row['classification'], 'Classification'); ?></p>
                </div>
                <?php
                if (isset($_SESSION['loggedUser'])) {
                    $idUtilisateur = $_SESSION['user_id'];
                    $idOeuvre = $row['id_oeuvre'];
                    $dejalike = check_like_status($conn, $idUtilisateur, $idOeuvre);
                ?>
                    <div class="button-container">
                        <?php
                        if ($dejalike) {
                            echo '<form method="POST" action="unlike.php">
                                    <input type="hidden" name="objectID" value="' . $idOeuvre . '">
                                    <input type="submit" name="unlike" value="UNLIKE">
                                </form>';
                        } else {
                            echo '<form method="POST" action="insertion_oeuvre.php">
                                    <input type="hidden" name="objectID" value="' . $idOeuvre . '">
                                    <input type="hidden" name="image" value="' . $row['image'] . '">
                                    <input type="hidden" name="title" value="' . $row['titre'] . '">
                                    <input type="hidden" name="culture" value="' . $row['culture'] . '">
                                    <input type="hidden" name="artistDisplayName" value="' . $row['nom_artiste'] . '">
                                    <input type="hidden" name="artistDisplayBio" value="' . $row['bio_artiste'] . '">
                                    <input type="hidden" name="artistNationality" value="' . $row['nationalite_artiste'] . '">
                                    <input type="hidden" name="objectEndDate" value="' . $row['date_fin'] . '">
                                    <input type="hidden" name="dimensions" value="' . $row['taille'] . '">
                                    <input type="hidden" name="country" value="' . $row['pays'] . '">
                                    <input type="hidden" name="classification" value="' . $row['classification'] . '">
                                    <input type="submit" value="LIKE">
                                </form>';
                        }
                        ?>
                    </div>
                <?php
                } else {
                    echo "<p>Connectez-vous pour liker cette oeuvre. <a href='login.php'>Se connecter</a></p>";
                }
                ?>
            </div>
    <?php
            $counter++;
        }
    }
    ?>
</div>
<script>
    const seeMoreButtons = document.querySelectorAll('.see-more');
    seeMoreButtons.forEach(button => {
        button.addEventListener('click', () => {
            const hiddenData = button.nextElementSibling;
            hiddenData.classList.toggle('hidden');
            button.textContent = hiddenData.classList.contains('hidden') ? 'Voir plus' : 'Voir moins';
        });
    });
</script>
<h3>Découvrez l'Art à Travers les Âges avec Notre Moteur de Recherche Innovant</h3>
<br>
<div class="container">
    <div class="image2">
        <img src="museum-exterieur.jpg" alt="MetMuseum Facade">
    </div>
    <div class="text">
        <h4>Grâce à l’intégration du prestigieux <a href="https://www.metmuseum.org/fr">Metropolitan Museum of Art</a>, nous vous invitons à un voyage culturel exceptionnel.</h4>

        <h4>Recherchez par <a href="recherche_oeuvre.php">Artiste</a> : Entrez le nom d’un artiste, tel que le maître impressionniste Monet, et plongez dans son univers de couleurs et de lumière.</h4>

        <h4>Recherchez par <a href="recherche_oeuvre.php">Année</a> : Curieux de savoir ce qui a été créé en 1800 ? Entrez l’année et découvrez les trésors artistiques qui ont marqué ce tournant du siècle.</h4>

        <h4>Recherchez par <a href="recherche_oeuvre.php">Pays</a> : Explorez les œuvres d’art par pays et plongez dans l’âme créative de nations telles que la France, berceau de nombreux mouvements artistiques révolutionnaires.</h4>

        <h4>Alors, qu’attendez-vous ? Faites votre première <a href="recherche_oeuvre.php">recherche</a>.</h4>
    </div>
    <div class="image4">
        <img src="museum-interieur.jpg" alt="interieur MetMuseum">
    </div>
</div>
</body>

<footer>
    <?php include 'footer.php'; ?>
</footer>

</html>

