<?php

namespace App\Covoiturage\Model\Repository;

use App\Covoiturage\Model\DataObject\Utilisateur;
use App\Covoiturage\Model\Repository\DatabaseConnection;
use Override;

class UtilisateurRepository extends AbstractRepository{

    #[Override]
    protected function getNomTable(): string {
        return 'utilisateur';
    }

    #[Override]
    protected function getNomClePrimaire(): string {
        return 'login';
    }

    #[Override]
    protected function getNomsColonnes(): array {
        return ['login', 'nom', 'prenom', 'mdpHache', 'estAdmin'];
    }

    // Méthode statique qui instancie et retourne un utilisateur à partir du tableau de la BDD
    #[Override]
    protected function construire (array $utilisateurFormatTableau) : Utilisateur {
        return new Utilisateur(
            $utilisateurFormatTableau['login'],
            $utilisateurFormatTableau['nom'],
            $utilisateurFormatTableau['prenom'],
            $utilisateurFormatTableau['mdpHache'],
            (bool) $utilisateurFormatTableau['estAdmin']
        );
    }
    

    /*public static function getUtilisateurs() : array {
        $sql = "SELECT * FROM utilisateur";
        $pdoStatement = DatabaseConnection::getPdo()->query($sql);

        $utilisateurs = array();

        foreach($pdoStatement as $listeUtilisateurs){
            $utilisateurs[] = static::construireUtilisateurs($listeUtilisateurs);
        }

        return $utilisateurs;
    }
    */

    public static function getTrajets(string $login) : array {
        $sql = "SELECT trajet.*
                FROM trajet
                INNER JOIN passager on trajet.id=passager.trajetID
                WHERE passager.passagerLogin = :passagerLoginTag";
        
        // Préparation de la requête
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array(
            "passagerLoginTag" => $login
        );

        // On donne les valeurs et on éxécute la requête
        $pdoStatement->execute($values);
        // On récupère les résultats commme précédemment
        $tableauTrajets = $pdoStatement->fetchAll();

        $trajets = array();

        foreach($tableauTrajets as $tableauTrajet){
            $trajets[] = (new TrajetRepository())->construire($tableauTrajet);
        }

        return $trajets;
    }

}