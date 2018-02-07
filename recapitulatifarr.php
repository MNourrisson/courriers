<?php 
	ob_start();
	session_start();
	$_SESSION['page']='recapitulatifarr.php';
	
	$current='recapitulatifarr';
	include_once('classes/connect.php');
	include_once('classes/Arrive.php');
	include_once('classes/PHPExcel.php');

	$arr = new Arrive();
	
	$erreur=array();
	$erreurmsg='';
	$htmltab='';
	if(isset($_POST['recherche']) && $_POST['recherche']=='Rechercher' && $_POST['recherche_annee']!='')
	{
		$listeRes = $arr->getRecap($_POST['recherche_annee']);
		if(count($listeRes)==0)
		{
			$erreurmsg='Aucun résultat trouvé pour '.$dateavant;	
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
									'color'		=> array('rgb' => '993366')
									),
					'font'=>array('color'=>array('rgb'=>'ffffff')),
					'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'wrap'=>TRUE,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER),
					'borders' => array(
										'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
										'top'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
										'left'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
										'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
									)	
				 ));
			$styleLigne = new PHPExcel_Style();
			$styleLigne->applyFromArray(
			array('borders' => array(
										'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
										'top'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
										'left'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
										'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
									),
				'fill' 	=> array(
									'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
									'color'		=> array('rgb' => 'b8f0a2')
									)
				 ));
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(12);
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(46);
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(59);
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(17);
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(15);
			$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('1')->setRowHeight(30);
			$objPHPExcel->setActiveSheetIndex(0)->setSharedStyle($styleTitre, "A".$curseur.":E".$curseur);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$curseur, 'Date');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$curseur, 'Expéditeur');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$curseur, 'Contenu');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$curseur, 'Techniciens destinataires');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$curseur, 'N° de courrier réponse');
			$curseur++;
			
			foreach($listeRes as $cle => $valeur)
			{
							
				$date_ligne=$valeur['date']; $arr1 = explode('-',$date_ligne); $date_ligne=$arr1[2].'/'.$arr1[1].'/'.$arr1[0];
				
				if(((int)$arr1[2] % 2)==0)	
				{
					$objPHPExcel->setActiveSheetIndex(0)->setSharedStyle($styleLigne, "A".$curseur.":E".$curseur);
				}
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$curseur, $date_ligne);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$curseur, stripcslashes($valeur['expediteur']));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$curseur, stripcslashes($valeur['contenu']));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$curseur, $valeur['techniciens']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$curseur, $valeur['id_courrier_reponse']);
				$curseur++;
			}		
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('recap/recapitulatif_arrive_'.$annee.'.xlsx');
			unset($objPHPExcel);
		
			
			$htmltab = '<div id="tableau"><table>';
			$htmltab.='<thead><tr><th id="th1" class="dateligne" style="width: 85px">Date</th><th id="th2" class="exp" style="width: 340px">Expéditeur</th><th id="th3" class="contenu" style="width: 364px">Contenu</th><th id="th4" class="tech" style="width: 93px">Techniciens destinataires</th><th id="th5" class="idformate" style="width: 96px">N° de courrier réponse</th></th></thead>';
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
			
				$htmltab.='<tr class="'.$couleur.'"><td headers="th1" class="dateligne" style="width: 82px">'.$date_ligne.'</td><td headers="th2" class="exp" style="width: 330px">'.stripcslashes($valeur['expediteur']).'</td><td headers="th3" class="contenu" style="width: 353px">'.stripcslashes($valeur['contenu']).'</td><td headers="th4" class="tech" style="width: 93px">'.$valeur['techniciens'].'</td><td headers="th5" class="idformate" style="width: 96px">'.stripcslashes($valeur['id_courrier_reponse']).'</td></tr>';
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
	<h2>Recapitulatif courrier arrivé</h2>
	<?php 
		//echo '<pre>'; print_r($listeRes); echo '</pre>';
		if(strlen($htmltab)==0)
		{
		
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
					echo '<p class="resultat"><a href="recap/recapitulatif_arrive_'.$annee.'.xlsx"><img src="../img/excel.png" />Récapitulatif '.$annee.' (fichier excel)</a></p>';
				}
			}?>
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
