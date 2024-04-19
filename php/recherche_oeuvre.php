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
		
	</body>
</html>

<?php
if (isset($_POST['search'])) {
	$search = urlencode($_POST['search']);
	$url = "https://collectionapi.metmuseum.org/public/collection/v1/search?q=$search";
	$output = file_get_contents($url);
	$data = json_decode($output, true);

		if ($objectData['isPublicDomain'] && $objectData['primaryImageSmall'] != null) {

			echo"<img src='" . $objectData['primaryImageSmall'] . "' alt='" . $objectData['title'] . "' />";
		}
}
?>