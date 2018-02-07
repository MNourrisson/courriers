<?php 
	session_start();
	$_SESSION['page']='rechercherarrive.php';
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
	
	$current='modifierarrive';
	include_once('classes/connect.php');
	include_once('classes/Arrive.php');

	$arr = new Arrive();
	
	$erreur=array();
	$erreurmsg='';
	$erreurmsgSupp='';
	
	$messdel='';
	if(isset($_POST['supp']) && $_POST['supp']=='Supprimer')
	{
		$erreurdel=array();
		$idcourrier=$_POST['moncourrier'];
		$fiche = $arr->deleteFiche($idcourrier);
	}
	if(isset($_GET['d']) && $_GET['d']!='' && !isset($_POST['recherche']))
	{
		$datetranformee=$_GET['d'];
		$array_majdate = explode('-',$datetranformee); 
		$dateavant=$array_majdate[2].'/'.$array_majdate[1].'/'.$array_majdate[0]; 
		$listeRes = $arr->getRecherche($_GET['d']);
		$_POST['recherche_annee']=$dateavant;
		if(count($listeRes)==0)
		{
			$erreurmsg='Aucun résultat trouvé pour '.$dateavant;	
		}
		else
		{	$liste='';
			foreach($listeRes as $cle => $valeur)
			{
				$liste .= '<li><a href="modifierarrive.php?c='.$valeur['id_arrive'].'&d='.$datetranformee.'">'.stripcslashes($valeur['expediteur']).' ('.stripcslashes($valeur['contenu']).')</a><form action="" method="post" class="formulairesupp"><input type="hidden" value="'.$valeur['id_arrive'].'" name="moncourrier"/><input type="submit" value="Supprimer" name="supp"/></form></li>';
			}
		}
	}
	if(isset($_POST['recherche']) && $_POST['recherche']=='Rechercher' && $_POST['recherche_annee']!='')
	{
		$dateavant=$_POST['recherche_annee'];
		$array_majdate = explode('/',$dateavant); 
		$datetranformee=$array_majdate[2].'-'.$array_majdate[1].'-'.$array_majdate[0]; 
		$listeRes = $arr->getRecherche($datetranformee);
		if(count($listeRes)==0)
		{
			$erreurmsg='Aucun résultat trouvé pour '.$dateavant;	
		}
		else
		{	$liste='';
			foreach($listeRes as $cle => $valeur)
			{
				$liste .= '<li><a href="modifierarrive.php?c='.$valeur['id_arrive'].'&d='.$datetranformee.'">'.stripcslashes($valeur['expediteur']).' ('.stripcslashes($valeur['contenu']).')</a><form action="" method="post" class="formulairesupp"><input type="hidden" value="'.$valeur['id_arrive'].'" name="moncourrier"/><input type="submit" value="Supprimer" name="supp"/></form></li>';
			}
		}
	}
	
	
	if(isset($_POST['recherche_annee']) && $_POST['recherche_annee']=='')
	{
		$erreur['recherche_annee']='Erreur';
	}
	if(count($erreur)!=0)
	{
		$erreurmsg='Il y a '.count($erreur).' erreur. Merci de vérifier la date.';
	}	
	require_once('head.php');
	require_once('nav.php');
?>
<section id="content">
	<h2>Rechercher un courrier arrivé</h2>
	<?php 
		//echo '<pre>'; print_r($listeRes); echo '</pre>';
		
		
	?>
	<p class="indication">Les champs suivis de * sont obligatoires.</p>
	<form action="" method="post">
		<fieldset>
			<label for="recherche_annee">Date<span class="obligatoire">*</span></label><input type="text" value="<?php if(isset($_POST['recherche_annee']) && $_POST['recherche_annee']!='') {echo $_POST['recherche_annee'];} else echo date('d/m/Y');?>" name="recherche_annee" id="recherche_annee" maxlength="10" /><?php if(isset($erreur['recherche_annee'])) echo '<p class="erreur left">'.$erreur['recherche_annee'].'</p>';?><br/>
			<input type="submit" value="Rechercher" name="recherche"/>
		</fieldset>
	
	</form>
	<?php
		if(isset($listeRes))
		{
			if($erreurmsg!='')
			{
				echo '<p class="erreur">'.$erreurmsg.'</p>';
			}
			else
			{	
				echo '<p class="resultat">Il y a '.count($listeRes).' résultat(s) pour "'.$dateavant.'".</p><ul class="liste_res">'.$liste.'</ul>';
			}
		}
		if(isset($erreurmsgSupp) && $erreurmsgSupp!='')
		{
			echo '<p class="erreur">'.$erreurmsgSupp.'</p>';
		}
	?>
</section>
<script type="text/javascript">

$(document).ready(function() {	
	$("#recherche_annee").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true
	});$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );


});
</script>
<?php

require_once('footer.php');

?>
