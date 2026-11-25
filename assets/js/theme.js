/* ==========================================================
   theme.js : à placer dans assets/js/ et à charger avec "defer"
   Le thème initial est posé plus tôt par le script du <head> (pas de flash).
   Ce fichier gère le bouton et la mémorisation du choix.
   ========================================================== */
(function () {
  "use strict";

  const racine = document.documentElement;
  const bouton = document.getElementById("theme-toggle");
  const CLE = "theme";

  function lireChoix() {
    try {
      const valeur = localStorage.getItem(CLE);
      return valeur === "light" || valeur === "dark" ? valeur : null;
    } catch (e) {
      return null; // stockage indisponible (navigation privée, etc.)
    }
  }

  function memoriser(theme) {
    try {
      localStorage.setItem(CLE, theme);
    } catch (e) {
      /* on ignore : le choix vaudra pour cette page seulement */
    }
  }

  function themeActuel() {
    return racine.getAttribute("data-theme") === "dark" ? "dark" : "light";
  }

  function mettreAJourBouton() {
    if (!bouton) return;
    const sombre = themeActuel() === "dark";
    bouton.setAttribute("aria-pressed", sombre ? "true" : "false");
    const libelle = sombre ? "Passer en mode clair" : "Passer en mode sombre";
    bouton.setAttribute("aria-label", libelle);
    bouton.title = libelle;
  }

  function appliquer(theme, avecFondu) {
    if (avecFondu) {
      // Fondu doux des couleurs pendant 400 ms, puis on retire la classe
      racine.classList.add("theme-transition");
      setTimeout(() => racine.classList.remove("theme-transition"), 400);
    }
    racine.setAttribute("data-theme", theme);
    mettreAJourBouton();
  }

  if (bouton) {
    bouton.addEventListener("click", () => {
      const suivant = themeActuel() === "dark" ? "light" : "dark";
      appliquer(suivant, true);
      memoriser(suivant);
    });
  }

  // Sans choix enregistré, on suit le réglage du système en direct
  if (window.matchMedia) {
    window
      .matchMedia("(prefers-color-scheme: dark)")
      .addEventListener("change", (e) => {
        if (lireChoix() === null) appliquer(e.matches ? "dark" : "light", true);
      });
  }

  mettreAJourBouton();
})();
