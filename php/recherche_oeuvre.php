<?php
if (!empty($_SERVER['HTTPS'])) {
    header("Strict-Transport-Security: max-age=31536000");
}
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
session_start();
$_SESSION['search_url'] = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/config/databaseconnect.php');
require_once('src/check_like_status.php');
$showNextButton = '';
$showPrevButton = '';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
    <title>Bienvenue sur Platos Disciple</title>
</head>

<body>
    <header>
        <h1>Bienvenue sur Platos Disciple</h1>
        <a href="index.php">Accueil</a>
        <?php if (isset($_SESSION['loggedUser'])) : ?>
            <p><?php echo $_SESSION['nom_utilisateur']; ?></p>
            <a class="nav-link" href="src/logout.php">Déconnexion</a>
        <?php else : ?>
            <div class="lien">
                <p><a href="inscription.php">Inscription</a></p>
                <p><a href="login.php">Connexion</a></p>
            </div>
        <?php endif; ?>
    </header>
    <form id="search-form" action="" method="get">
        <input type="text" name="general_search" placeholder="Recherche" value="<?php echo isset($_GET['general_search']) ? $_GET['general_search'] : ''; ?>">
        <input type="submit" value="Rechercher">
    </form>
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
                                <h2><?php echo $objectData['title']; ?></h2>
                                <?php
                                if ($objectData['isPublicDomain'] && $objectData['primaryImageSmall'] != null) {
                                    echo "<img class='search-result-image' src='" . $objectData['primaryImageSmall'] . "' alt='" . $objectData['title'] . "' />";
                                    $imageUrl = $objectData['primaryImageSmall'];
                                } else {
                                    $imageUrl = "https://collectionapi.metmuseum.org/api/collection/v1/iiif/" . $objectData['objectID'] . "/restricted";
                                    echo "<img class='search-result-image' src='" . $imageUrl . "' alt='" . $objectData['title'] . "'/>";
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
                                    echo "<p>Connectez-vous pour liker cette oeuvre.</p>";
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
</body>

</html>
