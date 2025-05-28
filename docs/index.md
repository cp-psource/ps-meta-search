---
layout: psource-theme
title: "PS Meta Suche"
---

<h2 align="center" style="color:#38c2bb;">📚 PS Meta Suche</h2>

<div class="menu">
  <a href="https://github.com/cp-psource/ps-meta-search/releases" style="color:#38c2bb;">💬 Forum</a>
  <a href="https://github.com/cp-psource/cp-community/releases" style="color:#38c2bb;">📝 Download</a>
</div>

# Datenschutzfreundliche Meta-Suche 🔍

Ein einfaches, anonymes Such-Plugin für WordPress mit Fokus auf Datenschutz und Usability.

## ✨ Features

- Suchfeld mit Auswahl zwischen:
  - DuckDuckGo
  - Wayback Machine
  - MetaGer (mit API)
- Ergebnisse direkt im Beitrag anzeigen
- Alternativ: direkter Link zur externen Suche
- Statistik zur Nutzung der Suche
- REST-API für Integration oder Erweiterungen
- Transient-Caching (nur bei MetaGer)

## 🛠️ Installation

1. Plugin hochladen oder ins `wp-content/plugins`-Verzeichnis kopieren
2. Aktivieren
3. (Optional) API-Key für MetaGer unter „Einstellungen → Meta-Suche“ einfügen
4. Shortcode `[ps_meta_suche]` einfügen

## 🔧 Shortcode

```php
[ps_meta_suche]