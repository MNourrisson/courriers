<?php 

	include_once('classes/connect.php');
	$term = $_GET['term'];

	$requete = $bdd->prepare('SELECT DISTINCT(expediteur) FROM arrive WHERE expediteur LIKE :term'); // j'effectue ma requête SQL grâce au mot-clé LIKE
	$requete->execute(array('term' => '%'.$term.'%'));

	$array = array(); // on créé le tableau

	while($donnee = $requete->fetch()) // on effectue une boucle pour obtenir les données
	{
		array_push($array, stripcslashes($donnee['expediteur'])); // et on ajoute celles-ci à notre tableau
	}

	echo json_encode($array); // il n'y a plus qu'à convertir en JSON
?>