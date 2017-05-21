<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="styles.css">
		<title>Stellarium - Accueil</title>
	</head>
	<body>
		<?php include("presentation.php"); ?>

		<a href="index.php">Accueil</a>
		<form method="post" action="liste_const.php">
			<label>Pour accéder à la liste des constellations, cliquez </label>
			<input type="submit" value="<< ici >>" name="bt_liste_const"/><br/>
		</form>
	</body>
</html>