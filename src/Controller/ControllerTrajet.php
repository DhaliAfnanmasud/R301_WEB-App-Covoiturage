<?php

namespace App\Covoiturage\Controller;

use App\Covoiturage\Lib\MessageFlash;
use App\Covoiturage\Model\DataObject\Trajet;  // chargement de la classe Utilisateur
use App\Covoiturage\Model\Repository\TrajetRepository;

class ControllerTrajet extends GenericController {

    public static function create() : void {
        self::afficheVue("trajet/create.php", [
            "pagetitle" => "Créer un trajet",
            "cheminVueBody" => "trajet/create.php"
        ]);
    }

    public static function created() : void {
        $depart = $_GET['depart'] ?? null;
        $arrivee = $_GET['arrivee'] ?? null;
        $date = $_GET['date'] ?? null;
        $nbPlaces = $_GET['nbPlaces'] ?? null;
        $prix = $_GET['prix'] ?? null;
        $conducteurLogin = $_GET['conducteurLogin'] ?? null;

        if ($depart && $arrivee && $date && $nbPlaces && $prix !== '' && $prix !== null && $conducteurLogin) {
            $trajet = new Trajet(null, $depart, $arrivee, $date, $nbPlaces, $prix, $conducteurLogin);
            (new TrajetRepository())->save($trajet);
            MessageFlash::ajouter("success", "Le trajet a bien été créé !");
            self::redirectionVersURL("frontController.php?controller=trajet&action=readAll");
        }
        else {
            MessageFlash::ajouter("danger", "Un ou plusieurs champs du trajet sont manquants.");
            self::redirectionVersURL("frontController.php?controller=trajet&action=create");
        }
    }

    public static function readAll() : void {
        $trajets = (new TrajetRepository())->selectAll(); //appel au repository pour gerer la BDD
        self::afficheVue("trajet/list.php", [
            "trajets" => $trajets,
            "pagetitle" => "Liste des trajets",
            "cheminVueBody" => "trajet/list.php"]); //"redirige" vers la vue afin d'afficher la liste des trajets
    }

    public static function read() : void {
        // On récupère l'immatriculation donnée dans l'URL
        $id = $_GET['id'] ?? null;
        
        if($id !== NULL) {
            //On appelle la fonction getVoitureParImmat() du modèle
            $trajet = (new TrajetRepository())->select($id);
            if ($trajet !== NULL) {
                $passagers = TrajetRepository::getPassagers($id);
                self::afficheVue("trajet/detail.php", [
                    "trajet" => $trajet,
                    "passagers" => $passagers,
                    "pagetitle" => "Détail du trajet",
                    "cheminVueBody" => "trajet/detail.php"]); //"redirige" vers la vue afin d'afficher les détails du trajet sélectionné
            }
            else {
                self::error("Le trajet demandé n'existe pas !");
            }
        }
        else {
            self::error("L'ID du trajet n'a pas été fournie dans l'URL");
        }
    }

    public static function update() : void {
        // On récupère l'immatriculation donnée dans l'URL
        $id = $_GET['id'] ?? null;
        $trajet = (new TrajetRepository())->select($id);
        
        if($trajet !== NULL) {
            self::afficheVue("trajet/update.php", [
                "trajet" => $trajet,
                "pagetitle" => "Mise à jour du trajet",
                "cheminVueBody" => "trajet/update.php"
            ]);
        }
        else {
            self::error("Modification impossible : le trajet demandé n'exsite pas");
        }
    }

    public static function updated() : void {
        $id = $_GET['id'] ?? null;
        $depart = $_GET['depart'] ?? null;
        $arrivee = $_GET['arrivee'] ?? null;
        $date = $_GET['date'] ?? null;
        $nbPlaces = $_GET['nbPlaces'] ?? null;
        $prix = $_GET['prix'] ?? null;
        $conducteurLogin = $_GET['conducteurLogin'] ?? null;

        if ($depart && $arrivee && $date && $nbPlaces && $prix !== '' && $prix !== null && $conducteurLogin) {
            $trajet = new Trajet($id, $depart, $arrivee, $date, $nbPlaces, $prix, $conducteurLogin);
            (new TrajetRepository())->update($trajet);
            MessageFlash::ajouter("success", "Le trajet a bien été mis à jour !");
            self::redirectionVersURL("frontController.php?controller=trajet&action=readAll");
        }
        else {
            MessageFlash::ajouter("danger", "Un ou plusieurs champs du trajet sont manquants.");
            self::redirectionVersURL("frontController.php?controller=trajet&action=update&id=" . urlencode($id ?? ""));
        }
    }

    public static function delete() : void {
        // On récupère l'immatriculation donnée dans l'URL
        $id = $_GET['id'] ?? null;
        
        if($id !== NULL) {
            (new TrajetRepository())->delete($id) ;
            MessageFlash::ajouter("success", "Le trajet a bien été supprimé !");
        }
        else {
            MessageFlash::ajouter("danger", "Impossible de supprimer le trajet : aucun trajet spécifié.");
        }

        self::redirectionVersURL("frontController.php?controller=trajet&action=readAll");
    }

    public static function deletePassagerFromTrajet() : void {
        $trajetId = $_GET['id'] ?? null;
        $passagerLogin = $_GET['login'] ?? null;

        if ($trajetId && $passagerLogin) {
            TrajetRepository::supprimerPassager((int)$trajetId, $passagerLogin);
            MessageFlash::ajouter("success", "Le passager a bien été désinscrit du trajet !");
            self::redirectionVersURL("frontController.php?controller=trajet&action=read&id=" . urlencode($trajetId));
        } 
        else {
            MessageFlash::ajouter("danger", "Paramètres manquants pour la désinscription.");
            self::redirectionVersURL("frontController.php?controller=trajet&action=readAll");
        }
    }
}