Nom du projet : Courriers

Auteur : Mélanie Nourrisson - Parc naturel régional Livradois-Forez

Description du projet :
Gestion des courriers (arrivé et départ) en interne (service non accessible depuis l'extérieur).
Seuls les personnes ayant un compte peuvent enregistrer des courriers. Les autres ont accès aux différents récapitulatifs.
Pour les courriers départs, génération d'un numéro lors de l'ajout. Possibilité de lier un scan du courrier envoyé. Possibilité de faire des courriers bis et ter. Possibilité de modification.
Pour les courriers arrivés, listing des courriers arrivés.
Possibilité d'avoir un récapitulatif pour chaque type de courriers en fonction de l'année. Export Excel

Installation :
Utiliser le script fourni pour créer la base de données (sql/structure_courriers.sql)
Créer directement en base un utilisateur, utilisé sha1 pour crypter le mot de passe et droit à 1. Attention, en cas d'utilisation sur un réseau non interne, prévoir une modification pour la sécurité du mot de passe.
Compléter le fichier de connexion avec vos identifiants (classes/connect.php)
Modifier la liste des compétences (ajouterdepart.php, modifierdepart.php)
Modifier modifier la fin de l'email avec celui de votre structure (connexion.php)
Modifier le lien (recapitulatifdep.php)
Changer les liens d'uploads (modifierdepart.php)

Utilisation :
Pour les personnes possédant un accès :
1. Se connecter
2. Choisir un type de courrier (arrivés ou départs) en fonction du besoin
	Choix courrier arrivé
	* Ajouter
		- Possibilité de traiter plusieurs courriers à la fois en ajoutant d'autres lignes soit avec le bouton prévu à cet usage soit avec la touche entrer du clavier. La touche entrer ne permet pas la validation du formulaire. Pour bien enregistrer les courriers, il faudra valider avec le bouton.
	* Modifier
		- Recherche par date d'arrivée du courrier.
		
	Choix courrier départ
	* Ajouter
		- Possibilité de créer plusieurs numéros courriers. Attention, la touche entrer valide le formulaire (contrairement au formulaire du courrier arrivé). Après validation, pour chaque courrier demandé, un numéro est généré (ex: 2018-0001)
	* Modifier
		- Recherche par numéro courrier
		- Possibilité de rattacher des fichiers.
	


Pour la simple consultation :
1. Choisir un type de courrier (arrivés ou départs) pour générer le récapitulatif
2. Rentrer une année et valider
3. Faire une recherche si besoin précis. Possibilité de télécharger la liste pour l'année choisie au format Excel.

Licence : GNU General Public License v3.0

Liste des dépendances : PHPExcel, google font Abeezee

Langages : HTML, CSS, PHP, jQuery
