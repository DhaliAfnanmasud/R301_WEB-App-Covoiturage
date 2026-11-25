/* ==========================================================
   animations.js : à placer dans assets/js/ et à charger avec "defer"
   ========================================================== */
(function () {
  "use strict";

  // Indique au petit script du <head> que ce fichier est bien chargé
  window.animationsPretes = true;

  const reduireMouvement = window.matchMedia(
    "(prefers-reduced-motion: reduce)",
  ).matches;

  /* ---------- 1. Apparition progressive ---------- */
  function initApparition() {
    const cibles = document.querySelectorAll(
      "main > *:not(form):not(table), main form p, main tbody tr",
    );

    // Pas d'animation possible ou souhaitée : tout est visible tout de suite
    if (reduireMouvement || !("IntersectionObserver" in window)) {
      cibles.forEach((el) => el.classList.add("is-visible"));
      return;
    }

    const observateur = new IntersectionObserver(
      (entrees) => {
        // On traite les éléments dans l'ordre de la page, pour un décalage cohérent
        entrees
          .filter((e) => e.isIntersecting)
          .sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top)
          .forEach((entree, rang) => {
            // Décalage de 60 ms par élément, plafonné pour ne pas faire attendre
            entree.target.style.setProperty(
              "--d",
              Math.min(rang, 8) * 60 + "ms",
            );
            entree.target.classList.add("is-visible");
            observateur.unobserve(entree.target);
          });
      },
      { threshold: 0.1, rootMargin: "0px 0px -5% 0px" },
    );

    cibles.forEach((el) => observateur.observe(el));
  }

  /* ---------- 2. Bandeaux de messages flash ---------- */
  function fermerAlerte(alerte) {
    if (alerte.classList.contains("alert--leaving")) return;

    // On fige la hauteur actuelle pour pouvoir l'animer jusqu'à 0
    alerte.style.maxHeight = alerte.offsetHeight + "px";
    void alerte.offsetHeight; // force le navigateur à enregistrer cette hauteur
    alerte.classList.add("alert--leaving");

    alerte.addEventListener("transitionend", (e) => {
      if (e.propertyName === "max-height") alerte.remove();
    });
    // Filet de sécurité si aucune transition ne se déclenche
    setTimeout(() => alerte.remove(), 900);
  }

  function initAlertes() {
    document.querySelectorAll(".alert").forEach((alerte) => {
      alerte.setAttribute(
        "role",
        alerte.classList.contains("alert-danger") ? "alert" : "status",
      );
      alerte.title = "Cliquer pour fermer";

      // Fin de la barre de temps = fermeture automatique
      alerte.addEventListener("animationend", (e) => {
        if (e.animationName === "alerte-minuteur") fermerAlerte(alerte);
      });
      // Fermeture manuelle
      alerte.addEventListener("click", () => fermerAlerte(alerte));
    });
  }

  /* ---------- 3. Retour visuel sur les formulaires ---------- */
  const CHAMPS =
    "input:not([type=hidden]):not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]), select, textarea";

  function afficherMessage(champ, texte) {
    if (!champ._message) {
      const span = document.createElement("span");
      span.className = "field-message";
      span.setAttribute("aria-live", "polite");
      champ.insertAdjacentElement("afterend", span);
      champ._message = span;
    }
    if (champ._message.textContent !== texte) {
      champ._message.textContent = texte;
    }
  }

  function marquerInvalide(champ) {
    champ.classList.remove("is-valid");
    // On retire puis remet la classe pour rejouer la secousse
    champ.classList.remove("is-invalid");
    void champ.offsetWidth;
    champ.classList.add("is-invalid");
    afficherMessage(champ, champ.validationMessage);
  }

  function marquerValide(champ) {
    champ.classList.remove("is-invalid");
    champ.classList.add("is-valid");
    afficherMessage(champ, "");
  }

  function reinitialiser(champ) {
    champ.classList.remove("is-valid", "is-invalid");
    afficherMessage(champ, "");
  }

  function evaluer(champ) {
    // Un champ vide et jamais touché reste neutre
    if (champ.value === "" && !champ.dataset.tentative) {
      reinitialiser(champ);
      return;
    }
    champ.checkValidity() ? marquerValide(champ) : marquerInvalide(champ);
  }

  function initFormulaires() {
    document.querySelectorAll("form").forEach((formulaire) => {
      // Quand on quitte un champ
      formulaire.addEventListener("focusout", (e) => {
        if (e.target.matches(CHAMPS)) evaluer(e.target);
      });

      // Pendant la saisie, on met à jour seulement un champ déjà évalué
      formulaire.addEventListener("input", (e) => {
        const champ = e.target;
        if (
          champ.matches(CHAMPS) &&
          (champ.classList.contains("is-valid") ||
            champ.classList.contains("is-invalid"))
        ) {
          evaluer(champ);
        }
      });

      // Tentative d'envoi avec un champ invalide (l'événement ne remonte pas : phase de capture)
      let focusDonne = false;
      formulaire.addEventListener(
        "invalid",
        (e) => {
          const champ = e.target;
          if (!champ.matches(CHAMPS)) return;
          e.preventDefault(); // on remplace la bulle native par notre message
          champ.dataset.tentative = "1";
          marquerInvalide(champ);
          if (!focusDonne) {
            focusDonne = true;
            champ.focus();
            setTimeout(() => (focusDonne = false), 0);
          }
        },
        true,
      );
    });
  }

  /* ---------- Démarrage ---------- */
  function init() {
    initApparition();
    initAlertes();
    initFormulaires();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
