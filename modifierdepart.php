<?php 
	session_start();
	$_SESSION['page']='modifierdepart.php?c='.$_GET['c'];
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
	
	$current = 'modifierdepart';
	include_once('classes/connect.php');
	include_once('classes/Depart.php');
	include_once('classes/Lien.php');
	$dep = new Depart();
	$lien = new Lien();
	$infosC = $dep->getInfosDepart($_GET['c']);
	$listefichiers = $lien->getLiens($_GET['c']);
	
	$tmp=array();
	$tmp2=array();
	foreach($listefichiers as $k => $v)
	{
		$idenP=$v['id_lien'];
		$tmp[]=$idenP;
		$tmp2[$idenP]=$v;
	}
	$id_formate=$infosC[0]['id_formate'];
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
		if($_POST['dest']=='')
		{
			$erreur['dest']='erreur';
		}
		if($_POST['redac']=='')
		{
			$erreur['redac']='erreur';
		}
		if($_POST['nb']=='')
		{
			$erreur['nb']='erreur';
		}
		if($_POST['objet']=='')
		{
			$erreur['objet']='erreur';
		}
		$tabcompetences = array('Charte'=>'Charte','Parc'=>'Parc','Pays'=>'Pays','SAGE'=>'SAGE','SCoT'=>'SCoT');
		if(!array_key_exists($_POST['competence'],$tabcompetences))
		{
			$erreur['competence']='erreur';
		}
		$liste_res=array();
		$tabhiddenfiles=array();
		$uploaddir = '/votre/chemin/vers/uploads/';
		if(isset($_POST['hiddenfiles']) && count($_POST['hiddenfiles'])!=0)
		{
			$tabhiddenfiles=$_POST['hiddenfiles'];
		}
		$diff_tab=array_diff($tmp,$tabhiddenfiles);
		if(count($diff_tab)!=0)
		{
			foreach($diff_tab as $cle => $valeur)
			{
				$nomfichier = $tmp2[$valeur]['lien'];
				
				if(unlink($uploaddir.$nomfichier))
					$del = $lien->delPiece($valeur);
				else
					$erreur['erreurunlink'] = 'Erreur sur le unlink '.$nomfichier;
			}
		}
		unset($listefichiers);
		$listefichiers=array();
		
		//echo '<pre>'; print_r($_FILES);	echo '</pre>';
		foreach ($_FILES["pieces"]["error"] as $key => $error) {
			if ($error == UPLOAD_ERR_OK) {
				if(is_dir('uploads/') || mkdir('uploads/',0750,false))
				{
					$tmp_name = $_FILES["pieces"]["tmp_name"][$key];
					$nameext = $_FILES["pieces"]["name"][$key];
					$tempext1 = substr(strrchr($nameext,"."),1);
					$tempext = (strlen(substr(strrchr($nameext,"."),1))+1)*(-1);
					$name=$id_formate.'_'.rand().'.'.$tempext1;
					move_uploaded_file($tmp_name, $uploaddir.$name);
					$ins = $lien->addLien($_GET['c'],$name);
					$listefichiers[] = array('id_lien'=>$ins,'id_depart'=>$_GET['c'],'lien'=>$name);
					$tmp[]=$ins;
					$tmp2[$ins]=array('id_lien'=>$ins,'id_depart'=>$_GET['c'],'lien'=>$name);
					$_POST['hiddenfiles'][]=$ins;
				}
				else
				{
					$erreur['mkdir'] = 'Erreur création du dossier.';
				}
			}
			if($error == UPLOAD_ERR_INI_SIZE)
			{
				$erreur['upload'] = 'Erreur sur upload (taille du fichier ini). Fichier trop lourd.';
			}
			if($error == UPLOAD_ERR_FORM_SIZE)
			{
				$erreur['upload2'] = 'Erreur sur upload (taille du fichier champs max size form). Fichier trop lourd.';
				
			}
			if($error == UPLOAD_ERR_PARTIAL)
			{
				$erreur['upload3'] = 'Erreur sur upload (fichier partiellement téléchargé).';
				
			}
			// if($error == UPLOAD_ERR_NO_FILE)
			// {
				// $erreur['upload4'] = 'Erreur sur upload (aucun fichier téléchargé).';
				
			// }
			if($error == UPLOAD_ERR_NO_TMP_DIR)
			{
				$erreur['upload5'] = 'Erreur sur upload (dossier temporaire manquant).';
				
			}
			if($error == UPLOAD_ERR_CANT_WRITE)
			{
				$erreur['upload5'] = 'Erreur sur upload (echec de l\'écriture du fichier sur le disque).';
				
			}
			if($error == UPLOAD_ERR_EXTENSION)
			{
				$erreur['upload5'] = 'Erreur sur upload (pb extension).';
				
			}
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
			
			$up = $dep->upDepart($_GET['c'],$datetranformee,addslashes($_POST['dest']),$_POST['redac'],$_POST['nb'],addslashes($_POST['objet']),$_SESSION['id'],$_POST['competence']);
			
		}
		if(count($erreur)==0)
			$msgok='La mise à jour s\'est bien déroulée.';
		else
			$erreur['update']='Erreur sur l\'update';
	}
	
	$erreurbister='';
	$hasError=false;
	$new_id='';
	$liste=array();
	if(isset($_POST['bouton_bister']) && $_POST['bouton_bister']=='Valider')
	{
		if(!isset($_POST['bister']))
		{
			$erreurbister='Sélectionner soir BIS ou TER.';
			$hasError=true;
		}
		if(isset($_POST['bister']) && $_POST['bister']!='')
		{
			$valeurbister = $_POST['bister'];
			$new_id = $_POST['id_formate_cache'].'-'.$valeurbister;
			$liste = $dep->getInfosDepartByIdFormate($new_id);
			if(count($liste)>=1)
			{
				$erreurbister='Il existe déjà un '.$new_id;
				$hasError=true;
			}
		}
		
		if(!$hasError && $new_id!='')
		{
			$insert = $dep->addDepart2($new_id,date('Y-m-d'),$_SESSION['id']);
			if($insert)
			{
				header('Location: modifierdepart.php?c='.$insert);
			}
			else
			{
				echo 'erreur';
			}
		}
		else{
			echo 'Il y a des erreurs';
		}
		
	}
	
	
	
	?>
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
	<script type="text/javascript">
	var elementPattern = /^element(\d+)$/;
    var deletePattern = /^delete(\d+)$/;

	function ajouterElement()
    {
        var Conteneur = document.getElementById('conteneur');
        if(Conteneur)
        {
            Conteneur.appendChild(creerElement(dernierElement() + 1))
        }
		var nbfich = parseInt(document.getElementById('nbfichiers').getAttribute('value'));
		document.getElementById('nbfichiers').setAttribute('value',nbfich+1);
    }
	
	function dernierElement()
    {
      var Conteneur = document.getElementById('conteneur'), n = 0;
      if(Conteneur)
      {
        var elementID, elementNo;
        if(Conteneur.childNodes.length > 0)
        {
			for(var i = 0; i < Conteneur.childNodes.length; i++)
			{
				// Ici, on vérifie qu'on peut récupérer les attributs, si ce n'est pas possible, on renvoit false, sinon l'attribut
				elementID = (Conteneur.childNodes[i].getAttribute) ? Conteneur.childNodes[i].getAttribute('id') : false;
				if(elementID)
				{
					elementNo = parseInt(elementID.replace(elementPattern, '$1'));
					if(!isNaN(elementNo) && elementNo > n)
					{
						n = elementNo;
					}
				}
			}
			
			
        }
      }
      return n;
    }
	
    function creerElement(ID)
    {
		var Conteneur = document.createElement('div');
		Conteneur.setAttribute('id', 'element' + ID);
		Conteneur.setAttribute('class', 'piece clear');
		var Input = document.createElement('input');
		Input.setAttribute('type', 'file');
		Input.setAttribute('name', 'pieces[]');
		Input.setAttribute('id', 'pieces' + ID);
		var Delete = document.createElement('input');
		Delete.setAttribute('type', 'button');
		Delete.setAttribute('value', 'Supprimer n°' + ID + ' !');
		Delete.setAttribute('id', 'delete' + ID);
		Delete.onclick = supprimerElement;
		Conteneur.appendChild(Input);
		Conteneur.appendChild(Delete);
		return Conteneur;
    }
	function supprimerElement()
    {
		var Conteneur = document.getElementById('conteneur');
		var n = parseInt(this.id.replace(deletePattern, '$1'));
		if(Conteneur && !isNaN(n))
		{
			var elementID, elementNo;
			if(Conteneur.childNodes.length > 0)
			{
				for(var i = 0; i < Conteneur.childNodes.length; i++)
				{		
					elementID = (Conteneur.childNodes[i].getAttribute) ? Conteneur.childNodes[i].getAttribute('id') : false;
					if(elementID)
					{
						elementNo = parseInt(elementID.replace(elementPattern, '$1'));
						if(!isNaN(elementNo) && elementNo  == n)
						{
							Conteneur.removeChild(Conteneur.childNodes[i]);
							//updateElements(); // A supprimer si tu ne veux pas la màj
							
							//return;
						}
					}
				}
			}
			var nbfich = parseInt(document.getElementById('nbfichiers').getAttribute('value'));
			document.getElementById('nbfichiers').setAttribute('value',nbfich-1);
		}  
		
    }

	function updateElements()
    {
		var Conteneur = document.getElementById('conteneur'), n = 0;
		if(Conteneur)
		{
			var elementID, elementNo;
			if(Conteneur.childNodes.length > 0)
			{
				for(var i = 0; i < Conteneur.childNodes.length; i++)
				{
					elementID = (Conteneur.childNodes[i].getAttribute) ? Conteneur.childNodes[i].getAttribute('id') : false;
					if(elementID)
					{
						elementNo = parseInt(elementID.replace(elementPattern, '$1'));
						if(!isNaN(elementNo))
						{
							n++
							Conteneur.childNodes[i].setAttribute('id', 'element' + n);
							document.getElementById('pieces' + elementNo).setAttribute('id', 'pieces' + n);
							document.getElementById('delete' + elementNo).setAttribute('id', 'delete' + n);
						}
					}
				}
			}	
		}
    }

	function supprimerfichier(numfich)
	{
		document.getElementById('fic_'+numfich).remove();
	}


	</script>
