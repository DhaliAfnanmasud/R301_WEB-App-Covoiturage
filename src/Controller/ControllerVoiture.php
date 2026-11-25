<?php

namespace App\Covoiturage\Controller;

use App\Covoiturage\Lib\MessageFlash;
use App\Covoiturage\Model\DataObject\Voiture;  // chargement de la classe Voiture
use App\Covoiturage\Model\Repository\VoitureRepository;

class ControllerVoiture extends GenericController {

    public static function create() : void {
        self::afficheVue("voiture/create.php", [
            "pagetitle" => "Créer une voiture",
            "cheminVueBody" => "voiture/create.php"
        ]);
    }

    public static function created() : void {
        $immatriculation = $_GET['immatriculation'] ?? null;
        $marque = $_GET['marque'] ?? null;
        $couleur = $_GET['couleur'] ?? null;
        $NbSieges = $_GET['nbSieges'] ?? null;

        if (!($immatriculation && $marque && $couleur && $NbSieges)) {
            MessageFlash::ajouter("danger", "Immatriculation, marque, couleur ou nombre de sièges manquant.");
            self::redirectionVersURL("frontController.php?controller=voiture&action=create");
        }

        elseif ((new VoitureRepository())->select($immatriculation) !== NULL){
            MessageFlash::ajouter("warning", "Immatriculation existante.");
            self::redirectionVersURL("frontController.php?controller=voiture&action=create");
        }

        else {
            $voiture = new Voiture($immatriculation, $marque, $couleur, $NbSieges);
            (new VoitureRepository())->save($voiture);
            MessageFlash::ajouter("success", "La voiture a bien été créée !");
            self::redirectionVersURL("frontController.php?controller=voiture&action=readAll");
        }
    }

    // Déclaration de type de retour void : la fonction ne retourne pas de valeur
    public static function readAll() : void {
        $voitures = (new VoitureRepository())->selectAll(); //appel au modèle pour gererla BD
        self::afficheVue("voiture/list.php", [
            "voitures" => $voitures,
            "pagetitle" => "Liste des voitures",
            "cheminVueBody" => "voiture/list.php"]); //"redirige" vers la vue afin d'afficher la liste des voitures
    }

    public static function read() : void {
        // On récupère l'immatriculation donnée dans l'URL
        $immatriculation = $_GET['immatriculation'] ?? null;
        
        if($immatriculation !== NULL) {
            //On appelle la fonction getVoitureParImmat() du modèle
            $voiture = (new VoitureRepository())->select($immatriculation);
            if ($voiture !== NULL) {
                self::afficheVue("voiture/detail.php", [
                    "voiture" => $voiture,
                    "pagetitle" => "Détail de la voiture",
                    "cheminVueBody" => "voiture/detail.php"]); //"redirige" vers la vue afin d'afficher les détails de la voitures sélectionné
            }
            else {
                self::error("La voiture d'immatriculation '$immatriculation' n'existe pas !");
            }
        }
        else {
            self::error("L'immatriculation n'a pas été fournie dans l'URL");
        }
    }
    
    public static function update() : void {
        // On récupère l'immatriculation donnée dans l'URL
        $immatriculation = $_GET['immatriculation'] ?? null;
        $voiture = (new VoitureRepository())->select($immatriculation);
        
        if($voiture !== NULL) {
            self::afficheVue("voiture/update.php", [
                "voiture" => $voiture,
                "pagetitle" => "Mise à jour de la voiture",
                "cheminVueBody" => "voiture/update.php"
            ]);
        }
        else {
            self::error("Modification impossible : la voiture demandée n'exsite pas");
        }
    }

    public static function updated() : void {
        $immatriculation = $_GET['immatriculation'] ?? null;
        $marque = $_GET['marque'] ?? null;
        $couleur = $_GET['couleur'] ?? null;
        $NbSieges = $_GET['nbSieges'] ?? null;

        if ($immatriculation && $marque && $couleur && $NbSieges) {
            $voiture = new Voiture($immatriculation, $marque, $couleur, $NbSieges);
            (new VoitureRepository())->update($voiture);
            MessageFlash::ajouter("success", "La voiture a bien été mise à jour !");
            self::redirectionVersURL("frontController.php?controller=voiture&action=readAll");
        }
        else {
            MessageFlash::ajouter("danger", "Immatriculation, marque, couleur ou nombre de sièges manquant.");
            self::redirectionVersURL("frontController.php?controller=voiture&action=update&immatriculation=" . urlencode($immatriculation ?? ""));
        }
    }
    
    public static function delete() : void {
        // On récupère l'immatriculation donnée dans l'URL
        $immatriculation = $_GET['immatriculation'] ?? null;
        
        if($immatriculation !== NULL) {
            (new VoitureRepository())->delete($immatriculation) ;
            MessageFlash::ajouter("success", "La voiture a bien été supprimée !");
        }
        else {
            MessageFlash::ajouter("danger", "Impossible de supprimer la voiture : aucune immatriculation spécifiée.");   
        }
        self::redirectionVersURL("frontController.php?controller=voiture&action=readAll");
    }

}
