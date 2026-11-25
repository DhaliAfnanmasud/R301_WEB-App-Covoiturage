<?php

namespace App\Covoiturage\Config;

class Conf {

    static private array $databases = array(
        // Le nom d'hote est localhost sur votre machine
        'hostname' => 'localhost',
        // Sur votre machine, vous devrez creer une BDD
        'database' => 'r3.01_web_dhali',
        // Sur votre machine, vous avez surement un compte 'root'
        'login' => 'root',
        // Sur votre machine, vous avez créé ou non ce mdp a l'installation
        'password' => 'j7eRJL7fAZnz'
    );

    static private int $dureeExpirationSession = 1800;   // 30 minutes, en secondes

    // Getters
    static public function getHostname() : string {
        // L'attribut statique $databases s'obtient avec la syntaxe static::$databases
        // au lieu de $this->databases pour un attribut non statique
        return static::$databases['hostname'];
    }

    static public function getDatabase() : string {
        // L'attribut statique $databases s'obtient avec la syntaxe static::$databases
        // au lieu de $this->databases pour un attribut non statique
        return static::$databases['database'];
    }

    static public function getLogin() : string {
        // L'attribut statique $databases s'obtient avec la syntaxe static::$databases
        // au lieu de $this->databases pour un attribut non statique
        return static::$databases['login'];
    }

    static public function getPassword() : string {
        // L'attribut statique $databases s'obtient avec la syntaxe static::$databases
        // au lieu de $this->databases pour un attribut non statique
        return static::$databases['password'];
    }

    static public function getDureeExpirationSession() : int {
        return static::$dureeExpirationSession;
    }
}