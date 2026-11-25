<?php
/** @var \App\Covoiturage\Model\DataObject\Utilisateur $utilisateur */

use App\Covoiturage\Lib\ConnexionUtilisateur;

?>

<form action="frontController.php" method="GET">
    <fieldset>
        <legend>Modifier l'utilisateur :</legend>
        <input type='hidden' name='controller' value='utilisateur'>
        <input type='hidden' name='action' value='updated'>
        <p>
            <label for="login_id">Login</label> :
            <input type="text" name="login" id="login_id" value="<?= htmlspecialchars($utilisateur->getLogin()) ?>" readonly required />
        </p>
        <p>
            <label for="nom_id">Nom</label> :
            <input type="text" name="nom" id="nom_id" value="<?= htmlspecialchars($utilisateur->getNom()) ?>" required />
        </p>
        <p>
            <label for="prenom_id">Prénom</label> :
            <input type="text" name="prenom" id="prenom_id" value="<?= htmlspecialchars($utilisateur->getPrenom()) ?>" required />
        </p>

        <p>
            <label for="ancien_mdp_id">Ancien mot de passe&#42;</label> :
            <input type="password" name="ancien_mdp" id="ancien_mdp_id" value=""
                <?= ConnexionUtilisateur::estUtilisateur($utilisateur->getLogin()) ? 'required' : '' ?> />
        </p>
        
        <p>
            <label for="mdp_id">Nouveau mot de passe&#42;</label> :
            <input type="password" name="mdp" id="mdp_id" value="" required />
        </p>

        <p>
            <label for="mdp2_id">Vérification du nouveau mot de passe&#42;</label> :
            <input type="password" name="mdp2" id="mdp2_id" value="" required />
        </p>
        <?php if (ConnexionUtilisateur::estAdministrateur()) { ?>
            <p>
                <label for="estAdmin_id">Administrateur</label> :
                <input type="checkbox" name="estAdmin" id="estAdmin_id" <?= $utilisateur->getEstAdmin() ? 'checked' : '' ?> />
            </p>
        <?php } ?>
        
        <p>
            <input type="submit" value="Mettre à jour" />
        </p>
    </fieldset>
</form>