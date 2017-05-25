<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="styles.css">
		<title>Liste des constellations</title>
	</head>
	<body>
	<?php include("presentation.php"); ?>

		<a href="index.php">Accueil</a>
		<span>></span>
		<a href="liste_const.php">Liste des constellations</a>
		<h2>Liste des constellations</h2>
		<table class="hemis">
			<tr>
				<th>Hémisphère nord</th>
				<th>Hémisphère sud</th>
			</tr>
			<tr>
				<td>
					<ul type="disk">
						<?php
							try
							{
								$bdd = new PDO('pgsql:host=ec2-54-247-166-129.eu-west-1.compute.amazonaws.com;dbname=d8d5o1h0870hg;charset=utf8', 'tkiiknfwbenkoe', '52a255680d0d2409a7ccefbd3da3cba1740f34c9e7e879d3d331faad1796ab02', array(PDO::ATTR_ERRMODE => PDO::	ERRMODE_EXCEPTION)); // Connexion à la bdd
							}
							catch (Exception $e)
							{
			        			die('Erreur : ' . $e->getMessage());
							}
		
							$reponse = $bdd->query('SELECT nomconst, idconst FROM CONSTELLATION WHERE hemisnord');
							while($donnees = $reponse->fetch())
							{
						?>
								<li><a href=<?php echo 'constellation.php'.'?'.'id='.$donnees['idconst']; ?>><?php echo $donnees['nomconst']; ?></a></li>
						<?php
							}
							$reponse->closeCursor();
						?>
					</ul>
				</td>
				<td>
					<ul type="disk">
						<?php
							try
							{
								$bdd = new PDO('pgsql:host=ec2-54-247-166-129.eu-west-1.compute.amazonaws.com;dbname=d8d5o1h0870hg;charset=utf8', 'tkiiknfwbenkoe', '52a255680d0d2409a7ccefbd3da3cba1740f34c9e7e879d3d331faad1796ab02', array(PDO::ATTR_ERRMODE => PDO::	ERRMODE_EXCEPTION));
							}
							catch (Exception $e)
							{
			        			die('Erreur : ' . $e->getMessage());
							}
		
							$reponse = $bdd->query('SELECT nomconst, idconst FROM CONSTELLATION WHERE hemissud');
							while($donnees = $reponse->fetch())
							{
						?>
								<li><a href=<?php echo 'constellation.php'.'?'.'id='.$donnees['idconst']; ?>><?php echo $donnees['nomconst']; ?></a></li>
						<?php
							}
							$reponse->closeCursor();
						?>
					</ul>
				</td>
			</tr>
		</table>
	</body>
</html>
