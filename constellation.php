<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="styles.css">
		<?php // Obtention du nom de la constellation à partir de l'id fourni dans l'url
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

				$reponse = $bdd->query('SELECT * FROM CONSTELLATION WHERE idconst='.$_GET['id']);
				$nom_constellation = NULL;
				if($donnees = $reponse->fetch())
				{
					$nom_constellation = $donnees['nomconst'];
				}
				else
				{
					
				}
				$reponse->closeCursor();
			}
		?>
		<title><?php echo $nom_constellation; ?></title>
	</head>
	<body>
		<?php include("presentation.php"); ?>
		
		<a href="index.php">Accueil</a>
		<span>></span>
		<a href="liste_const.php">Liste des constellations</a>
		<span>></span>
		<a href=<?php echo 'constellation.php?id='.$_GET['id']; ?>><?php echo $nom_constellation; ?></a>

		<h2><?php echo $nom_constellation; ?></h2>
		<h3>Description</h3>
		<p><?php echo $donnees['description']; ?></p>
		<h3>Etoiles recencées</h3>
		<ul type="disk">
		<?php // Obtention du nom et de l'id de toutes les étoiles composant la constellation
			$reponse = $bdd->query('SELECT idsys,nomsys FROM SYSTEME WHERE idconst='.$_GET['id']);
			while($donnees = $reponse->fetch())
			{
		?>
				<li><a href=<?php echo 'systeme.php?id='.$donnees['idsys']; ?>><?php echo $donnees['nomsys']; ?></a></li>
		<?php
			}
			$reponse->closeCursor();
		?>
		</ul>
	</body>
</html>