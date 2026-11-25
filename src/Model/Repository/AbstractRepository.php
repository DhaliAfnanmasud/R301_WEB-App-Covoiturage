<?php

namespace App\Covoiturage\Model\Repository;

use App\Covoiturage\Model\DataObject\AbstractDataObject;
use PDOException;

abstract class AbstractRepository {

    // Ajout de la méthode abstraite getNomTable()    
    protected abstract function getNomTable(): string;

    // Ajout de la méthode abstraite getNomClePrimaire
    protected abstract function getNomClePrimaire(): string;

    // Ajout de la méthode abstraite getNomColonnes
    protected abstract function getNomsColonnes(): array;

    // Ajout de la méthode construire() pour les classes filles
    protected abstract function construire(array $objetFormatTableau) : AbstractDataObject;

        /**
     * @return AbstractDataObject[]
     */
    public function selectAll() : array {
        $sql = "SELECT * FROM " . $this->getNomTable();
        $pdoStatement = DatabaseConnection::getPdo()->query($sql);

        $objets = array();
        
        foreach($pdoStatement as $objetFormatTableau){
            $objets[] = $this->construire($objetFormatTableau);
        }

        return $objets;
    }

    // Ajout de la méthode pour faciliter les actions read des différents contrôleurs
    public function select(string $valeurClePrimaire): ?AbstractDataObject {
        $nomTable = $this->getNomTable();
        $nomClePrimaire = $this->getNomClePrimaire();

        $sql = "SELECT * from $nomTable 
                WHERE $nomClePrimaire = :clePrimaireTag";

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array (
            "clePrimaireTag" => $valeurClePrimaire
        );

        $pdoStatement->execute($values);
        $objet = $pdoStatement->fetch();

        if ($objet === false) {
            return null;
        }

        // On appelle la méthode construire
        return $this->construire($objet);
    }

    public function update(AbstractDataObject $object) : void {
        $nomTable = $this->getNomTable();
        $nomClePrimaire = $this->getNomClePrimaire();
        $nomsColonnes = $this->getNomsColonnes();

        // Construction dynamique de la partie SET de la requête SQL (ex: marque = :marqueTag, couleur = :couleurTag...)
        $setSql = "";
        foreach ($nomsColonnes as $colonne) {
            if ($colonne !== $nomClePrimaire) {
                $setSql .= "$colonne = :{$colonne}Tag, ";
            }
        }

        $setSql = rtrim($setSql, ", ");        
        $sql = "UPDATE $nomTable
                SET $setSql
                WHERE $nomClePrimaire = :{$nomClePrimaire}Tag";
            
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        // Utilisation de formatTableau() défini dans l'objet
        $pdoStatement->execute($object->formatTableau());
    }

    public function save(AbstractDataObject $object): bool {
        try {
            $nomTable = $this->getNomTable();
            $nomsColonnes = $this->getNomsColonnes();

            // Construction dynamique de la liste des colonnes 
            $colonnesSql = implode(", ", $nomsColonnes);

            // Construction dynamique des tags
            $tagsSql = "";
            foreach ($nomsColonnes as $colonne) {
                $tagsSql .= ":{$colonne}Tag, ";
            }
            $tagsSql = rtrim($tagsSql, ", ");

            $sql = "INSERT INTO $nomTable ($colonnesSql) 
                    VALUES ($tagsSql)";

            $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

            // On utilise directement formatTableau() défini par l'objet
            return $pdoStatement->execute($object->formatTableau());
        } 
        catch (PDOException $e) {
            return false;
        }
    }

    public function delete (string $valeurClePrimaire) : bool {
        $nomTable = $this->getNomTable();
        $nomClePrimaire = $this->getNomClePrimaire();
        
        try {
            $sql = "DELETE FROM $nomTable 
                    WHERE $nomClePrimaire = :clePrimaireTag";
            
            $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

            $values = array(
                "clePrimaireTag" => $valeurClePrimaire
            );

            $pdoStatement->execute($values);
            return $pdoStatement->rowCount() > 0;
        }
        catch (PDOException $Exception){
            return false;
        }
    }
}

?>