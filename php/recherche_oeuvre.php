<?php

if (!empty($_SERVER['HTTPS'])) {
    header("Strict-Transport-Security: max-age=31536000");
}
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
session_start();

$_SESSION['origine'] = $_SERVER['REQUEST_URI'];

require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/config/databaseconnect.php');
require_once('src/functions.php');

$showNextButton = '';
$showPrevButton = '';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
    <title>Bienvenue sur Platos Disciple</title>
    <style>
        .additional-data {
            display: none; /* Cacher les données supplémentaires par défaut */
        }
    </style>
</head>

<body>
    <header>
    <?php include 'header.php'; ?>
    </header>

    <form id="search-form" action="" method="get" class="search-form <?php echo !empty($_GET['general_search']) ? 'small-search' : ''; ?>">
        <input type="text" name="general_search" placeholder="Recherche" value="<?php echo isset($_GET['general_search']) ? $_GET['general_search'] : ''; ?>">
        <input type="submit" value="Rechercher">
    </form>
    <?php
    if (!empty($_GET['general_search'])) {
        echo '<h3>Voici le résultat de vos recherches </h3>';
    }
    ?>
    <div class="results-container">
        <?php
        if (!empty($_GET['general_search'])) {
            $searchQuery = urlencode($_GET['general_search']);
            $url = "https://collectionapi.metmuseum.org/public/collection/v1/search?q=$searchQuery";
            $output = file_get_contents($url);
            if ($output !== FALSE) {
                $data = json_decode($output, true);
                if ($data['total'] > 0) {
                    $paging = 6;
                    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                    $pagingrequest = $paging * $currentPage;
                    $objectIDs = array_slice($data['objectIDs'], $pagingrequest - $paging, $paging);
                    foreach ($objectIDs as $objectID) {
                        $url = "https://collectionapi.metmuseum.org/public/collection/v1/objects/$objectID";
                        $output = @file_get_contents($url);
                        if ($output !== FALSE) {
                            $objectData = json_decode($output, true);
        ?>
                            <div class="result-item">
                                <h2><?php afficherValeurOuDefaut($objectData['title'], 'Titre'); ?></h2>
                                <?php
                                if ($objectData['isPublicDomain'] && $objectData['primaryImageSmall'] != null) {
                                    echo "<img class='search-result-image' src='" . $objectData['primaryImageSmall'] . "' alt='" . $objectData['title'] . "' />";
                                    $imageUrl = $objectData['primaryImageSmall'];
                                } else {
                                    $imageUrl = "https://collectionapi.metmuseum.org/api/collection/v1/iiif/" . $objectData['objectID'] . "/restricted";
                                    echo "<img class='search-result-image' src='" . $imageUrl . "' alt='" . $objectData['title'] . "'/>";
                                }
                                ?>
                                <p>Artiste: <?php afficherValeurOuDefaut($objectData['artistDisplayName'], 'Artiste'); ?></p>
                                <p>Année de fin: <?php afficherValeurOuDefaut($objectData['objectEndDate'], 'Année de fin'); ?></p>
                                <p>Dimensions: <?php afficherValeurOuDefaut($objectData['dimensions'], 'Dimensions'); ?></p>
                                <!-- Affichage des données supplémentaires -->
                                <div class="additional-data">
                                    <p>Culture: <?php afficherValeurOuDefaut($objectData['culture'], 'Culture'); ?></p>
                                    <p>Bio de l'artiste: <?php afficherValeurOuDefaut($objectData['artistDisplayBio'], 'Bio de l\'artiste'); ?></p>
                                    <p>Pays: <?php afficherValeurOuDefaut($objectData['country'], 'Pays'); ?></p>
                                    <p>Classification: <?php afficherValeurOuDefaut($objectData['classification'], 'Classification'); ?></p>
                                </div>
                                <!-- Bouton "Voir plus" pour afficher les données supplémentaires -->
                                <button onclick="toggleAdditionalData(this)">Voir plus</button>
                                <?php
                                if (isset($_SESSION['loggedUser'])) {
                                    $idUtilisateur = $_SESSION['user_id'];
                                    $idOeuvre = $objectData['objectID'];
                                    $dejalike = check_like_status($conn, $idUtilisateur, $idOeuvre);
                                    if ($dejalike) {
                                        echo '<form method="POST" action="src/unlike.php">
                                            <input type="hidden" name="objectID" value="' . $idOeuvre . '">
                                            <input type="submit" name="unlike" value="UNLIKE">
                                        </form>';
                                    } else {
                                        echo '<form method="POST" action="src/insertion_oeuvre.php">
                                        <input type="hidden" name="objectID" value="' . $idOeuvre . '">
                                        <input type="hidden" name="image" value="' . $imageUrl . '">
                                        <input type="hidden" name="title" value="' . $objectData['title'] . '">
                                        <input type="hidden" name="culture" value="' . $objectData['culture'] . '">
                                        <input type="hidden" name="artistDisplayName" value="' . $objectData['artistDisplayName'] . '">
                                        <input type="hidden" name="artistDisplayBio" value="' . $objectData['artistDisplayBio'] . '">
                                        <input type="hidden" name="artistNationality" value="' . $objectData['artistNationality'] . '">
                                        <input type="hidden" name="objectEndDate" value="' . $objectData['objectEndDate'] . '">
                                        <input type="hidden" name="dimensions" value="' . $objectData['dimensions'] . '">
                                        <input type="hidden" name="country" value="' . $objectData['country'] . '">
                                        <input type="hidden" name="classification" value="' . $objectData['classification'] . '">
                                        <input type="submit" value="LIKE">
                                    </form>';
                                    }
                                } else {
                                    echo "<p>Connectez-vous pour liker cette oeuvre. <a href='login.php'>Se connecter</a></p>";
                                }
                                ?>
                            </div>
        <?php
                        }
                    }
                    if ($currentPage > 1) {
                        $prevPage = $currentPage - 1;
                        $showPrevButton = "<a href='?page=$prevPage&general_search=" . $_GET['general_search'] . "' class='previous-page'>Page précédente</a>";
                    }
                    if ($data['total'] > $pagingrequest) {
                        $nextPage = $currentPage + 1;
                        $showNextButton = "<a href='?page=$nextPage&general_search=" . $_GET['general_search'] . "' class='next-page'>Page suivante</a>";
                    }
                } else {
                    echo "<p>Pas de résultat</p>";
                }
            } else {
                echo "<p>Erreur lors de la récupération des données.</p>";
            }
        }
        ?>
    </div>
    <?php echo $showPrevButton; ?>
    <div class="next-button-container">
        <?php echo $showNextButton; ?>
    </div>
    <script>
        function toggleAdditionalData(button) {
            var resultItem = button.closest('.result-item'); // Sélectionne le conteneur parent du bouton
            var additionalData = resultItem.querySelector('.additional-data'); // Sélectionne les données supplémentaires à l'intérieur du conteneur
            if (additionalData.style.display === "block") {
                additionalData.style.display = "none";
                button.textContent = "Voir plus";
            } else {
                additionalData.style.display = "block";
                button.textContent = "Voir moins";
            }
        }
    </script>
</body>

</html>

<?php include 'footer.php'; ?>