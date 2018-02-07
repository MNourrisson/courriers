<?php 
	session_start();
	$_SESSION['page']='modifierarrive.php?c='.$_GET['c'];
	if(isset($_COOKIE['email']) && $_COOKIE['email']!='' && $_COOKIE['drt']==1)
	{
		$_SESSION['email'] = $_COOKIE['email'];
		$_SESSION['id'] =$_COOKIE['id'];
		$_SESSION['droit'] =$_COOKIE['drt'];	
	}
	if(!isset($_SESSION['email']) || $_SESSION['email']=='' || $_SESSION['droit']!=1)
	{header('Location: connexion.php');}
	
	$current = 'modifierarrive';
	
	include_once('classes/connect.php');
	include_once('classes/Arrive.php');
	include_once('classes/Depart.php');
	$arr = new Arrive();
	$dep = new Depart();
	
	$infosC = $arr->getInfosArrive($_GET['c']);
	$erreur=array();
	$erreurmsg='';
	$msgok='';
	if(isset($_POST['departc']) && $_POST['departc']=='Enregistrer' )
	{
		$erreur=array();
		
		if($_POST['datec']=='') 
		{
			$erreur['datec']='erreur';
		}
		if($_POST['exp']=='')
		{
			$erreur['exp']='erreur';
		}
		if($_POST['contenu']=='')
		{
			$erreur['contenu']='erreur';
		}
		if(count($erreur)!=0)
		{
			$erreurmsg='Il y a '.count($erreur).' erreur(s). Merci de vérifier les données rentrées.';
		}
		else
		{	
			$dateavant=$_POST['datec'];
			$array_majdate = explode('/',$dateavant); 
			$datetranformee=$array_majdate[2].'-'.$array_majdate[1].'-'.$array_majdate[0]; 
			//verifier existence de id reponse;
			
			$up = $arr->upArrive($_GET['c'],$datetranformee,addslashes($_POST['exp']),addslashes($_POST['contenu']),$_POST['tech'],$_POST['rep'],$_SESSION['id']);
			
		}
		
		if(count($erreur)==0)
			$msgok='La mise à jour s\'est bien déroulée.';
		else
			$erreur['update']='Erreur sur l\'update';
	}
	require_once('head.php');
	require_once('nav.php');
?>
<section id="content">
	<p class="retour"><a href="rechercherarrive.php?d=<?php echo $_GET['d']; ?>">&lt;&lt; Retour aux résultats</a></p>
	<h2>Modification d'un courrier arrivé</h2>
	<p class="indication">Les champs suivis de * sont obligatoires.</p>
	<?php if(isset($erreurmsg) && $erreurmsg!='') echo '<p class="erreur left">'.$erreurmsg.' '.$erreur['update'].'</p><br/>'; ?>
	<?php if(isset($msgok) && $msgok!=''){ echo '<p class="msgok left">'.$msgok.'</p><br/>'; }?>
	<form action="" method="post">
		<fieldset>
			<label for="datec">Date<span class="obligatoire">*</span></label><input type="text" value="<?php if(isset($_POST['datec']) && $_POST['datec']!='') {echo $_POST['datec'];} elseif( isset($infosC[0]['date']) && $infosC[0]['date']!='0000-00-00'){$tab_majdate = explode('-',$infosC[0]['date']); $date_c = $tab_majdate[2].'/'.$tab_majdate[1].'/'.$tab_majdate[0]; echo $date_c; } ?>" name="datec" id="datec" placeholder="jj/mm/aaaa"/><?php if(isset($erreur['datec'])) echo '<p class="erreur left">'.$erreur['datec'].'</p>';?><br/>
			<label for="exp">Expéditeur<span class="obligatoire">*</span></label><input type="text" value="<?php if(isset($_POST['exp']) && $_POST['exp']!='') {echo $_POST['exp'];} else{echo htmlspecialchars(stripcslashes($infosC[0]['expediteur']));}?>" name="exp" id="exp" /><?php if(isset($erreur['exp'])) echo '<p class="erreur left">'.$erreur['exp'].'</p>';?><br/>	
			<label for="contenu">Contenu<span class="obligatoire">*</span></label><input type="text" value="<?php if(isset($_POST['contenu']) && $_POST['contenu']!='') {echo $_POST['contenu'];}else{echo htmlspecialchars(stripcslashes($infosC[0]['contenu']));} ?>" name="contenu" id="contenu" /><?php if(isset($erreur['contenu'])) echo '<p class="erreur left">'.$erreur['contenu'].'</p>';?><br/>	
			<label for="tech">Techniciens</label><input type="text" value="<?php if(isset($_POST['tech']) && $_POST['tech']!='') {echo $_POST['tech'];} else{echo $infosC[0]['techniciens'];} ?>" name="tech" id="tech" /><br/>
			<label for="rep">Numéro courrier réponse</label><input type="text" value="<?php if(isset($_POST['rep']) && $_POST['rep']!=0) {echo $_POST['rep'];}else{echo $infosC[0]['id_courrier_reponse'];}?>" name="rep" id="rep" /><?php if(isset($erreur['rep'])) echo '<p class="erreur left">'.$erreur['rep'].'</p>';?><br/>
			<input type="submit" value="Enregistrer" name="departc"/>
		</fieldset>
	
	</form>
</section>
<script type="text/javascript">

$(document).ready(function() {	
	$("#datec").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true
	});$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );


});
</script>
<?php

require_once('footer.php');

?>
