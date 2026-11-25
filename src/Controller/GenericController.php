<?php

namespace App\Covoiturage\Controller;

use App\Covoiturage\Lib\MessageFlash;
use App\Covoiturage\Lib\PreferenceControleur;

abstract class GenericController {

    protected static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres);
        $messagesFlash = MessageFlash::lireTousMessages();
        require_once __DIR__ . "/../View/view.php";   
    }

    public static function error(string $errorMessage = '') : void {
        self::afficheVue("voiture/error.php", [
            "errorMessage" => $errorMessage,
            "pagetitle" => "Erreur",
            "cheminVueBody" => "voiture/error.php"
        ]);
    }

    public static function formulairePreference() : void {
        self::afficheVue("formulairePreference.php", [
            "pagetitle" => "Préférence de contrôleur",
            "cheminVueBody" => "formulairePreference.php"
        ]);
    }

    public static function enregistrerPreference() : void {
        if (isset($_GET['controleur_defaut'])) {
            PreferenceControleur::enregistrer($_GET['controleur_defaut']);
        }
        MessageFlash::ajouter("success", "La préférence de contrôleur est enregistrée !");
        self::redirectionVersURL("frontController.php");
    }

    protected static function redirectionVersURL(string $url) : void {
        header("Location: $url");
        exit();
    }
}