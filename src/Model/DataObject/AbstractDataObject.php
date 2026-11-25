<?php

namespace App\Covoiturage\Model\DataObject;

abstract class AbstractDataObject {
    // Cette classe servira de parent commun pour tous nos objets métiers (Voiture, Utilisateur, Trajet)

    // Méthode qui transforme un AbstractDataObject en tableau
    public abstract function formatTableau(): array;
}