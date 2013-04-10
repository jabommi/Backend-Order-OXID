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


License
============

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this program; if not, see http://www.gnu.org/licenses/
