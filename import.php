<?php 

	include_once('classes/connect.php');
	include_once('classes/Arrive.php');
	$arr = new Arrive();
	
	require_once('head.php');
	

	$row = 1;
	if (($handle = fopen("arrivee.csv", "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
			$dateavant=$data[0];
			$array_majdate = explode('/',$dateavant); 
			$datetranformee=$array_majdate[2].'-'.$array_majdate[1].'-'.$array_majdate[0]; 
			
			$arr->addArrive($datetranformee,addslashes(utf8_encode($data[1])),addslashes(utf8_encode($data[2])),$data[3],1);
			
			$row++;
		}
		fclose($handle);
	}
		
	
/*	
	for($j=0; $j<=$_POST['nblignes'];$j++)
			{
				$dateavant=$_POST['datec'][$j];
				$array_majdate = explode('/',$dateavant); 
				$datetranformee=$array_majdate[2].'-'.$array_majdate[1].'-'.$array_majdate[0]; 
				
				$insert = $dep->addDepart($datetranformee,addslashes($_POST['dest'][$j]),$_POST['redac'][$j],$_POST['nb'][$j],addslashes($_POST['objet'][$j]),$_SESSION['id']);
				header('Location: ajouterdepart.php');
				
			}
	
	*/

	require_once('footer.php');
?>
