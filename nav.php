<nav id="nav">
	<ul>
<?php
if((isset($_SESSION['email']) && $_SESSION['droit']==1)||(isset($_COOKIE['email']) && $_COOKIE['drt']==1))
{
?>
	
		<li <?php if($current=='rechercherdepart' || $current=='ajouterdepart' || $current=='modifierdepart' || $current=='recapitulatifdep'){ echo 'class="currentli"';} ?>><span>Courriers départs</span>
			<ul>
				<!--li <?php if($current=='rechercherdepart') echo 'class="current"';?>><a href="rechercherdepart.php">Rechercher</a></li-->
				<li <?php if($current=='ajouterdepart') echo 'class="current"';?>><a href="ajouterdepart.php">Ajouter</a></li>
				<li <?php if($current=='modifierdepart') echo 'class="current"';?>><a href="rechercherdepart.php">Modifier</a></li>
				<li <?php if($current=='recapitulatifdep') echo 'class="current"';?>><a href="recapitulatifdep.php">Récapitulatif</a></li>
			</ul>
		</li>
		<li <?php if($current=='rechercherarrive' || $current=='ajouterarrive' || $current=='modifierarrive' || $current=='recapitulatifarr'){ echo 'class="currentli"';} ?>><span>Courriers arrivés</span>
			<ul>
				<!--li <?php if($current=='rechercherarrive') echo 'class="current"';?>><a href="rechercherarrive.php">Rechercher</a></li-->
				<li <?php if($current=='ajouterarrive') echo 'class="current"';?>><a href="ajouterarrive.php">Ajouter</a></li>
				<li <?php if($current=='modifierarrive') echo 'class="current"';?>><a href="rechercherarrive.php">Modifier</a></li>
				<li <?php if($current=='recapitulatifarr') echo 'class="current"';?>><a href="recapitulatifarr.php">Récapitulatif</a></li>
			</ul>
		</li>


	<?php
}
else
{ ?>
		<li <?php if($current=='rechercherdepart' || $current=='ajouterdepart' || $current=='modifierdepart' || $current=='recapitulatifdep'){ echo 'class="currentli"';} ?>><span>Courriers départs</span>
			<ul>
				<li <?php if($current=='recapitulatifdep') echo 'class="current"';?>><a href="recapitulatifdep.php">Récapitulatif</a></li>
			</ul>
		</li>
		<li <?php if($current=='rechercherarrive' || $current=='ajouterarrive' || $current=='modifierarrive' || $current=='recapitulatifarr'){ echo 'class="currentli"';} ?>><span>Courriers arrivés</span>
			<ul>
				<li <?php if($current=='recapitulatifarr') echo 'class="current"';?>><a href="recapitulatifarr.php">Récapitulatif</a></li>
			</ul>
		</li>
		
		
<?php	
}
?>
	
	</ul>
</nav>