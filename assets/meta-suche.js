document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("ds-suche-form");
  const suchmaschine = document.getElementById("ds-suchmaschine");
  const suchbegriff = document.getElementById("ds-suchbegriff");
  const ergebnisse = document.getElementById("ds-suchergebnisse");
  const externerLink = document.getElementById("ds-externer-link");
  const trefferInfo = document.getElementById("ds-trefferanzahl");

  let aktuelleSeite = 1;
  let letzteSuche = "";

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const rawQuery = suchbegriff.value.trim();
    const query = encodeURIComponent(rawQuery);
    const dienst = suchmaschine.value;

    if (!query) return;

    // Link generieren
    let suchURL = "";
    switch (dienst) {
      case "duckduckgo":
        suchURL = `https://duckduckgo.com/?q=${query}`;
        break;
      case "metager":
        suchURL = `https://metager.org/meta/meta.ger3?eingabe=${query}`;
        break;
      case "wayback":
        suchURL = `https://web.archive.org/web/*/${query}`;
        break;
    }

    externerLink.innerHTML = `<a href="${suchURL}" target="_blank" rel="noopener noreferrer">🔎 In Deinem Browser suchen</a>`;

    // Zurücksetzen
    ergebnisse.innerHTML = "⏳ Suche läuft...";
    trefferInfo.innerHTML = "";

    if (dienst === "metager") {
      ladeMetaGerErgebnisse(rawQuery);
      return;
    }

    if (dienst === "duckduckgo") {
      trefferInfo.innerHTML = "⚠️ DuckDuckGo erlaubt keinen Zugriff auf Trefferanzahl.";
      ergebnisse.innerHTML = `
        <p>Bitte nutze den externen Link zur Suche.</p>
      `;
      return;
    }

    if (dienst === "wayback") {
      const isURL = /^https?:\/\/|^www\./i.test(rawQuery);
      if (!isURL) {
        ergebnisse.innerHTML = `
          <p>⚠️ Die Wayback Machine funktioniert nur mit vollständigen URLs.</p>
        `;
        trefferInfo.innerHTML = "";
        return;
      }

      fetch(`/wp-json/ds-meta-suche/v1/wayback?url=${query}`)
        .then(res => res.json())
        .then(data => {
          const count = data.length - 1;
          trefferInfo.innerHTML = `📦 Archivierte Versionen: <strong>${count}</strong>`;
          ergebnisse.innerHTML = `<p>Nutze den Link unten zur Anzeige auf web.archive.org.</p>`;
        })
        .catch(() => {
          ergebnisse.innerHTML = "⚠️ Fehler beim Abrufen der Archivdaten.";
        });

      return;
    }

    // Fallback-Hinweis
    ergebnisse.innerHTML = "💡 Vorschau nur mit MetaGer + API-Key möglich.";
  });

  function ladeMetaGerErgebnisse(query, seite = 1) {
    fetch(`/wp-json/ds-meta-suche/v1/suche?q=${encodeURIComponent(query)}&page=${seite}`)
      .then(res => res.json())
      .then(data => {
        if (!data.results || !data.results.length) {
          ergebnisse.innerHTML = "⚠️ Keine Treffer gefunden.";
          trefferInfo.innerHTML = "";
          return;
        }

        aktuelleSeite = data.page;
        letzteSuche = query;

        trefferInfo.innerHTML = `🔎 MetaGer Treffer: <strong>${data.total}</strong>`;

        const list = document.createElement("ul");
        list.classList.add("ds-suchergebnis-liste");

        data.results.forEach(item => {
          const li = document.createElement("li");
          li.innerHTML = `<strong><a href="${item.url}" target="_blank" rel="noopener noreferrer">${item.title}</a></strong><p>${item.desc}</p>`;
          list.appendChild(li);
        });

        // Pagination
        const nav = document.createElement("div");
        nav.className = "ds-pager";
        if (data.page > 1) {
          const prev = document.createElement("button");
          prev.textContent = "← Zurück";
          prev.onclick = () => ladeMetaGerErgebnisse(query, data.page - 1);
          nav.appendChild(prev);
        }
        if (data.page < data.pages) {
          const next = document.createElement("button");
          next.textContent = "Weiter →";
          next.onclick = () => ladeMetaGerErgebnisse(query, data.page + 1);
          nav.appendChild(next);
        }

        ergebnisse.innerHTML = "";
        ergebnisse.appendChild(list);
        ergebnisse.appendChild(nav);
      })
      .catch(() => {
        ergebnisse.innerHTML = "⚠️ Fehler beim Abrufen der Ergebnisse.";
        trefferInfo.innerHTML = "";
      });
  }
});

