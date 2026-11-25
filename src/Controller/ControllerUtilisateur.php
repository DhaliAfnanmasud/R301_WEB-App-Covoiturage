<?php

namespace App\Covoiturage\Controller;

use App\Covoiturage\Lib\MessageFlash;
use App\Covoiturage\Lib\MotDePasse;
use App\Covoiturage\Lib\ConnexionUtilisateur;
use App\Covoiturage\Model\DataObject\Utilisateur;  // chargement de la classe Utilisateur
use App\Covoiturage\Model\Repository\UtilisateurRepository;
use App\Covoiturage\Model\HTTP\Cookie;
use App\Covoiturage\Model\HTTP\Session;


class ControllerUtilisateur extends GenericController {

    public static function create() : void {
        self::afficheVue("utilisateur/create.php", [
            "pagetitle" => "Créer un utilisateur",
            "cheminVueBody" => "utilisateur/create.php"
        ]);
    }

    public static function created() : void {
        $login = $_GET['login'] ?? null;
        $nom = $_GET['nom'] ?? null;
        $prenom = $_GET['prenom'] ?? null;
        $mdp = $_GET['mdp'] ?? null;
        $mdp2 = $_GET['mdp2'] ?? null;

        // donnée manquante alors que "required" : le client essaye d'attaquer -> danger
        if (!($login && $nom && $prenom && $mdp && $mdp2)) {
            MessageFlash::ajouter("danger", "Login, nom, prénom ou mot de passe manquant.");
            self::redirectionVersURL("frontController.php?controller=utilisateur&action=create");
            return;
        }
        // on vérifie que les deux passes sont identiques
        if($mdp !== $mdp2){
            MessageFlash::ajouter("warning", "Mots de passe distincts");
            self::redirectionVersURL("frontController.php?controller=utilisateur&action=create");
            return;
        }

        $utilisateur = Utilisateur::construireDepuisFormulaire($_GET);
        // Seul un administrateur peut créer un administrateur
        if (!ConnexionUtilisateur::estAdministrateur()) {
            $utilisateur->setEstAdmin(false);
        }
        (new UtilisateurRepository())->save($utilisateur);
        MessageFlash::ajouter("success", "L'utilisateur a bien été créé !");
        self::redirectionVersURL("frontController.php?controller=utilisateur&action=readAll");

    }

    public static function readAll() : void {
        $utilisateurs = (new UtilisateurRepository())->selectAll(); //appel au modèle pour gerer la BDD
        self::afficheVue("utilisateur/list.php", [
            "utilisateurs" => $utilisateurs,
            "pagetitle" => "Liste des utilisateurs",
            "cheminVueBody" => "utilisateur/list.php"]); //"redirige" vers la vue afin d'afficher la liste des utilisateurs
    }

    public static function read() : void {
        // On récupère l'immatriculation donnée dans l'URL
        $login = $_GET['login'] ?? null;
        
        if($login !== NULL) {
            $utilisateur = (new UtilisateurRepository())->select($login);
            if ($utilisateur !== NULL) {
                self::afficheVue("utilisateur/detail.php", [
                    "utilisateur" => $utilisateur,
                    "pagetitle" => "Détail de l'utilisateur",
                    "cheminVueBody" => "utilisateur/detail.php"]); //"redirige" vers la vue afin d'afficher les détails de la voitures sélectionné
            }
            else {
                self::error("Le login '$login' n'existe pas !");
            }
        }
        else {
            self::error("Le login n'a pas été fournie dans l'URL");
        }
    }

    public static function readTrajets() : void {
        $login = $_GET['login'] ?? null;

        if ($login !== null) {
            $trajets = UtilisateurRepository::getTrajets($login);
            
            self::afficheVue("trajet/list.php", [
                "trajets" => $trajets,
                "pagetitle" => "Trajets de l'utilisateur",
                "cheminVueBody" => "trajet/list.php"
            ]);
        } else {
            self::error("Login non spécifié pour afficher les trajets.");
        }   
    }

