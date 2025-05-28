=== Datenschutzfreundliche Meta-Suche ===
Contributors: dernerd  
Tags: suche, suchmaschine, datenschutz, duckduckgo, metager, wayback, anonym  
Requires at least: 6.0  
Tested up to: 6.8.1  
Requires PHP: 7.4  
Stable tag: 1.0.0  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

Eine einfache, datenschutzfreundliche Meta-Suchfunktion per Shortcode – mit Auswahl an Suchmaschinen, Vorschau der Ergebnisse und direktem Link zur externen Suche.

== Description ==

Dieses Plugin stellt einen Shortcode bereit, der ein Suchfeld mit Auswahl an datenschutzfreundlichen Suchdiensten einfügt.  
Es werden keine Daten gespeichert oder getrackt. Ergebnisse können direkt angezeigt oder extern geöffnet werden.  

**Unterstützte Dienste:**
- DuckDuckGo
- Wayback Machine
- MetaGer (mit API-Key)

**Funktionen:**
- Dropdown zur Auswahl der Suchmaschine
- Ergebnisanzeige direkt im Beitrag/auf der Seite
- Externe Suche optional im neuen Tab
- Statistik über Anzahl der Suchanfragen
- REST-API für erweiterte Nutzung
- Caching der Ergebnisse (nur bei MetaGer)

== Installation ==

1. Plugin hochladen und aktivieren.
2. Optional: API-Key für MetaGer unter „Einstellungen → Meta-Suche“ einfügen.
3. Shortcode `[ps_meta_suche]` einfügen, wo du die Suche anzeigen möchtest.

== Shortcode ==

`[ps_meta_suche]`

== Frequently Asked Questions ==

= Muss ich einen MetaGer API-Key eintragen? =  
Nur wenn du echte Suchergebnisse von MetaGer im Plugin anzeigen willst. DuckDuckGo und Wayback funktionieren ohne API.

= Werden Suchanfragen protokolliert? =  
Nein. Es wird nur anonym gezählt, wie oft eine bestimmte Suchanfrage gestellt wurde.

= Funktioniert das Plugin mit Caching-Plugins? =  
Ja, die API und JavaScript-Funktionalität läuft unabhängig vom Seiten-Cache.

== Screenshots ==

1. Eingabemaske mit Dropdown-Auswahl
2. Suchergebnisse direkt eingebettet
3. Einstellungen im WordPress-Adminbereich

== Changelog ==

= 1.0.0 =
* Erste Version mit Unterstützung für DuckDuckGo, Wayback und MetaGer.
* REST-Endpunkte, Shortcode, Admin-Einstellungen und Statistikfunktion.

== Upgrade Notice ==

= 1.0.0 =
Erstveröffentlichung.