<!DOCTYPE html>
<meta charset="utf-8">
<link rel="stylesheet" href="../css/style.css">
<html>
	<header>
		<h1>Bienvenue sur Platos Disciple</h1>
	</header>
	<body>
        <form action="" method="post">
            <input type="text" name="search" placeholder="Recherche" required>
            <input type="submit" value="Search">
        </form>
		


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
				$output = @file_get_contents($url);
				$objectData = json_decode($output, true);

			if($output !== FALSE){
				echo "<h2>" . $objectData['title'] . "</h2>";
				echo "<p>ObjectID: " . $objectData['objectID'] . "</P>";
				echo "<p>year: " . $objectData['objectDate'] . "</P>";
				echo "<p>Culture: " . $objectData['culture'] . "</p>";
				echo "<p>Artist: " . $objectData['artistDisplayName'] . "</p>";
				echo "<p>ArtistBio: " . $objectData['artistDisplayBio'] . "</p>";
				echo "<p>ArtistNationality: " . $objectData['artistNationality'] . "</p>";
				echo "<p>EndYear: " . $objectData['objectEndDate'] . "</p>";
				echo "<p>Dimensions: " . $objectData['dimensions'] . "</p>";
				echo "<p>Country: " . $objectData['country'] . "</P>";
				echo "<p>Classification: " . $objectData['classification'] . "</p>";
				

			
			if ($objectData['isPublicDomain'] && $objectData['primaryImageSmall'] != null) {

				echo"<img src='" . $objectData['primaryImageSmall'] . "' alt='" . $objectData['title'] . "' />";

			} else {

				$restricted_url = "https://collectionapi.metmuseum.org/api/collection/v1/iiif/" . $objectData['objectID'] . "/restricted";
				$thumbnail_url = "https://collectionapi.metmuseum.org/api/collection/v1/iiif/" . $objectData['objectID'] . "/thumbnail";

			if(!@getimagesize($restricted_url)) {

				echo "<img src='" . $thumbnail_url . "' alt='" . $objectData['title'] . "' />";

				} else {

					echo "<img src='" . $restricted_url . "' alt='" . $objectData['title'] . "'/>";
				}
			}
		}
	}
			}else{
			
			echo "<p>Pas de résultat</p>";
			}
		}
?>
	</body>
</html>