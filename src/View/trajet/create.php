<form action="frontController.php" method="GET">
    <fieldset>
        <legend>Créer un trajet :</legend>
        <input type="hidden" name="controller" value="trajet">
        <input type="hidden" name="action" value="created">

        <p>
            <label for="depart_id">Départ</label> :
            <input type="text" name="depart" id="depart_id" required />
        </p>
        <p>
            <label for="arrivee_id">Arrivée</label> :
            <input type="text" name="arrivee" id="arrivee_id" required />
        </p>
        <p>
            <label for="date_id">Date</label> :
            <input type="text" name="date" id="date_id" required />
        </p>
        <p>
            <label for="nbPlaces_id">Nombre de places</label> :
            <input type="number" name="nbPlaces" id="nbPlaces_id" required />
        </p>
        <p>
            <label for="prix_id">Prix</label> :
            <input type="text" name="prix" id="prix_id" required />
        </p>
        <p>
            <label for="conducteurLogin_id">Login du conducteur</label> :
            <input type="text" name="conducteurLogin" id="conducteurLogin_id" required />
        </p>
        <p>
            <input type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>