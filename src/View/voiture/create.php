<form method="get" action="frontController.php">
    <fieldset>
        <legend>Mon formulaire de voiture :</legend>
        <input type="hidden" name="controller" value="voiture">
        <input type="hidden" name="action" value="created">
        <p>
            <label for="immat_id">Immatriculation</label> :
            <input
                type="text"
                placeholder="256AB34"
                name="immatriculation"
                id="immat_id"
                required
            />
        </p>
        <p>
            <label for="marque_id">Marque</label> :
            <input
                type="text"
                placeholder="ex : Audi"
                name="marque"
                id="marque_id"
                required
            />
        </p>
        <p>
            <label for="couleur_id">Couleur</label> :
            <input
                type="text"
                placeholder="ex : noir"
                name="couleur"
                id="couleur_id"
                required
            />
        </p>
        <p>
            <label for="nbSieges_id">Nombre de sièges</label> :
            <input
                type="number"
                placeholder="ex : 5"
                name="nbSieges"
                id="nbSieges_id"
                min="1"
                required
            />
        </p>
        <p>
            <input type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>