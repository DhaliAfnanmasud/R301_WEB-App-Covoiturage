<?php
/** @var Utilisateur[] $utilisateurs */
?>

<?php foreach ($utilisateurs as $utilisateur) : ?>
    <?php
        // Utilise le getter de l'objet (ex: getLogin() ou getNom() selon ton modèle Utilisateur)
        $utilisateurBrute = $utilisateur->getLogin(); 
        // Pour l'affichage dans le corps du texte HTML
        $utilisateurHTML = htmlspecialchars($utilisateurBrute);
        // Pour la valeur du paramètre dans l'URL
        $utilisateurURL = rawurlencode($utilisateurBrute);
    ?>
    <p class="liste-item">
        Utilisateur  
        <a href="frontController.php?controller=utilisateur&action=read&login=<?= $utilisateurURL ?>"><?= $utilisateurHTML ?></a>. 
    </p>
<?php endforeach; ?>

<a href="frontController.php?controller=utilisateur&action=create">Créer un utilisateur</a>