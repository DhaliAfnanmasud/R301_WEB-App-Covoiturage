<?php

require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

use App\Covoiturage\Controller\ControllerVoiture;   
use App\Covoiturage\Lib\Psr4AutoloaderClass;
use App\Covoiturage\Lib\PreferenceControleur;

// instantiate the loader
$loader = new Psr4AutoloaderClass();
// register the base directories for the namespace prefix
$loader->addNamespace('App\Covoiturage', __DIR__ . '/../src');
// register the autoloader
$loader->register();

// On vérifie le controller passé dans l'URL avec isset() 
if (isset($_GET['controller'])){
    $controller = $_GET['controller']; 
}
elseif (PreferenceControleur::existe()) {
    $controller = PreferenceControleur::lire();
}
else {
    $controller = 'voiture';
}

// On construit le nom de la classe avec ucfirst 
$controllerClassName = 'App\Covoiturage\Controller\Controller' . ucfirst($controller);

// On vérifie l'action passée dans l'URL avec isset()
if (isset($_GET['action'])){
    $action = $_GET['action']; 
}
else {
    $action = 'readAll';
}

// On vérifie si la classe existe et si la méthode existe dedans
if (class_exists($controllerClassName)) {
    if (in_array($action, get_class_methods($controllerClassName))) {
        // On appelle dynamiquement l'action du bon contrôleur
        $controllerClassName::$action();
    }
    else {
        // Si l'action n'existe pas, on appelle l'action error du contrôleur voiture
        ControllerVoiture::error("L'action '$action' n'existe pas !");
    }
}
else {
    // Si la classe du contrôleur n'existe pas du tout
    ControllerVoiture::error("Le contrôleur '$controller' n'existe pas !");
}

?>