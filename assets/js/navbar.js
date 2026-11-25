(function () {
  "use strict";

  const nav = document.querySelector("header nav");
  if (!nav) return;

  const liens = Array.from(nav.querySelectorAll("a"));
  if (liens.length === 0) return;

  const indicateur = document.createElement("span");
  indicateur.className = "nav-indicateur";
  indicateur.setAttribute("aria-hidden", "true");
  nav.appendChild(indicateur);

  // Quel lien correspond à la page affichée ?
  function estActif(lien) {
    const l = new URL(lien.href, location.href).searchParams;
    const p = new URLSearchParams(location.search);
    // Liens "Gestion des ..." : actifs sur toutes les pages de ce contrôleur
    if (l.get("action") === "readAll") {
      return l.get("controller") === p.get("controller");
    }
    // Autres liens (préférences, connexion, profil) : même page exacte
    return ["controller", "action", "login"].every(
      (cle) => l.get(cle) === p.get(cle),
    );
  }

  const lienActif = liens.find(estActif) || null;
  if (lienActif) lienActif.setAttribute("aria-current", "page");

  let survole = null;

  function placer(lien) {
    if (!lien) {
      indicateur.style.opacity = "0";
      return;
    }
    const n = nav.getBoundingClientRect();
    const r = lien.getBoundingClientRect();
    indicateur.style.width = r.width + "px";
    indicateur.style.transform =
      "translateX(" + (r.left - n.left - nav.clientLeft) + "px)";
    indicateur.style.opacity = "1";
  }

  function actualiser() {
    placer(survole || lienActif);
  }

  liens.forEach((lien) => {
    lien.addEventListener("mouseenter", () => {
      survole = lien;
      actualiser();
    });
    lien.addEventListener("focus", () => {
      survole = lien;
      actualiser();
    });
    lien.addEventListener("blur", () => {
      survole = null;
      actualiser();
    });
  });

  nav.addEventListener("mouseleave", () => {
    survole = null;
    actualiser();
  });

  window.addEventListener("resize", actualiser);

  // Premier placement sans animation, puis on active la transition
  actualiser();
  void indicateur.offsetWidth;
  indicateur.classList.add("anime");

  // La police Inter peut décaler les liens une fois chargée
  if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(actualiser);
  }
})();
