<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Plateforme de gestion des courriers - PNRLF</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/jquery-ui.css">
  <script type="text/javascript"  src="js/jquery-2.1.3.min.js"></script>
  <script type="text/javascript"  src="js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="js/jquery.ui.datepicker-fr.js"></script>
  <script type="text/javascript" src="js/jquery.quicksearch.js"></script>
</head>
<body>
	<header>
	<?php if((isset($_SESSION['email']) && $_SESSION['droit']==1)||(isset($_COOKIE['email']) && $_COOKIE['drt']==1))
{}else{ ?><span class="connect"><a href="connexion.php">Connexion</a></span><?php }?>
		<div class="header">
			<h1><a href="index.php">Gestion des courriers départs et arrivés</a></h1>		
			
		</div>
	</header>
