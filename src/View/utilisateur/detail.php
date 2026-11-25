<?php
use App\Covoiturage\Lib\ConnexionUtilisateur;

/** @var App\Covoiturage\Model\DataObject\Utilisateur $utilisateur */
?>

<h2>Détail de l'utilisateur</h2>
<p>
    <strong>Login :</strong> <?= htmlspecialchars($utilisateur->getLogin()) ?><br>
    <strong>Nom :</strong> <?= htmlspecialchars($utilisateur->getNom()) ?><br>
    <strong>Prénom :</strong> <?= htmlspecialchars($utilisateur->getPrenom()) ?>
</p>

<p><a href="frontController.php?controller=utilisateur&action=readTrajets&login=<?= rawurlencode($utilisateur->getLogin()) ?>">Voir ses trajets en tant que passager</a></p>

<?php if (ConnexionUtilisateur::estUtilisateur($utilisateur->getLogin()) || ConnexionUtilisateur::estAdministrateur()) { ?>
    <p><a href="frontController.php?controller=utilisateur&action=update&login=<?= rawurlencode($utilisateur->getLogin()) ?>">Mettre à jour cet utilisateur</a></p>
    <p><a href="frontController.php?controller=utilisateur&action=delete&login=<?= rawurlencode($utilisateur->getLogin()) ?>">Supprimer cet utilisateur</a></p>
<?php } ?>

<a href="frontController.php?controller=utilisateur&action=readAll">Retour à la liste des utilisateurs</a>