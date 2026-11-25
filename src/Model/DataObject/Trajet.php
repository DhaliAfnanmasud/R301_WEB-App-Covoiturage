<?php

namespace App\Covoiturage\Model\DataObject;

use App\Covoiturage\Model\Repository\DatabaseConnection;
use Override;

class Trajet extends AbstractDataObject{

    // Attributs
    private ?int $id;
    private string $depart;
    private string $arrivee;
    private string $date;
    private int $nbPlaces;
    private float $prix;
    private string $conducteurLogin;

    // Constructeurs
    public function __construct(?int $id = null, string $depart, string $arrivee, string $date, int $nbPlaces, float $prix, string $conducteurLogin) {
        $this->id = $id;
        $this->depart = $depart;
        $this->arrivee = $arrivee;
        $this->date = $date;
        $this->nbPlaces = $nbPlaces;
        $this->prix = $prix;
        $this->conducteurLogin = $conducteurLogin;
    }


    // Getters et Setters
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(string $id): void {
        $this->id = $id;
    }

    public function getDepart(): string {
        return $this->depart;
    }

    public function setDepart(string $depart): void {
        $this->depart = $depart;
    }

    public function getArrivee(): string {
        return $this->arrivee;
    }

    public function setArrivee(string $arrivee): void {
        $this->arrivee = $arrivee;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function setDate(string $date): void {
        $this->date = $date;
    }

    public function getNbPlaces(): int {
        return $this->nbPlaces;
    }

    public function setNbPlaces(int $nbPlaces): void {
        $this->nbPlaces = $nbPlaces;
    }

    public function getPrix(): float {
        return $this->prix;
    }

    public function setPrix(float $prix): void {
        $this->prix = $prix;
    }

    public function getConducteurLogin(): string {
        return $this->conducteurLogin;
    }

    public function setConducteurLogin(string $conducteurLogin): void {
        $this->conducteurLogin = $conducteurLogin;
    }


    // Méthode abstraite
    #[Override]
    public function formatTableau(): array {
        return array (
            "idTag" => $this->id,
            "departTag" => $this->depart,
            "arriveeTag" => $this->arrivee,
            "dateTag" => $this->date,
            "nbPlacesTag" => $this->nbPlaces,
            "prixTag" => $this->prix,
            "conducteurLoginTag" => $this->conducteurLogin,
        );
    }

    // Méthode
    public function __toString(): string {
        return "Trajet n°" . $this->id . " allant de " . $this->depart . " à " . $this->arrivee . " le " . $this->date ."<br>" . $this->nbPlaces . " places disponibles <br> Conducteur : " . $this->conducteurLogin . " <br><strong>Prix : " . $this->prix . " €</strong>";   
    }
}
