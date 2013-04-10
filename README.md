Backend Order OXID
=============

OXID-ESALES: deaktiviert Neuberechnung der Preise, Rabatte, Versandkosten bei Bestellaktualisierung im Backend 

Oxid berechnet standardmäßig die Preise, Rabatte, und Versandkosten neu, sobald man im Backend die Bestellung aktualisiert.
Dies kann dazu führen, daß sich der Gesamtbetrag der Bestellung ändert, da der Artikel z.B. mittlerweile
einen anderen Preis hat oder zeitlich begrenzte Rabatte, die zum Zeitpunkt der Bestellung aktiv waren, nicht mehr gelten.

Bezogen auf Bug: https://bugs.oxid-esales.com/view.php?id=4624

Entwickelt für Version 4.6.1. Andere Versionen wurden nicht getestet.

Version: 0.5
-nur Preise bleiben konstant. Rabatt und Versandkosten werden noch neuberechnet
