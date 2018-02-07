<?php
class Lien
{
	private $id_lien;
	private $id_depart;
	private $lien;
	
	public function __construct()
	{
		
	}
  
	public function addLien($id_depart,$lien)
	{
		global $bdd;
		
		$q = $bdd->prepare("INSERT INTO lien SET id_depart= :id_depart, lien= :lien");
		$q->bindValue(":id_depart", $id_depart);
		$q->bindValue(":lien", $lien);
		$q->execute();
		$dernierid = $bdd->lastInsertId();
		return $dernierid;
		
	}
	public function upLien($courrier,$lien)
	{
		global $bdd;
		
		$q = $bdd->prepare("UPDATE depart SET lien = :lien WHERE id_depart=:courrier");
		$q->bindValue(":lien", $lien);
		$q->bindValue(":courrier", $courrier);
		$q->execute();

	}
	
	
	public function getLiens($iddepart)
	{
		global $bdd;
		
		$req = $bdd->prepare('SELECT * FROM lien WHERE id_depart = :iddepart');
		$req->bindValue(":iddepart", $iddepart);
		$req->execute();
		$data=$req->fetchAll();
		return $data;
	}
	public function delPiece($idpiece)
	{
		global $bdd;
		
		$q = $bdd->prepare("DELETE FROM lien WHERE id_lien = :piece");
		$q->bindValue(":piece", $idpiece);
		$q->execute();
	}
	public function deleteLien($iddepart)
	{
		global $bdd;
		
		$q = $bdd->prepare("DELETE FROM lien WHERE id_depart = :iddepart");
		$q->bindValue(":iddepart", $iddepart);
		$q->execute();
	}
	
	
	


}

?>