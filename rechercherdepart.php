<?php 
	session_start();
	$_SESSION['page']='rechercherdepart.php';
	if(isset($_COOKIE['email']) && $_COOKIE['email']!='' && $_COOKIE['drt']==1)
	{
		$_SESSION['email'] = $_COOKIE['email'];
		$_SESSION['id'] =$_COOKIE['id'];
		$_SESSION['droit'] =$_COOKIE['drt'];	
	}
	if(!isset($_SESSION['email']) || $_SESSION['email']=='' || $_SESSION['droit']!=1)
	{
		header('Location: connexion.php');
	}
	
	$current='modifierdepart';
	include_once('classes/connect.php');
	include_once('classes/Depart.php');
	include_once('classes/Lien.php');

	$dep = new Depart();
	$lien = new Lien();
	
	$erreur=array();
	$erreurmsg='';
	$messdel='';
	if(isset($_POST['supp']) && $_POST['supp']=='Supprimer')
	{
		$erreurdel=array();
		$idcourrier=$_POST['moncourrier'];
		$liens=$lien->getLiens($idcourrier);
		if(count($liens)!=0)
		{
			foreach($liens as $key => $val)
			{
				if(unlink('uploads/'.$val['lien']))
					$del = $lien->delPiece($val['id_lien']);
				else
					$erreurdel['erreurunlink'.$key] = 'Erreur sur le unlink '.$val['lien'];
			}
			
		}
		if(count($erreurdel)==0)
			$fiche = $dep->deleteFiche($idcourrier);
		else
			$messdel = 'Il y a eu des erreurs lors de la suppression.';
	}
	
	if(isset($_POST['recherche']) && $_POST['recherche']=='Rechercher' && $_POST['recherche_num']!='')
	{
		$num = $_POST['recherche_num'];
		$listeRes = $dep->getRechercheNum($num);
		if(count($listeRes)==0)
		{
			$erreurmsg='Aucun résultat trouvé pour '.$num;	
		}
		else
		{	$liste='';
			foreach($listeRes as $cle => $valeur)
			{
				$liste .= '<li><a href="modifierdepart.php?c='.$valeur['id_depart'].'">'.$valeur['id_formate'].' ('.stripcslashes($valeur['destinataire']).')</a><form action="" method="post" class="formulairesupp"><input type="hidden" value="'.$valeur['id_depart'].'" name="moncourrier"/><input type="submit" value="Supprimer" name="supp"/></form></li>';
			}
		}
	}
	if(isset($_POST['recherche_num']) && $_POST['recherche_num']=='')
	{
		$erreur['recherche_num']='Erreur';
	}
	if(count($erreur)!=0)
	{
		$erreurmsg='Il y a '.count($erreur).' erreur. Merci de vérifier la date.';
	}
	
	require_once('head.php');
	require_once('nav.php');
?>
<section id="content">
	<h2>Rechercher un courrier départ</h2>
	<?php 
		//echo '<pre>'; print_r($listeRes); echo '</pre>';
		
		
	?>
	<p class="indication">Les champs suivis de * sont obligatoires.</p>
	<form action="" method="post">
		<fieldset>
			<label for="recherche_num">Numéro courrier<span class="obligatoire">*</span></label><input type="text" value="<?php if(isset($_POST['recherche_num']) && $_POST['recherche_num']!='') {echo $_POST['recherche_num'];}?>" name="recherche_num" id="recherche_num" maxlength="15" /><?php if(isset($erreur['recherche_num'])) echo '<p class="erreur left">'.$erreur['recherche_num'].'</p>';?><br/>
			<input type="submit" value="Rechercher" name="recherche"/>
		</fieldset>
	
	</form>
	<?php
		if(isset($listeRes))
		{
			if($messdel!='')
			{
				echo '<p class="erreur">'.$messdel.' : '.print_r($erreurdel).'</p>';
			}
			if($erreurmsg!='')
			{
				echo '<p class="erreur">'.$erreurmsg.'</p>';
			}
			else
			{	
				echo '<p class="resultat">Il y a '.count($listeRes).' résultat(s) pour "'.$num.'".</p><ul class="liste_res">'.$liste.'</ul>';
			}
		}
	?>
</section>
<?php

require_once('footer.php');

?>
