<?php
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    header("Strict-Transport-Security: max-age=31536000");
}
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
session_start();
$showMoreButton = ''; 
$newSearchButton = ''; 
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
        function showSearchForm() {
            document.getElementById('search-form').style.display = 'block';
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
        <div class="results-container">
        <?php
        if (!empty($_GET['general_search'])) {
            $searchQuery = urlencode($_GET['general_search']);
            echo "<script>hideSearchForm();</script>";
            $url = "https://collectionapi.metmuseum.org/public/collection/v1/search?q=$searchQuery";
            $output = file_get_contents($url);
            if ($output !== FALSE) {
                $data = json_decode($output, true);
                if ($data['total'] > 0) {
                    $paging = 6; // Affichage de 6 œuvres par page
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
                                <h2><?php echo $objectData['title']; ?></h2>
                                <?php
                                if ($objectData['isPublicDomain'] && $objectData['primaryImageSmall'] != null) {
                                    echo "<img class='search-result-image' src='" . $objectData['primaryImageSmall'] . "' alt='" . $objectData['title'] . "' />";
                                } else {
                                    $restricted_url = "https://collectionapi.metmuseum.org/api/collection/v1/iiif/" . $objectData['objectID'] . "/restricted";
                                    echo "<img class='search-result-image' src='" . $restricted_url . "' alt='" . $objectData['title'] . "'/>";
                                }
                                ?>
                                <p>ObjectID: <?php echo $objectData['objectID']; ?></p>
                                <p>Year: <?php echo $objectData['objectDate']; ?></p>
                                <p>Culture: <?php echo $objectData['culture']; ?></p>
                                <p>Artist: <?php echo $objectData['artistDisplayName']; ?></p>
                                <p>ArtistBio: <?php echo $objectData['artistDisplayBio']; ?></p>
                                <p>ArtistNationality: <?php echo $objectData['artistNationality']; ?></p>
                                <p>EndYear: <?php echo $objectData['objectEndDate']; ?></p>
                                <p>Dimensions: <?php echo $objectData['dimensions']; ?></p>
                                <p>Country: <?php echo $objectData['country']; ?></p>
                                <p>Classification: <?php echo $objectData['classification']; ?></p>
                            </div>
                            <?php
                        }
                    }
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
            $newSearchButton = "<a href='http://projet.test/PlatosDisciple/php/recherche_oeuvre.php' class='nouvelle-recherche'>Nouvelle recherche</a>";
        }
        ?>
        </div>
        <?php echo $showMoreButton; ?>
        <?php echo $newSearchButton; ?>
    </body>
</html>
