<?php

namespace App\Covoiturage\Model\DataObject;

use App\Covoiturage\Lib\MotDePasse;
use Override;

class Utilisateur extends AbstractDataObject {

    // Attributs 
    private string $login;
    private string $nom;
    private string $prenom;
    private string $mdpHache;
    private bool $estAdmin;


    // Constructeur
    public function __construct(string $login, string $nom, string $prenom, string $mdpHache, bool $estAdmin){
        $this->login = $login;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->mdpHache = $mdpHache;
        $this->estAdmin = $estAdmin;
    }

    // Getter & Setters
    public function getLogin(): string {
        return $this->login;
    }

    public function setLogin(string $login): void {
        $this->login = $login;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function getPrenom(): string {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }

    public function getMdpHache() : string {
        return $this->mdpHache;
    }

    // Prend le mot de passe en clair, le hache, puis l'enregistre
    public function setMdpHache(string $mdpClair) : void {
        $this->mdpHache = MotDePasse::hacher($mdpClair);
    }

    public function getEstAdmin() : bool {
        return $this->estAdmin;
    }

    public function setEstAdmin(bool $estAdmin) : void {
        $this->estAdmin = $estAdmin;
    }

    // Pour pouvoir convertir un objet en chaîne de caractère
    public function __toString(): string {
        return "Utilisateur Login : " . $this->login . "<br>Nom : ". $this->nom . "<br>Prénom : " . $this->prenom;
    }

    #[Override]
    public function formatTableau(): array {
        return array (
            "loginTag" => $this->getLogin(),
            "nomTag" => $this->getNom(),
            "prenomTag" => $this->getPrenom(),
            "mdpHacheTag" => $this->getMdpHache(),
            "estAdminTag" => $this->getEstAdmin() ? 1 : 0
        );
    }

    public static function construireDepuisFormulaire (array $tableauFormulaire) : Utilisateur {
        $mdpHache = MotDePasse::hacher($tableauFormulaire['mdp']);
        $estAdmin = isset($tableauFormulaire['estAdmin']);
        return new Utilisateur(
            $tableauFormulaire['login'],
            $tableauFormulaire['nom'],
            $tableauFormulaire['prenom'],
            $mdpHache,
            $estAdmin
        );
    }

}