    public static function update() : void {
        $login = $_GET['login'] ?? null;

        // Pas de login dans l'URL : le client a triché donc danger
        if ($login === null) {
            MessageFlash::ajouter("danger", "Login manquant.");
            self::redirectionVersURL("frontController.php?controller=utilisateur&action=readAll");
            return;
        }
        
        // un admin peut tout modifier mais seul un admin change le statut
        if (!(ConnexionUtilisateur::estUtilisateur($login) || ConnexionUtilisateur::estAdministrateur())) {
            MessageFlash::ajouter("danger", "Vous n'avez pas le droit de modifier cet utilisateur.");
            self::redirectionVersURL("frontController.php?controller=utilisateur&action=readAll");
            return;
        }

        /** @var Utilisateur|null $utilisateur */
        $utilisateur = (new UtilisateurRepository())->select($login);

        // Le login doit exister dans la base
        if ($utilisateur === null) {
            MessageFlash::ajouter("danger", "Cet utilisateur n'existe pas.");
            self::redirectionVersURL("frontController.php?controller=utilisateur&action=readAll");
            return;
        }

        self::afficheVue("utilisateur/update.php", [
            "utilisateur" => $utilisateur,
            "pagetitle" => "Mise à jour de l'utilisateur",
            "cheminVueBody" => "utilisateur/update.php"
        ]);
    }

    public static function updated() : void {
        $login = $_GET['login'] ?? null;
        $nom = $_GET['nom'] ?? null;
        $prenom = $_GET['prenom'] ?? null;
        $ancienMdp = $_GET['ancien_mdp'] ?? null;
        $mdp = $_GET['mdp'] ?? null;
        $mdp2 = $_GET['mdp2'] ?? null;
        $estAdmin = isset($_GET['estAdmin']) ?? null;

        // on vérifie que tous les champs obligatoires ont été transmis
        if (!($login && $nom && $prenom && $mdp && $mdp2)) {
            MessageFlash::ajouter("danger", "Données manquantes.");
            self::redirectionVersURL("frontController.php?controller=utilisateur&action=readAll");
            return;
        }

        // on vérifie si l'utilisateur existe
        /** @var Utilisateur $utilisateur */
        $utilisateur = (new UtilisateurRepository())->select($login);
        if ($utilisateur === null) {
            MessageFlash::ajouter("danger", "Cet utilisateur n'existe pas");
            self::redirectionVersURL("frontController.php?controller=utilisateur&action=readAll");
            return;
        }

        // 3. L'utilisateur est le connecté, ou un administrateur ?
        if (!(ConnexionUtilisateur::estUtilisateur($login) || ConnexionUtilisateur::estAdministrateur())) {
            MessageFlash::ajouter("danger", "Vous n'avez pas le droit de modifier cet utilisateur.");
            self::redirectionVersURL("frontController.php?controller=utilisateur&action=readAll");
            return;
        }

        $urlUpdate = "frontController.php?controller=utilisateur&action=update&login=" . urlencode($login);

        // on vérifie que les deux nouveaux mot de passes sont identiques
        if($mdp !== $mdp2){
            MessageFlash::ajouter("warning", "Mots de passe distincts");
            self::redirectionVersURL($urlUpdate);
            return;
        }

        // L'ancien mot de passe n'est demandé que pour son propre compte
        if (ConnexionUtilisateur::estUtilisateur($login)) {
            if (!$ancienMdp || !MotDePasse::verifier($ancienMdp, $utilisateur->getMdpHache())) {
                MessageFlash::ajouter("warning", "Ancien mot de passe erroné");
                self::redirectionVersURL($urlUpdate);
                return;
            }
        }
        

        // Si tout est correcte on peut mettre à jour
        $utilisateur->setNom($nom);
        $utilisateur->setPrenom($prenom);
        $utilisateur->setMdpHache($mdp); // hache automatiquement
        if (ConnexionUtilisateur::estAdministrateur()) {
            $utilisateur->setEstAdmin($estAdmin);
        }
        (new UtilisateurRepository())->update($utilisateur); // on effectue la mise à jour dans la base de données
        
        // Message de confirmation de création
        MessageFlash::ajouter("success", "L'utilisateur a bien été mis à jour !");
        self::redirectionVersURL("frontController.php?controller=utilisateur&action=readAll");
    }

    public static function delete() : void {
        $login = $_GET['login'] ?? null;

        // on vérifie si le login existe
        if ($login === null) {
            MessageFlash::ajouter("danger", "Impossible de supprimer l'utilisateur : aucun login spécifié.");
            self::redirectionVersURL("frontController.php?controller=utilisateur&action=readAll");
            return;
        }

        // on vérifie si l'utilisateur existe
        if ((new UtilisateurRepository())->select($login) === null) {
            MessageFlash::ajouter("danger", "Cet utilisateur n'existe pas.");
            self::redirectionVersURL("frontController.php?controller=utilisateur&action=readAll");
            return;
        }
        
        // on vérifie les droits : soi-même OU administrateur
        $estSoiMeme = ConnexionUtilisateur::estUtilisateur($login);
        if (!($estSoiMeme || ConnexionUtilisateur::estAdministrateur())) {
            MessageFlash::ajouter("danger", "Vous n'avez pas le droit de supprimer cet utilisateur.");
            self::redirectionVersURL("frontController.php?controller=utilisateur&action=readAll");
            return;
        }

        (new UtilisateurRepository())->delete($login);

        // On ne déconnecte que si l'utilisateur s'est supprimé lui-même
        if ($estSoiMeme) {
            ConnexionUtilisateur::deconnecter();
        }

        MessageFlash::ajouter("success", "L'utilisateur a bien été supprimé !");
        self::redirectionVersURL("frontController.php?controller=utilisateur&action=readAll");
    }

