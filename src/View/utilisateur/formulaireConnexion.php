<form action="frontController.php" method="GET">
    <fieldset>
        <legend>Connexion</legend>

        <input type="hidden" name="controller" value="utilisateur">
        <input type="hidden" name="action" value="connecter">

        <p>
            <label for="login_id">Login</label> :
            <input type="text" name="login" id="login_id" required />
        </p>
        <p>
            <label for="mdp_id">Mot de passe&#42;</label> :
            <input type="password" name="mdp" id="mdp_id" required />
        </p>
        <p>
            <input type="submit" value="Se connecter" />
        </p>
    </fieldset>
</form>