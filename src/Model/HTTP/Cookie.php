<?php

namespace App\Covoiturage\Model\HTTP;

class Cookie {
    public static function enregistrer(string $cle, mixed $valeur, ?int $dureeExpiration = null): void {
        
        // Les cookies ne contient que des valeurs string : on convertit donc avec serialize
        $valeurSerialisee = serialize($valeur);

        if ($dureeExpiration === null) {
            //  0  = le cookie sera supprimé à la fin de la session
            setcookie($cle, $valeurSerialisee, 0);
        }
        else {
            setcookie($cle, $valeurSerialisee, time() + $dureeExpiration);   
        }

    }

    public static function lire(string $cle): mixed {
        return unserialize($_COOKIE[$cle]);
    }

    public static function contient(string $cle) : bool {
        return isset($_COOKIE[$cle]);  
    }

    public static function supprimer(string $cle) : void {
        // on efface un cookie lu par le client avec unset
        unset($_COOKIE[$cle]);
        
        //  on le supprime chez le client en le faisant expirer, en lui mettant une date d’expiration passée
        setcookie ($cle, "", 1);
    }


}   