</head>
<body>
	<header>
		<div class="header">
			<h1><a href="index.php">Gestion des courriers départs et arrivés</a></h1>		
			
		</div>
	</header>
<?php
	require_once('nav.php');
?>
<section id="content">
	<?php if(isset($_GET['d'])){echo '<p class="retour"><a href="rechercherdepart.php?d='.$_GET['d'].'">&lt;&lt; Retour aux résultats</a></p>';} else {echo '<p class="retour"><a href="rechercherdepart.php">&lt;&lt; Retour à la recherche</a></p>';} ?>
	
	<h2>Modification d'un courrier départ</h2>
	<p class="indication">Les champs suivis de * sont obligatoires. Par défaut, la valeur de compétence est "Charte".</p>
	<?php if(isset($erreurmsg) && $erreurmsg!='') {echo '<p class="erreur left">'.$erreurmsg.'</p><br/>'; print_r($erreur); }?>
	<?php if($msgok!='') {echo '<p class="msgok left">'.$msgok.'</p><br/>'; }?>
	<form action="" method="post" enctype="multipart/form-data">
		<fieldset>
			<label for="num">N°</label><input type="text" value="<?php echo $infosC[0]['id_formate']; ?>" name="num" id="num" readonly="readonly" /><br/>
			<label for="datec">Date<span class="obligatoire">*</span></label><input type="text" value="<?php if(isset($_POST['datec']) && $_POST['datec']!='') {echo $_POST['datec'];} elseif( isset($infosC[0]['date']) && $infosC[0]['date']!='0000-00-00'){$tab_majdate = explode('-',$infosC[0]['date']); $date_c = $tab_majdate[2].'/'.$tab_majdate[1].'/'.$tab_majdate[0]; echo $date_c; } ?>" name="datec" id="datec" placeholder="jj/mm/aaaa"/><?php if(isset($erreur['datec'])) echo '<p class="erreur left">'.$erreur['datec'].'</p>';?><br/>
			<label for="competence">Compétence<span class="obligatoire">*</span></label><input value="<?php if(isset($_POST['competence']) && $_POST['competence']!='') {echo $_POST['competence'];}else{if($infosC[0]['competence']=='') echo 'Charte'; else echo $infosC[0]['competence'];} ?>" id="competence" name="competence" class="comp"/><?php if(isset($erreur['competence'])) echo '<p class="erreur left">'.$erreur['competence'].'</p>';?><br/>
			<label for="dest">Destinataire<span class="obligatoire">*</span></label><input type="text" value="<?php if(isset($_POST['dest']) && $_POST['dest']!='') {echo htmlspecialchars(stripcslashes($_POST['dest']));} else{echo htmlspecialchars(stripcslashes($infosC[0]['destinataire']));}?>" name="dest" id="dest" /><?php if(isset($erreur['dest'])) echo '<p class="erreur left">'.$erreur['dest'].'</p>';?><br/>	
			<label for="redac">Rédacteur<span class="obligatoire">*</span></label><input type="text" value="<?php if(isset($_POST['redac']) && $_POST['redac']!='') {echo $_POST['redac'];}else{echo $infosC[0]['redacteur'];} ?>" name="redac" id="redac" /><?php if(isset($erreur['redac'])) echo '<p class="erreur left">'.$erreur['redac'].'</p>';?><br/>	
			<label for="nb">Nombre<span class="obligatoire">*</span></label><input type="text" value="<?php if(isset($_POST['nb']) && $_POST['nb']!=0) {echo $_POST['nb'];} else{echo $infosC[0]['nb'];} ?>" name="nb" id="nb" /><?php if(isset($erreur['nb'])) echo '<p class="erreur left">'.$erreur['nb'].'</p>';?><br/>
			<label for="objet">Objet<span class="obligatoire">*</span></label><input type="text" value="<?php if(isset($_POST['objet']) && $_POST['objet']!='') {echo htmlspecialchars(stripcslashes($_POST['objet']));} else{echo htmlspecialchars(stripcslashes($infosC[0]['objet']));}?>" name="objet" id="objet" /><?php if(isset($erreur['objet'])) echo '<p class="erreur left">'.$erreur['objet'].'</p>';?><br/>	
			<div id="conteneur">
				<label>Courier scanné</label>
				<?php 
					if(isset($_POST['hiddenfiles']) && count($_POST['hiddenfiles'])!=0)
					{
						echo '<ul class="liste_res clear">';
						foreach($_POST['hiddenfiles'] as $keyf => $valuef)
						{
							echo '<li id="fic_'.$keyf.'"><a href="uploads/'.$tmp2[$valuef]['lien'].'"  target="_blank">'.$tmp2[$valuef]['lien'].'</a><span onclick="supprimerfichier('.$keyf.');" ><img src="img/delete.png" alt="Supprimer" width="24px"/></span><input type="hidden" name="hiddenfiles[]" value="'.$valuef.'" /></li>';
							
						}
						echo '</ul>';
					}
					elseif(isset($listefichiers) && count($listefichiers)!=0)
					{	
						echo '<ul class="liste_res clear">';
						foreach($listefichiers as $keyf => $valuef)
						{
							echo '<li id="fic_'.$keyf.'"><a href="uploads/'.$valuef['lien'].'" target="_blank">'.$valuef['lien'].'</a><span onclick="supprimerfichier('.$keyf.');" ><img src="img/delete.png" alt="Supprimer" width="24px"/></span><input type="hidden" name="hiddenfiles[]" value="'.$valuef['id_lien'].'" /></li>';
							
						}
						echo '</ul>';
					}
						?>
				<div id="element1" class="piece clear"><input type="file" value="" name="pieces[]" id="pieces1"/><br/></div>
			</div>
			<input type="hidden" id="nbfichiers" name="nbfichiers" value="1" />
			<input type="button" value="Ajouter un fichier" onclick="javascript:ajouterElement();" /><br/>
			
			
			<input type="submit" value="Enregistrer" name="departc"/>
		</fieldset>
	
	</form>
	<br/>
	<?php 
	
	$tab_explode = explode('-',$id_formate);
	$listedep = $dep->getInfosDepartByIdFormate($tab_explode[0].'-'.$tab_explode[1]);
	if(count($listedep)==1)
	{
		if(isset($erreurbister) && $erreurbister!=''){echo '<p class="erreur left">'.$erreurbister.'</p>';}
	?>
	<form method="post" >
		<fieldset>
			<legend>Ajouter un courrier bis</legend>
			<label for="bbis">Bis</label><input type="radio" value="bis" name="bister" id="bbis" /><br/>
			<input type="hidden" value="<?php echo $id_formate;?>" name="id_formate_cache" />
			<input type="submit" value="Valider" name="bouton_bister"/>
		</fieldset>
	</form>
	
	<?php
	}
	else if(count($listedep)==2 /*&& $tab_explode[2]=='bis'*/ ){ 
		if(isset($erreurbister) && $erreurbister!=''){echo '<p class="erreur left">'.$erreurbister.'</p>';}
	?>
		<form method="post" >
		<fieldset>
			<legend>Ajouter un courrier ter</legend>
			<label for="tter">Ter</label><input type="radio" value="ter" name="bister" id="tter" /><br/>
			<input type="hidden" value="<?php echo $tab_explode[0].'-'.$tab_explode[1];?>" name="id_formate_cache" />
			<input type="submit" value="Valider" name="bouton_bister"/>
		</fieldset>
	</form>
	<?php }?>
	
</section>
<script type="text/javascript">

$(document).ready(function() {	
	$("#datec").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true
	});$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );
	var listecompe = ["Charte","Parc","Pays","SCoT","SAGE"];
	$(".comp").autocomplete({
		source : listecompe,
		minLength : 0,
		open: function() {$("ul.ui-menu").width( $(this).innerWidth());}
		
	});

});
</script>
<?php

require_once('footer.php');

?>
