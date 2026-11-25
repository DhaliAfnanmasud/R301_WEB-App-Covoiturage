<?php
/** @var App\Covoiturage\Model\DataObject\Trajet $trajet */
/** @var App\Covoiturage\Model\DataObject\Utilisateur[] $passagers */
?>

<h2>Détail du trajet</h2>
<p>
    <strong>ID :</strong> <?= htmlspecialchars((string)$trajet->getId()) ?><br>
    <strong>Départ :</strong> <?= htmlspecialchars($trajet->getDepart()) ?><br>
    <strong>Arrivée :</strong> <?= htmlspecialchars($trajet->getArrivee()) ?><br>
    <strong>Date :</strong> <?= htmlspecialchars($trajet->getDate()) ?><br>
    <strong>Nombre de places :</strong> <?= htmlspecialchars((string)$trajet->getNbPlaces()) ?><br>
    <strong>Prix :</strong> <?= htmlspecialchars((string)$trajet->getPrix()) ?> €<br>
    <strong>Conducteur :</strong> <?= htmlspecialchars($trajet->getConducteurLogin()) ?>
</p>

<h3>Liste des passagers</h3>
<ul>
    <?php foreach ($passagers as $passager) : ?>
        <li>
            <?= htmlspecialchars($passager->getPrenom()) ?> <?= htmlspecialchars($passager->getNom()) ?>
            <a href="frontController.php?controller=trajet&action=deletePassagerFromTrajet&id=<?= $trajet->getId() ?>&login=<?= rawurlencode($passager->getLogin()) ?>">Désinscrire</a>
        </li>
    <?php endforeach; ?>
</ul>

<p><a href="frontController.php?controller=trajet&action=update&id=<?= rawurlencode((string)$trajet->getId()) ?>">Mettre à jour ce trajet</a></p>
<p><a href="frontController.php?controller=trajet&action=readAll">Retour à la liste des trajets</a></p>