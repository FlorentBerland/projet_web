<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="styles.css">
		<?php // Obtention du nom du système à partir de l'id fourni dans l'url
			if(isset($_GET['id']))
			{
				try
				{
					$bdd = new PDO('mysql:host=localhost;dbname=Astres;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
				}
				catch (Exception $e)
				{
			   		die('Erreur : ' . $e->getMessage());
				}

				$reponse = $bdd->query('SELECT * FROM SYSTEME WHERE idsys='.$_GET['id']);
				$donnees = $reponse->fetch();
				$reponse->closeCursor();

				// Obtention du nom de la constellation qui contient ce système
				$reponse = $bdd->query('SELECT nomconst FROM CONSTELLATION WHERE idconst='.$donnees['idconst']);
				$nomconst = $reponse->fetch()['nomconst'];
				$reponse->closeCursor();
			}
		?>
		<title><?php echo $donnees['nomsys']; ?></title>
	</head>
	<body>
		<?php include("presentation.php"); ?>
		
		<a href="index.php">Accueil</a>
		<span>></span>
		<a href="liste_const.php">Liste des constellations</a>
		<span>></span>
		<a href=<?php echo 'constellation.php?id='.$donnees['idconst']; ?>><?php echo $nomconst; ?></a>
		<span>></span>
		<a href=<?php echo 'systeme.php?id='.$donnees['idsys']; ?>><?php echo $donnees['nomsys']; ?></a>

		<h2><?php echo $donnees['nomsys']; ?></h2>
		<h3>Description</h3>
		<p><?php echo $donnees['description']; ?></p>
		<h3>Composantes</h3>
		<ul type="disk">
		<?php // Obtention de composantes du système
			$reponse = $bdd->query('SELECT idet FROM ETOILE WHERE idsys='.$donnees['idsys']);
			while($composante = $reponse->fetch())
			{
		?>
				<li><a href=<?php echo 'composante.php?idsys='.$donnees['idsys'].'&amp;idcomp='.$composante['idet']; ?>><?php echo $donnees['nomsys'].' '.$composante['idet']; ?></a></li>
		<?php
			}
			$reponse->closeCursor();
		?>
		</ul>
	</body>
</html>