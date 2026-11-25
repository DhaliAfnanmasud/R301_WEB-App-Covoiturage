<?php
/** @var App\Covoiturage\Model\DataObject\Voiture $voiture */
?>

<h2>Détail de la voiture</h2>
<p>
    <strong>Immatriculation :</strong> <?= htmlspecialchars($voiture->getImmatriculation()) ?><br>
    <strong>Marque :</strong> <?= htmlspecialchars($voiture->getMarque()) ?><br>
    <strong>Couleur :</strong> <?= htmlspecialchars($voiture->getCouleur()) ?><br>
    <strong>Nombre de sièges :</strong> <?= htmlspecialchars($voiture->getNbSieges()) ?>
</p>

<p><a href="frontController.php?controller=voiture&action=update&immatriculation=<?= rawurlencode($voiture->getImmatriculation()) ?>">Mettre à jour cette voiture</a></p>
<a href="frontController.php?controller=voiture&action=readAll">Retour à la liste des voitures</a>