<?php
/** @var Voiture[] $voitures */
?>

<?php foreach ($voitures as $voiture) : ?>
    <?php
        $immatBrute = $voiture->getImmatriculation();
        // Pour l'affichage dans le corps du texte HTML
        $immatHTML = htmlspecialchars($immatBrute);
        // Pour la valeur du paramètre dans l'URL
        $immatURL = rawurlencode($immatBrute);
    ?>
    <p class="liste-item">
        Voiture d'immatriculation 
        <a href="frontController.php?controller=voiture&action=read&immatriculation=<?= $immatURL ?>"><?= $immatHTML ?></a>. 
        <a href="frontController.php?controller=voiture&action=delete&immatriculation=<?= $immatURL ?>" 
        onclick="return confirm('Supprimer cette voiture ?')">Supprimer cette voiture</a>
        <a href="frontController.php?controller=voiture&action=update&immatriculation=<?= $immatURL ?>">Mettre à jour</a>
    </p>
<?php endforeach; ?>

<a href="frontController.php?controller=voiture&action=create">Créer une voiture</a>