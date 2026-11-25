<?php

namespace App\Covoiturage\Lib;

use App\Covoiturage\Model\HTTP\Session;
use App\Covoiturage\Model\DataObject\Utilisateur;
use App\Covoiturage\Model\Repository\UtilisateurRepository;
use Random\Engine\Secure;

class ConnexionUtilisateur {

    // L'utilisateur connecté sera enregistré en session associé à la clé suivante
    private static string $cleConnexion = "_utilisateurConnecte";

    public static function connecter(string $loginUtilisateur): void {
        Session::getInstance()->enregistrer(self::$cleConnexion, $loginUtilisateur);
    }

    public static function estConnecte(): bool {
        return Session::getInstance()->contient(self::$cleConnexion);
    }
    
    public static function deconnecter(): void {
        Session::getInstance()->supprimer(self::$cleConnexion);
    }

    public static function getLoginUtilisateurConnecte(): ?string {
        if (self::estConnecte()){
            return Session::getInstance()->lire(self::$cleConnexion);
        }
        return null;
    }

    public static function estUtilisateur(string $login): bool {
        return self::estConnecte() && self::getLoginUtilisateurConnecte() === $login; 
    }

    public static function estAdministrateur() : bool {
        if (self::estConnecte()){
            /** @var Utilisateur|null $utilisateur */
            $utilisateur = (new UtilisateurRepository())->select(self::getLoginUtilisateurConnecte());
            return $utilisateur !== null && $utilisateur->getEstAdmin();
        }
        
        else {
            return false;
        }
    }

}