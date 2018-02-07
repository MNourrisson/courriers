<?php 

class Personne
{
	private $id_personne;
	private $nom;
	private $mail;
	private $mdp;
	private $droit;
	
	public function __construct()
	{
		
	}
	public function identification($mail)
	{
		global $bdd;
		$query=$bdd->prepare('SELECT id_personne, mdp, mail,droit FROM personne WHERE mail = :mail');
		$query->bindValue(':mail',$mail, PDO::PARAM_STR);
		$query->execute();
		$data=$query->fetchAll();
		return $data;
	}
	
	
}		
?>