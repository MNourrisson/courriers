<?php
class Depart
{
	private $id_depart;
	private $id_formate;
	private $date;
	private $destinataire;
	private $redacteur;
	private $nb;
	private $objet;
	private $id_personne;
	private $competence;
	
	public function __construct()
	{
		
	}
  
	public function addDepart($datec,$dest,$redac,$nb,$obj,$id_pers,$competence)
	{
		global $bdd;
		$year = date('Y');
		$q = $bdd->prepare("SELECT * FROM depart WHERE id_formate like '%$year-%' order by id_formate DESC, id_depart DESC LIMIT 1");
		$q->execute();
		$data=$q->fetch();
		if($data!=0)
		{
			$dernieridformate = $data['id_formate'];
			$tabidf = explode('-',$dernieridformate);
			$nouveauidf = $tabidf[1]+1;
			$id_formate = date('Y').'-'.str_pad($nouveauidf,4,"0",STR_PAD_LEFT);
		}
		else
		{
			$id_formate=date('Y').'-0001';
		}
		$q = $bdd->prepare("INSERT INTO depart SET id_formate = :idf, date= :datec, destinataire= :dest, redacteur = :redac, nb = :nb, objet = :obj, id_personne = :id_pers, competence= :competence");
		$q->bindValue(":idf", $id_formate);
		$q->bindValue(":datec", $datec);
		$q->bindValue(":dest", $dest);
		$q->bindValue(":redac", $redac);
		$q->bindValue(":nb", $nb);
		$q->bindValue(":obj", $obj);
		$q->bindValue(":id_pers", $id_pers);
		$q->bindValue(":competence", $competence);
		$q->execute();
		

		/*$dernierid = $bdd->lastInsertId();
		$q = $bdd->prepare("SELECT COUNT(*) as nbr FROM depart WHERE YEAR(date)=YEAR(NOW())");
		$q->execute();
		$data=$q->fetch();
		$compte = $data['nbr']+2;
		$id_formate = date('Y').'-'.str_pad($compte,4,"0",STR_PAD_LEFT);
		$q = $bdd->prepare("UPDATE depart SET id_formate = :id_formate WHERE id_depart= :dernierid");
		$q->bindValue(":id_formate", $id_formate);
		$q->bindValue(":dernierid", $dernierid);
		$q->execute();*/
		return array( $data['id_depart'],$id_formate,$datec,$dest,$obj);

	}
	public function addDepart2($iden,$datec,$id_pers)
	{
		global $bdd;
		
		$q = $bdd->prepare("INSERT INTO depart SET id_formate = :iden, date= :datec, id_personne = :id_pers");
		$q->bindValue(":iden", $iden);
		$q->bindValue(":datec", $datec);
		$q->bindValue(":id_pers", $id_pers);
		$q->execute();
		$dernierid = $bdd->lastInsertId();
		return $dernierid;
		
	}
	public function upDepart($id,$datec,$dest,$redac,$nb,$obj,$id_pers,$competence)
	{
		global $bdd;
		
		$q = $bdd->prepare("UPDATE depart SET date = :datec, destinataire = :dest, redacteur = :redac,nb = :nb, objet = :obj, id_personne = :id_pers, competence =:competence WHERE id_depart=:id");
		$q->bindValue(":datec", $datec);
		$q->bindValue(":dest", $dest);
		$q->bindValue(":redac", $redac);
		$q->bindValue(":nb", $nb);
		$q->bindValue(":obj", $obj);
		$q->bindValue(":id_pers", $id_pers);
		$q->bindValue(":id", $id);
		$q->bindValue(":competence", $competence);
		$q->execute();

	}
	
	public function getInfosDepart($iddepart)
	{
		global $bdd;
		
		$req = $bdd->prepare('SELECT * FROM depart WHERE id_depart = :iddepart');
		$req->bindValue(":iddepart", $iddepart);
		$req->execute();
		$data=$req->fetchAll();
		return $data;
	}
	
	public function getRecherche($annee)
	{
		global $bdd;
		
		$req = $bdd->prepare("SELECT * FROM depart WHERE date = :annee ORDER BY id_formate");
		$req->bindValue(":annee", $annee);
		$req->execute();
		$data=$req->fetchAll();
		return $data;
	}
	public function getRechercheNum($numcourriers)
	{
		global $bdd;
		
		$req = $bdd->prepare("SELECT * FROM depart WHERE id_formate LIKE :numcourriers ORDER BY id_formate");
		$req->bindValue(":numcourriers", '%'.$numcourriers.'%');
		$req->execute();
		$data=$req->fetchAll();
		return $data;
	}
	
	public function getRecap($annee)
	{
		global $bdd;
		
		$req = $bdd->prepare("SELECT * FROM depart WHERE YEAR(date) = :annee AND nb!=0 ORDER BY id_formate DESC");
		$req->bindValue(":annee", $annee);
		$req->execute();
		$data=$req->fetchAll();
		return $data;
	}
	public function deleteFiche($idcourrier)
	{
		global $bdd;
		
		$req = $bdd->prepare("DELETE FROM depart WHERE id_depart = :iddepart");
		$req->bindValue(":iddepart", $idcourrier);
		$req->execute();
	}

	public function getInfosDepartByIdFormate($id)
	{
		global $bdd;
		
		$req = $bdd->prepare('SELECT * FROM depart WHERE id_formate LIKE :id');
		$req->bindValue(":id", '%'.$id.'%');
		$req->execute();
		$data=$req->fetchAll();
		return $data;
	}
}

?>