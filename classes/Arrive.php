<?php
class Arrive
{
	private $id_arrive;
	private $date;
	private $expediteur;
	private $contenu;
	private $techniciens;
	private $id_courrier_reponse;
	private $id_personne;
	
	public function __construct()
	{
		
	}
  
	public function addArrive($datec,$exp,$contenu,$tech,$id_pers)
	{
		global $bdd;
		
		$q = $bdd->prepare("INSERT INTO arrive SET date= :datec, expediteur= :exp, contenu = :contenu, techniciens = :tech, id_personne = :id_pers");
		$q->bindValue(":datec", $datec);
		$q->bindValue(":exp", $exp);
		$q->bindValue(":contenu", $contenu);
		$q->bindValue(":tech", $tech);
		$q->bindValue(":id_pers", $id_pers);
		$q->execute();
		
		$dernierid = $bdd->lastInsertId();
		return $dernierid;

	}

	public function upArrive($id,$datec,$exp,$contenu,$tech,$courrier_rep,$id_pers)
	{
		global $bdd;
		
		$q = $bdd->prepare("UPDATE arrive SET date = :datec, expediteur = :exp, contenu = :contenu,techniciens = :tech, id_courrier_reponse= :courrier_rep, id_personne = :id_pers WHERE id_arrive=:id");
		$q->bindValue(":datec", $datec);
		$q->bindValue(":exp", $exp);
		$q->bindValue(":contenu", $contenu);
		$q->bindValue(":tech", $tech);
		$q->bindValue(":courrier_rep", $courrier_rep);
		$q->bindValue(":id_pers", $id_pers);
		$q->bindValue(":id", $id);
		$q->execute();

	}
	
	public function getInfosArrive($idarrive)
	{
		global $bdd;
		
		$req = $bdd->prepare('SELECT * FROM arrive WHERE id_arrive = :idarrive');
		$req->bindValue(":idarrive", $idarrive);
		$req->execute();
		$data=$req->fetchAll();
		return $data;
	}
	
	
	public function getRecherche($annee)
	{
		global $bdd;
		
		$req = $bdd->prepare("SELECT * FROM arrive WHERE date = :annee");
		$req->bindValue(":annee", $annee);
		$req->execute();
		$data=$req->fetchAll();
		return $data;
	}
	public function getInfosIdCourrier($idrep)
	{
		global $bdd;
		$req = $bdd->prepare("SELECT count(*) as nb FROM depart WHERE id_formate =:idrep");
		$req->bindValue(":idrep", $idrep);
		$req->execute();
		$data=$req->fetch();
		return $data;
	}
	
	public function getRecap($annee)
	{
		global $bdd;
		
		$req = $bdd->prepare("SELECT * FROM arrive WHERE YEAR(date) = :annee ORDER BY date DESC, id_arrive DESC");
		$req->bindValue(":annee", $annee);
		$req->execute();
		$data=$req->fetchAll();
		return $data;
	}
	public function deleteFiche($idcourrier)
	{
		global $bdd;
		
		$req = $bdd->prepare("DELETE FROM arrive WHERE id_arrive = :idarrive");
		$req->bindValue(":idarrive", $idcourrier);
		$req->execute();
	}
}

?>