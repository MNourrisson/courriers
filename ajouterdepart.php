<?php 
	session_start();
	$_SESSION['page']='ajouterdepart.php';
	//print_r($_COOKIE); die();
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
	
	$current = 'ajouterdepart';
	include_once('classes/connect.php');
	include_once('classes/Depart.php');
	$dep = new Depart();
	$insert = array();
	$erreur=array();
	$erreurmsg='';
	if(isset($_POST['departc']) && $_POST['departc']=='Enregistrer' )
	{
		$erreur=array();
		foreach($_POST['datec'] as $kd => $vd)
		{
			if($vd=='')
			{
				$erreur['datec'][$kd]='erreur';
			}
			
		}
		foreach($_POST['dest'] as $kd => $vd)
		{
			if($vd=='')
			{
				$erreur['dest'][$kd]='erreur';
			}
			
		}
		foreach($_POST['redac'] as $kd => $vd)
		{
			if($vd=='')
			{
				$erreur['redac'][$kd]='erreur';
			}
			
		}
		foreach($_POST['nb'] as $kd => $vd)
		{
			if($vd=='')
			{
				$erreur['nb'][$kd]='erreur';
			}
			
		}
		foreach($_POST['objet'] as $kd => $vd)
		{
			if($vd=='')
			{
				$erreur['objet'][$kd]='erreur';
			}
			
		}
		$tabcompetences = array('Charte'=>'Charte','Parc'=>'Parc','Pays'=>'Pays','SAGE'=>'SAGE','SCoT'=>'SCoT');
		foreach($_POST['competence'] as $kd => $vd)
		{
			if(!array_key_exists($vd,$tabcompetences))
			{
				$erreur['competence'][$kd]='erreur';
			}
			
		}
		if(count($erreur)!=0)
		{
			$erreurmsg='Il y a des erreur(s). Merci de vérifier les données rentrées.';
		}
		else
		{	
			$insert = array();
			for($j=0; $j<=$_POST['nblignes'];$j++)
			{
				$dateavant=$_POST['datec'][$j];
				$array_majdate = explode('/',$dateavant); 
				$datetranformee=$array_majdate[2].'-'.$array_majdate[1].'-'.$array_majdate[0]; 
				//retourne un tableau $id => $idformate.
				
				$insert[] = $dep->addDepart($datetranformee,addslashes($_POST['dest'][$j]),$_POST['redac'][$j],$_POST['nb'][$j],addslashes($_POST['objet'][$j]),$_SESSION['id'],$_POST['competence'][$j]);
			
			
				if($insert!=0)
				{
					//header('Location: ajouterdepart.php');
				}
			}
		}
		//print_r($_POST);
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

	function ajouterElement()
    {
        var Conteneur = document.getElementById('conteneur');
        if(Conteneur)
        {
            Conteneur.appendChild(creerElement(dernierElement() + 1))
        }
		var nbligne = parseInt(document.getElementById('nblignes').getAttribute('value'));
		document.getElementById('nblignes').setAttribute('value',nbligne+1);
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
var d = new Date();
var twoDigitMonth = ("0" + (d.getMonth() + 1)).slice(-2);
var twoDigitDay = ("0" + d.getDate()).slice(-2);

    function creerElement(ID)
    {
		var Conteneur = document.createElement('li');
		Conteneur.setAttribute('id', 'element' + ID);
		Conteneur.setAttribute('class', 'clear');
		var ulelem = document.createElement('ul');
		var lidate = document.createElement('li');
		lidate.setAttribute('class','classdate');
		var inputdate = document.createElement('input');
		inputdate.setAttribute('type','text');
		inputdate.setAttribute('name','datec[]');
		inputdate.setAttribute('id','datec'+ID);
		inputdate.setAttribute('value',twoDigitDay+'/'+twoDigitMonth+'/'+d.getFullYear());
		inputdate.setAttribute('class','datecourrier');
		var licompetence = document.createElement('li');
		licompetence.setAttribute('class','classcompetence');
		var inputcompetence = document.createElement('input');
		inputcompetence.setAttribute('type','text');
		inputcompetence.setAttribute('name','competence[]');
		inputcompetence.setAttribute('id','competence'+ID);
		inputcompetence.setAttribute('value','Charte');
		inputcompetence.setAttribute('class','comp');
		var lidest = document.createElement('li');
		var inputdest = document.createElement('input');
		inputdest.setAttribute('type','text');
		inputdest.setAttribute('name','dest[]');
		inputdest.setAttribute('id','dest'+ID);
		var liredac = document.createElement('li');
		liredac.setAttribute('class','redaccourrier');
		var inputredac = document.createElement('input');
		inputredac.setAttribute('type','text');
		inputredac.setAttribute('name','redac[]');
		inputredac.setAttribute('id','redac'+ID);
		var linb = document.createElement('li');
		linb.setAttribute('class','classnb');
		var inputnb = document.createElement('input');
		inputnb.setAttribute('type','text');
		inputnb.setAttribute('name','nb[]');
		inputnb.setAttribute('id','nb'+ID);
		inputnb.setAttribute('value','1');
		var liobjet = document.createElement('li');
		liobjet.setAttribute('class','objet');
		var inputobjet = document.createElement('input');
		inputobjet.setAttribute('type','text');
		inputobjet.setAttribute('name','objet[]');
		inputobjet.setAttribute('id','objet'+ID);	
		inputobjet.setAttribute('class','objetcourrier');	
		/*var libister = document.createElement('li');
		libister.setAttribute('class','terbis');
		var spanbister = document.createElement('span');
		var inputbis = document.createElement('input');
		inputbis.setAttribute('type','checkbox');
		inputbis.setAttribute('name','bister[]');
		inputbis.setAttribute('id','bbis'+ID);
		var labelbis=document.createElement('label');
		labelbis.setAttribute('for','bbis'+ID);
		labelbis.innerHTML='Bis';
		var labelter=document.createElement('label');
		labelter.setAttribute('for','tter'+ID);
		labelter.innerHTML='Ter';
		var inputter = document.createElement('input');
		inputter.setAttribute('type','checkbox');
		inputter.setAttribute('name','bister[]');
		inputter.setAttribute('id','tter'+ID);*/
		
		var lisuppr = document.createElement('li');
		var inputsuppr = document.createElement('input');
		inputsuppr.setAttribute('type','image');
		inputsuppr.setAttribute('src','img/delete.png');
		inputsuppr.setAttribute('value','Suppr');
		inputsuppr.setAttribute('id','suppr'+ID);
		inputsuppr.setAttribute('onclick','javascript:supprimerElement('+ID+')');
		inputsuppr.setAttribute('class','boutonsuppr');

		Conteneur.appendChild(ulelem);
		ulelem.appendChild(lidate);
		lidate.appendChild(inputdate);
		ulelem.appendChild(licompetence);
		licompetence.appendChild(inputcompetence);
		ulelem.appendChild(lidest);
		lidest.appendChild(inputdest);
		ulelem.appendChild(liredac);
		liredac.appendChild(inputredac);
		ulelem.appendChild(linb);
		linb.appendChild(inputnb);
		ulelem.appendChild(liobjet);
		liobjet.appendChild(inputobjet);
		
		/*ulelem.appendChild(libister);
		libister.appendChild(spanbister);
		spanbister.appendChild(inputbis);
		spanbister.appendChild(labelbis);
		libister.appendChild(spanbister);
		spanbister.appendChild(inputter);
		spanbister.appendChild(labelter);*/
		ulelem.appendChild(lisuppr);
		lisuppr.appendChild(inputsuppr);
		return Conteneur;
    }
	function supprimerElement(numelem)
	{
		document.getElementById('element'+numelem).remove();
		var nbligne = parseInt(document.getElementById('nblignes').getAttribute('value'));
		document.getElementById('nblignes').setAttribute('value',nbligne-1);
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
	<?php 
		if(count($insert)==0)
		{
			echo '<h2>Ajout courrier départ</h2><p class="indication">Les champs suivis de * sont obligatoires. Par défaut la compétence est "Charte".</p>';
			if(isset($erreurmsg) && $erreurmsg!='') echo '<p class="erreur left">'.$erreurmsg.'</p><br/>'; 
			?>
	<form action="" method="post">
		<fieldset>
			<ul id="conteneur">
				<li>
					<ul>
						<li class="classdate"><label for="datec">Date<span class="obligatoire">*</span></label></li>
						<li class="classcompetence"><label for="competence">Compétence<span class="obligatoire">*</span></label></li>
						<li><label for="dest">Destinataire<span class="obligatoire">*</span></label></li>
						<li class="redaccourrier" ><label for="redac">Rédacteur<span class="obligatoire">*</span></label></li>
						<li class="classnb"><label for="nb">Nb<span class="obligatoire">*</span></label></li>
						<li class="objet"><label for="objet">Objet<span class="obligatoire">*</span></label></li>
						<!--li class="terbis"><label for="bister">Bis/Ter</label></li-->
						<li></li>
					</ul>
				</li>
				<li id="element1" class="clear">
					<ul>
						<li class="classdate"><input type="text" value="<?php if(isset($_POST['datec'][0]) && $_POST['datec'][0]!='') {echo $_POST['datec'][0];} else echo date('d/m/Y');?>" name="datec[]" id="datec1" class="datecourrier" /></li>
						<li class="classcompetence"><input type="text" value="<?php if(isset($_POST['competence'][0]) && $_POST['competence'][0]!='') {echo $_POST['competence'][0];} else{echo "Charte";} ?>" name="competence[]" id="competence1" class="comp" /></li>
						<li <?php if(isset($erreur['dest'][0])) echo 'class="erreurtab"';?>><input type="text" value="<?php if(isset($_POST['dest'][0]) && $_POST['dest'][0]!='') {echo $_POST['dest'][0];} ?>" name="dest[]" id="dest1" /></li>					
						<li class="redaccourrier <?php if(isset($erreur['redac'][0])) echo 'erreurtab';?>"><input type="text" value="<?php if(isset($_POST['redac'][0]) && $_POST['redac'][0]!='') {echo $_POST['redac'][0];} ?>" name="redac[]" id="redac1" class="redaccourrier" /></li>
						<li class="classnb <?php if(isset($erreur['nb'][0])) echo 'erreurtab';?>"><input type="text" value="<?php if(isset($_POST['nb'][0]) && $_POST['nb'][0]!=0) {echo $_POST['nb'][0];} else{ echo '1';} ?>" name="nb[]" id="nb1" /></li>
						<li class="objet <?php if(isset($erreur['objet'][0])) echo 'erreurtab';?>"><input type="text" value="<?php if(isset($_POST['objet'][0]) && $_POST['objet'][0]!='') {echo $_POST['objet'][0];}?>" name="objet[]" id="objet1" class="objetcourrier" /></li>
						<!--li class="terbis"><span><input type="checkbox" value="bis" name="bister[]" id="bbis1"/><label for="bbis1">Bis</label></span><span><input type="checkbox" value="ter" name="bister[]" id="tter1"/><label for="tter1">Ter</label></span></li-->
						<li></li>
					</ul>
				</li>		
			<?php
				if(isset($_POST['nblignes']) && $_POST['nblignes'] >0)
				{
					for($i=1;$i<=$_POST['nblignes'];$i++)
					{
						$num = $i+1;
						?>
						<li id="element<?php echo $num;?>" class="clear">
							<ul>
								<li class="classdate"><input type="text" value="<?php if(isset($_POST['datec'][$i]) && $_POST['datec'][$i]!='') {echo $_POST['datec'][$i];}else echo date('d/m/Y'); ?>" name="datec[]" id="datec<?php echo $num; ?>" placeholder="jj/mm/aaaa" class="datecourrier" /></li>
								<li class="classcompetence"><input type="text" value="<?php if(isset($_POST['competence'][0]) && $_POST['competence'][0]!='') {echo $_POST['competence'][0];} ?>" name="competence[]" id="competence<?php echo $num; ?>" class="comp" /></li>
								<li <?php if(isset($erreur['dest'][$i])) echo 'class="erreurtab"';?>><input type="text" value="<?php if(isset($_POST['dest'][$i]) && $_POST['dest'][$i]!='') {echo $_POST['dest'][$i];} ?>" name="dest[]" id="dest<?php echo $num; ?>" /></li>
								<li <?php if(isset($erreur['redac'][$i])) echo 'class="erreurtab"';?>><input type="text" value="<?php if(isset($_POST['redac'][$i]) && $_POST['redac'][$i]!='') {echo $_POST['redac'][$i];} ?>" name="redac[]" id="redac<?php echo $num; ?>"  class="redaccourrier" /></li>
								<li class="classnb <?php if(isset($erreur['nb'][$i])) echo 'erreurtab';?>"><input type="text" value="<?php if(isset($_POST['nb'][$i]) && $_POST['nb'][$i]!=0) {echo $_POST['nb'][$i];} else{ echo '1';} ?>" name="nb[]" id="nb<?php echo $num; ?>" /></li>
								<li  class="objet <?php if(isset($erreur['objet'][$i])) echo 'erreurtab';?>"><input type="text" value="<?php if(isset($_POST['objet'][$i]) && $_POST['objet'][$i]!='') {echo $_POST['objet'][$i];}?>" name="objet[]" id="objet<?php echo $num; ?>" class="objetcourrier" /></li>
								<!--li class="terbis"><span><input type="checkbox" value="bis" name="bister[]" id="bbis1"/><label for="bbis1">Bis</label></span><span><input type="checkbox" value="ter" name="bister[]" id="tter1"/><label for="tter1">Ter</label></span></li-->
								<li><input type="image" src="img/delete.png" value="Suppr" onclick="javascript:supprimerElement(<?php echo $num;?>);" id="suppr<?php echo $num;?>" class="boutonsuppr"/></li>
							</ul>
						</li>
						<?php	
					}
				}
			?>
			</ul>
			<input type="hidden" id="nblignes" name="nblignes" value="<?php if(isset($_POST['nblignes']) && $_POST['nblignes']!=0) echo $_POST['nblignes']; else echo '0'; ?>" />
			<input type="button" value="Ajouter une ligne" onclick="javascript:ajouterElement();" id="ajout" /><br/>
			<input type="submit" value="Enregistrer" name="departc"/>
		</fieldset>
	
	</form>
		<?php }
		else{
			echo '<h2>Liste des courriers</h2><p class="indication">Les courriers ont bien été enregistrés</p><ul>';
			foreach($insert as $cle => $valeur)
			{
				echo '<li>'.$valeur[1].' : '.htmlspecialchars(stripcslashes($valeur[3])).' '.htmlspecialchars(stripcslashes($valeur[4])).'</li>';
				
			}
			echo '</ul>';
		}
		?>
</section>
<script type="text/javascript">

$(document).ready(function() {	
	$(".datecourrier").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true
	});$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );

	$(".objetcourrier").autocomplete({
		source : 'listedep.php',
		minLength : 3,
		open: function() {$("ul.ui-menu").width( $(this).innerWidth());}
		
	});
	var listecompe = ["Charte","Parc","Pays","SCoT","SAGE"];
	$(".comp").autocomplete({
		source : listecompe,
		minLength : 0,
		open: function() {$("ul.ui-menu").width( $(this).innerWidth());}
		
	});
	$('#ajout').bind('click',function() {
		$(".datecourrier").datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true
		});$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );

		$(".objetcourrier").autocomplete({
			source : 'listedep.php',
			minLength : 3,
			open: function() {$("ul.ui-menu").width( $(this).innerWidth());}
			
		});
		$(".comp").autocomplete({
		source : listecompe,
		minLength : 0,
		open: function() {$("ul.ui-menu").width( $(this).innerWidth());}
		
	});
	});
});
</script>
<?php

require_once('footer.php');

?>
