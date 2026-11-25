<?php
/** @var string $pagetitle */
/** @var string $cheminVueBody */
/** @var array $messagesFlash */

use App\Covoiturage\Lib\ConnexionUtilisateur;

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= htmlspecialchars($pagetitle) ?></title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/animations.css">
        
        <script>
        (function () {
            var racine = document.documentElement;
            racine.classList.add('js');
            var theme = null;
            try { theme = localStorage.getItem('theme'); } catch (e) {}
            if (theme !== 'light' && theme !== 'dark') {
                theme = window.matchMedia && matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            racine.setAttribute('data-theme', theme);
            setTimeout(function () {
                if (!window.animationsPretes) racine.classList.remove('js');
             }, 1500);
        })();
        </script>
        
        <script src="../assets/js/theme.js" defer></script>
        <script src="../assets/js/animations.js" defer></script>
        <script src="../assets/js/navbar.js" defer></script>
    </head>
    <body>
        <header>
            <nav>
                <div class="nav-gauche">
                    <button type="button" id="theme-toggle" class="theme-toggle" aria-pressed="false" aria-label="Passer en mode sombre" title="Passer en mode sombre">
                        <svg class="icone-lune" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>
                        </svg>
                        <svg class="icone-soleil" viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="4"/>
                            <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
                        </svg>
                    </button>
                </div>

                <ul class="nav-centre">
                    <li><a href="frontController.php?controller=voiture&action=readAll">Gestion des voitures</a></li>
                    <li><a href="frontController.php?controller=utilisateur&action=readAll">Gestion des utilisateurs</a></li>
                    <li><a href="frontController.php?controller=trajet&action=readAll">Gestion des trajets</a></li>
                    <li><a href="frontController.php?action=formulairePreference"><img src="../assets/img/heart.png" alt="Préférences" height="25"></a></li>
                </ul>

                <ul class="nav-droite">
                    <?php if (!ConnexionUtilisateur::estConnecte()) { ?>
                        <li>
                            <a href="frontController.php?controller=utilisateur&action=formulaireConnexion" title="Connexion">
                                <img src="../assets/img/connexion.png" alt="Connexion" height="25">
                            </a>
                        </li>
                    <?php } else { ?>
                        <li>
                            <a href="frontController.php?controller=utilisateur&action=read&login=<?= rawurlencode(ConnexionUtilisateur::getLoginUtilisateurConnecte()) ?>" title="Mon profil" aria-label="Mon profil">
                                <img src="../assets/img/utilisateur.png" alt="Mon profil" height="20">
                            </a>
                        </li>
                        <li>
                            <a href="frontController.php?controller=utilisateur&action=deconnecter" title="Déconnexion" aria-label="Déconnexion">
                                <img src="../assets/img/deconnexion.png" alt="Se déconnecter" height="23">
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </header>
        
        <?php
        foreach ($messagesFlash as $type => $messagesFlashPourUnType) {
            foreach ($messagesFlashPourUnType as $messageFlash) {
                echo '<div class="alert alert-' . $type . '">' . htmlspecialchars($messageFlash) . '</div>';
            }
        }
        ?>

        <main>
            <?php
            require __DIR__ . "/{$cheminVueBody}";
            ?>
        </main>
        
        <footer>
            <p> Site de covoiturage de Afnaan</p>
        </footer>
    </body>
</html>