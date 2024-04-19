<!DOCTYPE html>
<html>
<head>
    <title>Recherche dans la collection du Met Museum</title>
    <style>
        #loader {
            display: none;
                  width: 200px;
        }
    </style>
    <script>
        function showLoader() {
            document.getElementById('loader').style.display = 'block';
        }
        function hideLoader() {
            document.getElementById('loader').style.display = 'none';
        }
    </script>
</head>
<body>
    <form method="post" action="" onsubmit="showLoader()">
        <input type="text" name="search" placeholder="Search" required>
        <input type="submit" value="Search">
    </form>
                  <div id="loader">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <circle cx="50" cy="50" fill="none" stroke="#49d1e0" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">

                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
            </circle>
        </svg>
    </div>
    <?php 

    if (isset($_POST['search'])) {

        $search = urlencode($_POST['search']);
        $url = "https://collectionapi.metmuseum.org/public/collection/v1/search?q=$search";
        $output = file_get_contents($url);
        $data = json_decode($output, true);

            if($data['total'] > 0){
                $objectIDs = array_slice($data['objectIDs'], 0, 20);

            foreach ($objectIDs as $objectID) {

                $url = "https://collectionapi.metmuseum.org/public/collection/v1/objects/$objectID";
                $output = file_get_contents($url);
                $objectData = json_decode($output, true);

                echo "<h2>" . $objectData['title'] . "</h2>";
                echo "<p>ObjectID: " . $objectData['objectID'] . "</p>";
                echo "<p>Year: " . $objectData['objectDate'] . "</p>";
                echo "<p>Artist: " . $objectData['artistDisplayName'] . "</p>";

                if ($objectData['isPublicDomain'] && $objectData['primaryImageSmall'] != null) {

                    echo "<img src='" . $objectData['primaryImageSmall'] . "' alt='" . $objectData['title'] . "' />";

                } else {

                    $restricted_url = "https://collectionapi.metmuseum.org/api/collection/v1/iiif/" . $objectData['objectID'] . "/restricted";
                    $thumbnail_url = "https://collectionapi.metmuseum.org/api/collection/v1/iiif/" . $objectData['objectID'] . "/thumbnail";

                if(!@getimagesize($restricted_url)) { // if the restricted image does not exist

                    echo "<img src='" . $thumbnail_url . "' alt='" . $objectData['title'] . "' />";

                    } else {

                    echo "<img src='" . $restricted_url . "' alt='" . $objectData['title'] . "' />";
                    }
                }
            }
                }else{

                echo "<p>Pas de résultat</p>";
                }             
        echo '<script type="text/javascript">hideLoader();</script>'; // Cache le loader
    }
    ?>
</body>
</html>

<form method="post">
    <input type="submit" name="submit" value="Fetch Data">
</form>