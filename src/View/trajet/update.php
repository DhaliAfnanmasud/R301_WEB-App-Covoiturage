<?php
/** @var \App\Covoiturage\Model\DataObject\Trajet $trajet */
?>

<form action="frontController.php" method="GET">
    <fieldset>
        <legend>Modifier le trajet :</legend>
        <input type='hidden' name='controller' value='trajet'>
        <input type='hidden' name='action' value='updated'>
        
        <!-- L'ID est souvent mis en champ caché (hidden) ou en readonly pour l'identifier lors de la mise à jour -->
        <input type='hidden' name='id' value="<?= htmlspecialchars((string)$trajet->getId()) ?>">

        <p>
            <label for="depart_id">Départ</label> :
            <input type="text" name="depart" id="depart_id" value="<?= htmlspecialchars($trajet->getDepart()) ?>" required />
        </p>
        <p>
            <label for="arrivee_id">Arrivée</label> :
            <input type="text" name="arrivee" id="arrivee_id" value="<?= htmlspecialchars($trajet->getArrivee()) ?>" required />
        </p>
        <p>
            <label for="date_id">Date</label> :
            <input type="text" name="date" id="date_id" value="<?= htmlspecialchars($trajet->getDate()) ?>" required />
        </p>
        <p>
            <label for="nbPlaces_id">Nombre de places</label> :
            <input type="number" name="nbPlaces" id="nbPlaces_id" value="<?= htmlspecialchars((string)$trajet->getNbPlaces()) ?>" required />
        </p>
        <p>
            <label for="prix_id">Prix</label> :
            <input type="text" name="prix" id="prix_id" value="<?= htmlspecialchars((string)$trajet->getPrix()) ?>" required />
        </p>
        <p>
            <label for="conducteurLogin_id">Login du conducteur</label> :
            <input type="text" name="conducteurLogin" id="conducteurLogin_id" value="<?= htmlspecialchars($trajet->getConducteurLogin()) ?>" required />
        </p>
        <p>
            <input type="submit" value="Mettre à jour" />
        </p>
    </fieldset>
</form>