<?php
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    header("Strict-Transport-Security: max-age=31536000");
}
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
session_start();
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
        <!-- Modifiez l'attribut "method" de "post" à "get" -->
        <form id="search-form" action="" method="get">
            <input type="text" name="title_search" placeholder="Recherche par titre" value="<?php echo isset($_GET['title_search']) ? $_GET['title_search'] : ''; ?>">
            <input type="text" name="artist_search" placeholder="Recherche par artiste" value="<?php echo isset($_GET['artist_search']) ? $_GET['artist_search'] : ''; ?>">
            <input type="text" name="date_search" placeholder="Recherche par date" value="<?php echo isset($_GET['date_search']) ? $_GET['date_search'] : ''; ?>">
            <input type="text" name="culture_search" placeholder="Recherche par culture" value="<?php echo isset($_GET['culture_search']) ? $_GET['culture_search'] : ''; ?>">
            <input type="submit" value="Rechercher">
        </form>
        <?php
        // Utilisez $_GET au lieu de $_POST pour récupérer les critères de recherche
        $searchQuery = '';
        if (!empty($_GET['title_search'])) {
            $searchQuery .= urlencode($_GET['title_search']) . ' ';
        }
        if (!empty($_GET['artist_search'])) {
            $searchQuery .= urlencode($_GET['artist_search']) . ' ';
        }
        if (!empty($_GET['date_search'])) {
            $searchQuery .= urlencode($_GET['date_search']) . ' ';
        }
        if (!empty($_GET['culture_search'])) {
            $searchQuery .= urlencode($_GET['culture_search']);
        }
        $searchQuery = trim($searchQuery);
        if ($searchQuery) {
            echo "<script>hideSearchForm();</script>";
            $url = "https://collectionapi.metmuseum.org/public/collection/v1/search?q=$searchQuery";
            $output = file_get_contents($url);
            if ($output !== FALSE) {
                $data = json_decode($output, true);
                if ($data['total'] > 0) {
                    $paging = 8; // Nombre d'œuvres à afficher par page
                    if (isset($_GET['page'])) {
                        $currentPage = $_GET['page'];
                    } else {
                        $currentPage = 1;
                    }
                    $pagingrequest = $paging * $currentPage;
                    $objectIDs = array_slice($data['objectIDs'], $pagingrequest - $paging, $pagingrequest);
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
                    // Modifiez le lien du bouton "Voir plus" pour inclure les critères de recherche
                    if ($data['total'] > $pagingrequest) {
                        $nextPage = $currentPage + 1;
                        echo "<a href='?page=$nextPage&title_search=" . $_GET['title_search'] . "&artist_search=" . $_GET['artist_search'] . "&date_search=" . $_GET['date_search'] . "&culture_search=" . $_GET['culture_search'] . "' class='voir-plus'>Voir plus</a>";
                    }
                } else {
                    echo "<p>Pas de résultat</p>";
                }
            } else {
                echo "<p>Erreur lors de la récupération des données.</p>";
            }
        }
        ?>
    </body>
</html>
