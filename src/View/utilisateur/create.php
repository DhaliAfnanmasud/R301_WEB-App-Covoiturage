<?php
use App\Covoiturage\Lib\ConnexionUtilisateur;
?>

<form action="frontController.php" method="GET">
    <fieldset>
        <legend>Créer un utilisateur :</legend>

        <input type="hidden" name="controller" value="utilisateur">
        <input type="hidden" name="action" value="created">

        <p>
            <label for="login_id">Login</label> :
            <input type="text" name="login" id="login_id" required />
        </p>
        <p>
            <label for="nom_id">Nom</label> :
            <input type="text" name="nom" id="nom_id" required />
        </p>
        <p>
            <label for="prenom_id">Prénom</label> :
            <input type="text" name="prenom" id="prenom_id" required />
        </p>
        <p>
            <label for="mdp_id">Mot de passe&#42;</label> :
            <input type="password" name="mdp" id="mdp_id" value="" required />
        </p>
        <p>
            <label for="mdp2_id">Vérification du mot de passe&#42;</label> :
            <input type="password" name="mdp2" id="mdp2_id" value="" required />
        </p>
        <?php if (ConnexionUtilisateur::estAdministrateur()) { ?>
            <p>
                <label for="estAdmin_id">Administrateur</label> :
                <input type="checkbox" name="estAdmin" id="estAdmin_id" />
            </p>
        <?php } ?>
        
        <p>
            <input type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>