    // Gestions des cookies --------------------------------------------

    // Dépose un cookie
    /*public static function deposerCookie() : void {
       Cookie::enregistrer("TestCookie", ["nom" => "Dupont", "notes" => [14, 16]], time() + 3600);   // expire dans 1 heure = 3600 secondes

        $utilisateurs = (new UtilisateurRepository())->selectAll(); //appel au modèle pour gerer la BDD
        self::afficheVue("utilisateur/list.php", [
            "utilisateurs" => $utilisateurs,
            "pagetitle" => "Liste des utilisateurs",
            "cheminVueBody" => "utilisateur/list.php"]);

    }

    // Lire un cookie
    public static function lireCookie() : void {
        if (Cookie::contient("TestCookie")){
            echo "<pre>";
            print_r(Cookie::lire("TestCookie"));
            echo "</pre>";            
        }
        else {
            echo "Le cookie TestCookie n'exsite pas";
        }
    }
    */

    /* Supprimer un cookie
    public static function supprimerCookie(){
        Cookie::supprimer("TestCookie");
        echo "Cookie supprimé.";
    }

    // Gestion des sessions ------------------------------------------------
    public static function testSessionEcrire() : void {
        // on démarre la session
        $session = Session::getInstance();
        $session->enregistrer("texte", "Cathy Penneflamme");
        $session->enregistrer("tableau", ["a" => 1, "b" => [2, 3]]);
        $session->enregistrer("objet", new Utilisateur("jdupont", "DUPONT", "Jean"));
        echo "Session écrite";
    }

    public static function testSessionLire() : void {
        $session = Session::getInstance();
        foreach (["texte", "tableau", "objet"] as $cle) {
            echo "<pre>$cle : ";
            print_r($session->contient($cle) ? $session->lire($cle) : "(absent)");
            echo "</pre>";
        }
    }

    public static function testSessionSupprimee() : void {
        Session::getInstance()->supprimer("texte");
        echo "Variable 'texte' supprimé";
    }

    public static function testSessionDetruite() : void {
        Session::getInstance()->detruire();
        echo "Session détruite.";
    }

    public static function testExpiration() : void {
        $session = Session::getInstance();
        if ($session->contient("test")) {
            echo "La variable est toujours en session : " . $session->lire("test");
        } 
        else {
            $session->enregistrer("test", "Je suis là");
            echo "Variable absente : je viens de l'enregistrer.";
        }
    }*/

    
    public static function formulaireConnexion() : void {
        self::afficheVue("utilisateur/formulaireConnexion.php", [
            "pagetitle" => "Connexion",
            "cheminVueBody" => "utilisateur/formulaireConnexion.php"
        ]);
    }

    public static function connecter() : void {
        $login = $_GET['login'] ?? null;
        $mdp = $_GET['mdp'] ?? null;
        
        // on vérifie si le login et le mot de passe sont bien transmis
        if (!($login && $mdp)) {
            MessageFlash::ajouter("danger", "Login ou mot de passe manquant.");
            self::redirectionVersURL("frontController.php?controller=utilisateur&action=formulaireConnexion");
            return;
        }

        // on vérifie si le login et le mot de passe sont correct
        /** @var Utilisateur|null $utilisateur */
        $utilisateur = (new UtilisateurRepository())->select($login);
        if ($utilisateur === null || !MotDePasse::verifier($mdp, $utilisateur->getMdpHache())){
            MessageFlash::ajouter("warning", "Login et/ou mot de passe incorrect.");
            self::redirectionVersURL("frontController.php?controller=utilisateur&action=formulaireConnexion");
            return;            
        }

        // si tout est bon et correcte
        ConnexionUtilisateur::connecter($utilisateur->getLogin());
        MessageFlash::ajouter("success", "Connexion réussie !");
        self::redirectionVersURL("frontController.php?controller=utilisateur&action=read&login=" . urlencode($utilisateur->getLogin()));

    }

    public static function deconnecter() : void {
        ConnexionUtilisateur::deconnecter();
        MessageFlash::ajouter("success", "Déconnexion réussie");
        self::redirectionVersURL("frontController.php?controller=utilisateur&action=readAll");
    }
}
