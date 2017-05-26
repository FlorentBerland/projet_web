<!DOCTYPE html>
<html>
	<head>
		<?php include("head.php") ?>
		<?php // Obtention des données à partir des id fournis
			if(isset($_GET['idsys']) && isset($_GET['idcomp']))
			{
				try
				{
					$bdd = new PDO('pgsql:host=ec2-54-247-166-129.eu-west-1.compute.amazonaws.com;dbname=d8d5o1h0870hg', 'tkiiknfwbenkoe', '52a255680d0d2409a7ccefbd3da3cba1740f34c9e7e879d3d331faad1796ab02', array(PDO::ATTR_ERRMODE => PDO::	ERRMODE_EXCEPTION));
				}
				catch (Exception $e)
				{
			   		die('Erreur : ' . $e->getMessage());
				}
				// Données de l'étoile
				//$reponse = $bdd->query('SELECT * FROM ETOILE WHERE idsys='.$_GET['idsys'].' AND idet=\''.$_GET['idcomp'].'\'');
				$reponse = $bdd->prepare('SELECT * FROM ETOILE WHERE idsys = ? AND idet = ?');
				$reponse->execute(array($_GET['idsys'], $_GET['idcomp']));
				$donnees = $reponse->fetch();
				$reponse->closeCursor();

				// Données du système auquel elle appartient
				//$reponse = $bdd->query('SELECT nomsys,idconst FROM SYSTEME WHERE idsys='.$donnees['idsys']);
				$reponse = $bdd->prepare('SELECT nomsys,idconst FROM SYSTEME WHERE idsys = ?');
				$reponse->execute(array($donnees['idsys']));
				$donnees_sys = $reponse->fetch();
				$reponse->closeCursor();

				// Données de la constellation à laquelle elle aprtient
				//$reponse = $bdd->query('SELECT nomconst FROM CONSTELLATION WHERE idconst='.$donnees_sys['idconst']);
				$reponse = $bdd->prepare('SELECT nomconst FROM CONSTELLATION WHERE idconst = ?');
				$reponse->execute(array($donnees_sys['idconst']));
				$donnees_const = $reponse->fetch();
				$reponse->closeCursor();

				// Obtention de toutes les particularités spectrales de l'étoile
				//$reponse = $bdd->query('SELECT liblum FROM CLASSELUM WHERE idlum='.$donnees['idlum']);
				$reponse = $bdd->prepare('SELECT liblum FROM CLASSELUM WHERE idlum = ?');
				$reponse->execute(array($donnees['idlum']));
				$donnees_lum = $reponse->fetch();
				$reponse->closeCursor();

				$reponse = $bdd->query('SELECT idpartic FROM ASSOPARTICETOILE WHERE idet=\''.$donnees['idet'].'\' AND idsys=\''.$donnees['idsys'].'\'');
				$i = 0;
				while($donnee_partic[$i] = $reponse->fetch())
				{
					$i++;
				}
				$reponse->closeCursor();
			}
		?>
		<title>Stellarium - <?php echo $donnees_sys['nomsys'].' '.$donnees['idet']; ?></title>
	</head>
	<body>
		<?php include("presentation.php"); ?>

		<a href="index.php">Accueil</a>
		<span>></span>
		<a href="liste_const.php">Liste des constellations</a>
		<span>></span>
		<a href=<?php echo 'constellation.php?id='.$donnees_sys['idconst']; ?>><?php echo $donnees_const['nomconst']; ?></a>
		<span>></span>
		<a href=<?php echo 'systeme.php?id='.$donnees['idsys']; ?>><?php echo $donnees_sys['nomsys']; ?></a>
		<a href=<?php echo 'composante.php?idsys='.$donnees['idsys'].'&amp;idcomp='.$donnees['idet']; ?>><?php echo $donnees['idet']; ?></a>

		<h2><?php echo $donnees_sys['nomsys'].' '.$donnees['idet']; ?></h2>
		<h3>Description</h3>
		<p><?php echo $donnees['description']; ?></p>

		<!-- Création du tableau contenant les caractéristiques -->
		<h3>Caractéristiques</h3>

		<h4>Présentation générale</h4>
		<table class="caract">
			<tr>
				<th>Nom</th>
				<td><?php echo $donnees_sys['nomsys'].' '.$donnees['idet']; ?></td>
			</tr>
			<tr>
				<th>Constellation</th>
				<td><?php echo $donnees_const['nomconst']; ?></td>
			</tr>
			<tr>
				<th rowspan="2">Coordonnées</th>
				<td>
				<?php
					$heures = (int)($donnees['ascension']/3600);
					$minutes = (int)(($donnees['ascension']-3600*$heures)/60);
					$secondes = $donnees['ascension']-3600*$heures-60*$minutes;
					echo 'RA : '.$heures.'h '.$minutes.'\' '.round($secondes, 5).'"';
				?>
				</td>
			</tr>
			<tr>
				<td>
				<?php
					$positif = $donnees['declinaison']>=0;
					$donnees['declinaison'] = abs($donnees['declinaison']);
					$degres = (int)($donnees['declinaison']/3600);
					$minutes = (int)(($donnees['declinaison']-3600*$degres)/60);
					$secondes = $donnees['declinaison']-3600*$degres-60*$minutes;
					echo 'dec : '.($positif ? '+' : '-').$degres.'° '.$minutes.'\' '.round($secondes, 5).'"';
				?>
				</td>
			</tr>
			<tr>
				<th>Type spectral</th>
				<!-- Lettre type spectral, chiffre spectral, classe de luminosité, particularités spectrales -->
				<td><?php
					$typespectral = $donnees['idspectr'];
					$valeurspectrale = $donnees['valspectr'];
					$classelum = $donnees_lum['liblum'];
					echo $typespectral.$valeurspectrale.$classelum;
					for($i=0; $i<count($donnee_partic); $i++)
					{
						echo $donnee_partic[$i]['idpartic'];
					}
				?></td>
			</tr>
			<tr>
				<th>Variabilité</th>
				<td><?php echo $donnees['variabilite'] ?></td>
			</tr>
		</table>

		<h4>Données astrométriques</h4>
		<table class="caract">
			<tr>
				<th>Distance (pc)</th>
				<td><?php echo $donnees['distance']; ?></td>
			</tr>
			<tr>
				<th>Parallaxe (°)</th>
				<td><?php echo $donnees['paralaxe']; ?></td>
			</tr>
			<tr>
				<th>Vitesse radiale (km/s)</th>
				<td><?php echo $donnees['vitesseradiale']; ?></td>
			</tr>
			<tr>
				<th rowspan="2">Mouvement propre (mas/an)</th>
				<td><?php echo 'PM-a : '.$donnees['mouvasc']; ?></td>
			</tr>
			<tr>
				<td><?php echo 'PM-d : '.$donnees['mouvdec']; ?></td>
			</tr>
			<tr>
				<th>Redshift</th>
				<td><?php echo $donnees['redshift']; ?></td>
			</tr>
			<tr>
				<th>Magnitude apparente (mag)</th>
				<td><?php echo $donnees['magap']; ?></td>
			</tr>
		</table>

		<h4>Paramètres stellaires</h4>
		<table class="caract">
			<tr>
				<th>Masse (Mo)</th>
				<td><?php echo $donnees['masse']; ?></td>
			</tr>
			<tr>
				<th>Rayon (Ro)</th>
				<td><?php echo $donnees['rayon']; ?></td>
			</tr>
			<tr>
				<th>Luminosité (Lo)</th>
				<td><?php echo $donnees['luminosite']; ?></td>
			</tr>
			<tr>
				<th>Température de surface (K)</th>
				<td><?php echo $donnees['temperature']; ?></td>
			</tr>
			<tr>
				<th>Gravité de surface (cgs)</th>
				<td><?php echo $donnees['gravite']; ?></td>
			</tr>
			<tr>
				<th>Métallicité (%)</th>
				<td><?php echo $donnees['metalicite']; ?></td>
			</tr>
			<tr>
				<th>Période (h)</th>
				<td><?php echo $donnees['rotation']; ?></td>
			</tr>
			<tr>
				<th>Vitesse de rotation (km/s)</th>
				<td><?php echo $donnees['vitrot']; ?></td>
			</tr>
			<tr>
				<th>Magnitude absolue (mag)</th>
				<td><?php echo $donnees['magabs']; ?></td>
			</tr>
		</table>
	</body>
</html>