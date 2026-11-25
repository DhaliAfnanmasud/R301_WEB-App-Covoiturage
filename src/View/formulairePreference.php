<?php

use App\Covoiturage\Lib\PreferenceControleur;

$pref = PreferenceControleur::existe() ? PreferenceControleur::lire() : "";
?>

<form method="get" action="frontController.php">
    <input type="hidden" name="action" value="enregistrerPreference">
    <fieldset>
        <legend>Contrôleur par défaut :</legend>
        <input type="radio" id="voitureId" name="controleur_defaut" value="voiture" <?= $pref === "voiture" ? "checked" : "" ?>>
        <label for="voitureId">Voiture</label>
        <input type="radio" id="utilisateurId" name="controleur_defaut" value="utilisateur" <?= $pref === "utilisateur" ? "checked" : "" ?>>
        <label for="utilisateurId">Utilisateur</label>
        <input type="radio" id="trajetId" name="controleur_defaut" value="trajet" <?= $pref === "trajet" ? "checked" : "" ?>>
        <label for="trajetId">Trajet</label>
        <p><input type="submit" value="Enregistrer"></p>
    </fieldset>
</form>