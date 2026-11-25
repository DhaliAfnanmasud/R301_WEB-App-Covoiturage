<?php
/** @var \App\Covoiturage\Model\DataObject\Voiture $voiture */
?>

<form action="frontController.php" method="GET">
    <fieldset>
        <legend>Mon formulaire de mise à jour :</legend>
        <input type="hidden" name="controller" value="voiture">
        <input type="hidden" name="action" value="updated">
        <p>
            <label for="immatriculation_id">Immatriculation</label> :
            <input type="text" name="immatriculation" id="immatriculation_id" 
                   value="<?= htmlspecialchars($voiture->getImmatriculation()) ?>" readonly required />
        </p>
        <p>
            <label for="marque_id">Marque</label> :
            <input type="text" name="marque" id="marque_id" 
                   value="<?= htmlspecialchars($voiture->getMarque()) ?>" required />
        </p>
        <p>
            <label for="couleur_id">Couleur</label> :
            <input type="text" name="couleur" id="couleur_id" 
                   value="<?= htmlspecialchars($voiture->getCouleur()) ?>" required />
        </p>
        <p>
            <label for="nbSieges_id">Nombre de sièges</label> :
            <input type="number" name="nbSieges" id="nbSieges_id" 
                   value="<?= htmlspecialchars($voiture->getNbSieges()) ?>" required />
        </p>
        <p>
            <input type="submit" value="Mettre à jour" />
        </p>
    </fieldset>
</form>