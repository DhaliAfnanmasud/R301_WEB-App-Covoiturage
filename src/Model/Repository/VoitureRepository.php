<?php

namespace App\Covoiturage\Model\Repository;

use App\Covoiturage\Model\DataObject\Voiture;
use App\Covoiturage\Model\Repository\DatabaseConnection;
use Override;
use PDOException;

class VoitureRepository extends AbstractRepository {

    #[Override]
    protected function getNomTable(): string {
        return 'voiture';
    }

    #[Override]
    protected function getNomClePrimaire(): string {
        return 'immatriculation';
    }

    #[Override]
    protected function getNomsColonnes(): array {
        return ['immatriculation', 'marque', 'couleur', 'nbSieges'];
    }

    #[Override]
    public function construire(array $voitureFormatTableau) : Voiture {
        return new Voiture (
            $voitureFormatTableau['immatriculation'],
            $voitureFormatTableau['marque'],
            $voitureFormatTableau['couleur'],
            $voitureFormatTableau['nbSieges']
        );
    } 

}