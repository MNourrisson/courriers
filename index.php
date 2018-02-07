<?php 
	session_start();
	$_SESSION['page']='index.php';
	$current='';
	include_once('classes/connect.php');

	
	require_once('head.php');
	require_once('nav.php');
?>
<section id="content">
	<h2>Bienvenue sur la plateforme de gestion des courriers départs et arrivés du Parc naturel régional Livradois-Forez.</h2>
	<p class="indication">Les navigateurs conseillés pour les enregistrements sont Google Chrome et Mozilla Firefox.</p>
	
</section>
<?php

require_once('footer.php');

?>
