<?php 
	ob_start();
	session_start();
	$_SESSION['page']='recapitulatifdep.php';	
	$current='recapitulatifdep';
	include_once('classes/connect.php');
	include_once('classes/Depart.php');
	include_once('classes/Lien.php');
	include_once('classes/PHPExcel.php');

	$dep = new Depart();
	$lien = new Lien();
	$erreur=array();
	$erreurmsg='';
	$htmltab='';
	if(isset($_POST['recherche']) && $_POST['recherche']=='Rechercher' && $_POST['recherche_annee']!='')
	{
		$listeRes = $dep->getRecap($_POST['recherche_annee']);
		if(count($listeRes)==0)
		{
			$erreurmsg='Aucun résultat trouvé pour '.$_POST['recherche_annee'];	
		}
		else
		{	
			$annee=$_POST['recherche_annee'];
			$curseur=1;
			$prix=0;
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setCreator("Mélanie Nourrisson")
					->setLastModifiedBy("Mélanie Nourrisson")
					->setTitle("Récapitulatif ".$annee)
					->setSubject("Récapitulatif ".$annee);
					$styleTitre = new PHPExcel_Style();
			$styleTitre->applyFromArray(
			array('fill' 	=> array(
									'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
									'color'		=> array('rgb' => 'ccff66')
									),
					'font'=>array('color'=>array('rgb'=>'7030a0')),
					'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'wrap'=>TRUE,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER),
					'borders' => array(
										'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
										'top'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
										'left'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
										'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
									)	
				 ));
			
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(10);//num
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(11);//date
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(11);//competence
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(45);//destinataires
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(12);//redac
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(5);//nb
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(52);//objet
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(35);//courrier scanné
			$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('1')->setRowHeight(35);
			$objPHPExcel->setActiveSheetIndex(0)->setSharedStyle($styleTitre, "A".$curseur.":H".$curseur);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$curseur, 'N°');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$curseur, 'Date');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$curseur, 'Compétences');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$curseur, 'Destinataire');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$curseur, 'Rédacteur');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$curseur, 'Nb');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$curseur, 'Objet');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$curseur, 'Courrier scanné');
			$curseur++;
			
			foreach($listeRes as $cle => $valeur)
			{
							
				$date_ligne=$valeur['date']; $arr1 = explode('-',$date_ligne); $date_ligne=$arr1[2].'/'.$arr1[1].'/'.$arr1[0];
				
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$curseur, $valeur['id_formate']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$curseur, $date_ligne);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$curseur, $valeur['competence']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$curseur, stripcslashes($valeur['destinataire']));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$curseur, $valeur['redacteur']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$curseur, $valeur['nb']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$curseur, stripcslashes($valeur['objet']));
				$listelien = $lien->getLiens($valeur['id_depart']);
				if(count($listelien)!=0)
				{
					$tabLettres=array('H','I','J','K','L','M','N');
					foreach($listelien as $k => $v)
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tabLettres[$k].$curseur, $v['lien']);
						$objPHPExcel->setActiveSheetIndex(0)->getCell($tabLettres[$k].$curseur)->getHyperlink()->setUrl('http://votrelienvers/uploads/'.$v['lien']);
					}
					
				}
				$curseur++;
			}		
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('recap/recapitulatif_depart_'.$annee.'.xlsx');
			unset($objPHPExcel);
			
			
			$htmltab = '<div id="tableau" class="depart"><table>';
			$htmltab.='<thead><tr><th class="idformate">N°</th><th class="dateligne">Date</th><th class="comp">Compétence</th><th class="desti">Destinataire</th><th class="redac">Rédacteur</th><th class="nombre">Nb</th><th class="obj">Objet</th><th class="links">Courrier scanné</th></th></thead>';
			$htmltab.='<tbody>';
		
			foreach($listeRes as $cle => $valeur)
			{
							
				$date_ligne=$valeur['date']; $arr1 = explode('-',$date_ligne); $date_ligne=$arr1[2].'/'.$arr1[1].'/'.$arr1[0];
				if(((int)$cle % 2)==0)	
				{
					$couleur='identique';
				}
				else
				{
					$couleur='differente';
				}
				$listelien = $lien->getLiens($valeur['id_depart']);
				if(count($listelien)!=0)
				{
					$link='<ul>';
					foreach($listelien as $k => $v)
					{
						$link.='<li><a href="http://votrelienvers/uploads/'.$v['lien'].'" target="_blank">'.$v['lien'].'</a></li>';
					}
					$link.='</ul>';
				}
				else
				{
					$link='';
				}
				$htmltab.='<tr class="'.$couleur.'"><td class="idformate">'.$valeur['id_formate'].'</td><td class="dateligne">'.$date_ligne.'</td><td class="comp">'.$valeur['competence'].'</td><td class="desti">'.stripcslashes($valeur['destinataire']).'</td><td class="redac">'.$valeur['redacteur'].'</td><td class="nombre">'.$valeur['nb'].'</td><td class="obj">'.stripcslashes($valeur['objet']).'</td><td class="links">'.$link.'</td></tr>';
			}	
			$htmltab.='</tbody></table></div>';
			
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
	<h2>Récapitulatif courrier départ</h2>
	<?php 
		//echo '<pre>'; print_r($listeRes); echo '</pre>';
		if(strlen($htmltab)==0)
		{
			if($erreurmsg!='')
			{
				echo '<p class="erreur">'.$erreurmsg.'</p>';
			}
	?>
	<p class="indication">Les champs suivis de * sont obligatoires.</p>
	<form action="" method="post">
		<fieldset>
			<label for="recherche_annee">Année<span class="obligatoire">*</span></label><input type="text" value="<?php if(isset($_POST['recherche_annee']) && $_POST['recherche_annee']!='') {echo $_POST['recherche_annee'];} else {echo date('Y');}?>" name="recherche_annee" id="recherche_annee" maxlength="4" /><?php if(isset($erreur['recherche_annee'])) echo '<p class="erreur left">'.$erreur['recherche_annee'].'</p>';?><br/>
			<input type="submit" value="Rechercher" name="recherche"/>
		</fieldset>
	
	</form>
	<?php
		}else
		
		{
			if(isset($listeRes))
			{
				if($erreurmsg!='')
				{
					echo '<p class="erreur">'.$erreurmsg.'</p>';
				}
				else
				{	
					echo '<p class="resultat"><a href="recap/recapitulatif_depart_'.$annee.'.xlsx"><img src="../img/excel.png" />Récapitulatif '.$annee.' (fichier excel)</a></p>';
				}
			}
			?>
				<form action="#">
				<input value="" placeholder="Rechercher" id="moninput"/>
				</form>
			
			<?php
			
			echo $htmltab;
			
		}
	?>
</section>
<script type="text/javascript" >
$(document).ready(function() {	
	//$("#tableau").scrollTop($("#tableau table").height());

	$('input#moninput').quicksearch('table tbody tr');
});
</script>
<?php

require_once('footer.php');

?>
