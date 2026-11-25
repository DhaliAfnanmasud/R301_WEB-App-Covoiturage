<?php
/** @var Trajet[] $trajets */
?>

<?php foreach ($trajets as $trajet) : ?>
    <?php
        // Récupération de l'identifiant du trajet
        $idBrute = $trajet->getId(); 
        // Pour l'affichage dans le corps du texte HTML
        $idHTML = htmlspecialchars((string)$idBrute);
        // Pour la valeur du paramètre dans l'URL
        $idURL = rawurlencode((string)$idBrute);

        // Optionnel : on peut aussi préparer des infos d'affichage comme le départ et l'arrivée
        $departHTML = htmlspecialchars($trajet->getDepart());
        $arriveeHTML = htmlspecialchars($trajet->getArrivee());
    ?>
    <p class="liste-item">
        Trajet de <?= $departHTML ?> à <?= $arriveeHTML ?> — 
        <a href="frontController.php?controller=trajet&action=read&id=<?= $idURL ?>">Détails (n°<?= $idHTML ?>)</a>. 
        <a href="frontController.php?controller=trajet&action=delete&id=<?= $idURL ?>" 
        onclick="return confirm('Supprimer ce trajet ?')">Supprimer ce trajet</a>
        <a href="frontController.php?controller=trajet&action=update&id=<?= $idURL ?>">Mettre à jour</a>
    </p>
<?php endforeach; ?>

<a href="frontController.php?controller=trajet&action=create">Créer un trajet</a>