<?php

namespace App\Covoiturage\Model\Repository;

use App\Covoiturage\Model\DataObject\Utilisateur;
use App\Covoiturage\Model\DataObject\Trajet;
use App\Covoiturage\Model\Repository\DatabaseConnection;
use Override;
use PDOException;

class TrajetRepository extends AbstractRepository {

    #[Override]
    protected function getNomTable(): string {
        return 'trajet';
    }

    #[Override]
    protected function getNomClePrimaire(): string {
        return 'id';
    }

    #[Override]
    protected function getNomsColonnes(): array {
        return ['id', 'depart', 'arrivee', 'date', 'nbPlaces', 'prix', 'conducteurLogin'];
    }

    // Méthode statique qui instancie et retourne un trajet à partir du tableau de la BDD
    #[Override]
    public function construire (array $trajetFormatTableau) : Trajet {
        return new Trajet(
            $trajetFormatTableau['id'],
            $trajetFormatTableau['depart'],
            $trajetFormatTableau['arrivee'],
            $trajetFormatTableau['date'],
            $trajetFormatTableau['nbPlaces'],
            $trajetFormatTableau['prix'],
            $trajetFormatTableau['conducteurLogin']
        );
    }


    public static function getPassagers(string $id) : array {
        $sql = "SELECT utilisateur.*
                FROM utilisateur
                INNER JOIN passager ON utilisateur.login=passager.passagerlogin
                WHERE passager.trajetId = :trajetIdTag";
        
        // Préparation de la requête
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array(
            "trajetIdTag" => $id
        );

        // On donne les valeurs et on éxécute la requête
        $pdoStatement->execute($values);

        // On récupère les résultats commme précédemment
        $tableauPassagers = $pdoStatement->fetchAll();
        
        $passagers = array();

        foreach($tableauPassagers as $tableauPassager){
            $passagers[] = new Utilisateur(
                $tableauPassager['login'],
                $tableauPassager['nom'],
                $tableauPassager['prenom']
            );
        }
        
        return $passagers;
    }

    // Méthode
    public static function supprimerPassager(int $trajetId, string $passagerLogin) : bool {
        $sql = "DELETE FROM passager
                WHERE trajetID = :trajetIdTag
                AND passagerLogin = :passagerLoginTag";
        
        // Préparation de la requête
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array(
            "trajetIdTag" => $trajetId,
            "passagerLoginTag" => $passagerLogin
        );

        // On donne les valeurs et on éxécute la requête
        $pdoStatement->execute($values);

        return $pdoStatement->rowcount() > 0;

    }
}