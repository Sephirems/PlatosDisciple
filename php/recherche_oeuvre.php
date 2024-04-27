<?php
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    header("Strict-Transport-Security: max-age=31536000");
}
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
session_start();
$showMoreButton = ''; // Initialisation de la variable $showMoreButton
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/style.css">
        <title>Bienvenue sur Platos Disciple</title>
        <script>
        function hideSearchForm() {
            document.getElementById('search-form').style.display = 'none';
        }
        </script>
    </head>
    <body>
        <header>
            <h1>Bienvenue sur Platos Disciple</h1>
        </header>
        <form id="search-form" action="" method="get">
            <input type="text" name="general_search" placeholder="Recherche" value="<?php echo isset($_GET['general_search']) ? $_GET['general_search'] : ''; ?>">
            <input type="submit" value="Rechercher">
        </form>
        <?php
        if (!empty($_GET['general_search'])) {
            $searchQuery = urlencode($_GET['general_search']);
            echo "<script>hideSearchForm();</script>";
            $url = "https://collectionapi.metmuseum.org/public/collection/v1/search?q=$searchQuery";
            $output = file_get_contents($url);
            if ($output !== FALSE) {
                $data = json_decode($output, true);
                if ($data['total'] > 0) {
                    $paging = 8; // Nombre d'œuvres à afficher par page
                    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                    $pagingrequest = $paging * $currentPage;
                    $objectIDs = array_slice($data['objectIDs'], $pagingrequest - $paging, $paging);
                    foreach ($objectIDs as $objectID) {
                        $url = "https://collectionapi.metmuseum.org/public/collection/v1/objects/$objectID";
                        $output = @file_get_contents($url);
                        if ($output !== FALSE) {
                            $objectData = json_decode($output, true);
                            echo "<h2>" . $objectData['title'] . "</h2>";
                            echo "<p>ObjectID: " . $objectData['objectID'] . "</p>";
                            echo "<p>Year: " . $objectData['objectDate'] . "</p>";
                            echo "<p>Culture: " . $objectData['culture'] . "</p>";
                            echo "<p>Artist: " . $objectData['artistDisplayName'] . "</p>";
                            echo "<p>ArtistBio: " . $objectData['artistDisplayBio'] . "</p>";
                            echo "<p>ArtistNationality: " . $objectData['artistNationality'] . "</p>";
                            echo "<p>EndYear: " . $objectData['objectEndDate'] . "</p>";
                            echo "<p>Dimensions: " . $objectData['dimensions'] . "</p>";
                            echo "<p>Country: " . $objectData['country'] . "</p>";
                            echo "<p>Classification: " . $objectData['classification'] . "</p>";
                            if ($objectData['isPublicDomain'] && $objectData['primaryImageSmall'] != null) {
                                echo "<img class='image' height=50 src='" . $objectData['primaryImageSmall'] . "' alt='" . $objectData['title'] . "' />";
                            } else {
                                $restricted_url = "https://collectionapi.metmuseum.org/api/collection/v1/iiif/" . $objectData['objectID'] . "/restricted";
                                echo "<img height=50 src='" . $restricted_url . "' alt='" . $objectData['title'] . "'/>";
                            }
                        }
                    }
                    // Préparez le bouton "Voir plus" si nécessaire
                    if ($data['total'] > $pagingrequest) {
                        $nextPage = $currentPage + 1;
                        $showMoreButton = "<a href='?page=$nextPage&general_search=" . $_GET['general_search'] . "' class='voir-plus'>Voir plus</a>";
                    }
                } else {
                    echo "<p>Pas de résultat</p>";
                }
            } else {
                echo "<p>Erreur lors de la récupération des données.</p>";
            }
        }
        ?>
        <!-- Affichez le bouton "Voir plus" ici -->
        <?php echo $showMoreButton; ?>
    </body>
</html>
