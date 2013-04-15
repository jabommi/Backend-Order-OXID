Backend Order OXID
=============

OXID-ESALES: deaktiviert Neuberechnung der Preise, Rabatte, Versandkosten bei Bestellaktualisierung im Backend 

Oxid berechnet standardmäßig die Preise, Rabatte, und Versandkosten neu, sobald man im Backend die Bestellung aktualisiert.
Dies kann dazu führen, daß sich der Gesamtbetrag der Bestellung ändert, da der Artikel z.B. mittlerweile
einen anderen Preis hat oder zeitlich begrenzte Rabatte, die zum Zeitpunkt der Bestellung aktiv waren, nicht mehr gelten.
Mit diesem Modul kann man dieses oft unerwünschte Verhalten deaktivieren.

Bezogen auf Bug: https://bugs.oxid-esales.com/view.php?id=4624

Entwickelt für Oxid Esales Version 4.6.1. Andere Versionen wurden nicht getestet.

Forum: http://forum.oxid-esales.com/showthread.php?t=18930

Version: 1.0
============
Es kann nun seperat voneinander eingestellt werden, ob Artikelpreise, Rabatte, Gutscheine, Versandkosten, Zahlartabschläge, Verpackungskosten und TS Protection neuberechnet werden. 


Version: 0.6
============
Es kann nun seperat voneinander eingestellt werden, ob Artikelpreise, Rabatte oder Gutscheine neuberechnet werden. Versandkosten, Zahlartabschläge, Verpackungskosten und TS Protection werden noch neuberechnet.


Version: 0.5
============
Nur Artikelpreise bleiben konstant. Rabatte und Versandkosten werden noch neuberechnet


License
============

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this program; if not, see http://www.gnu.org/licenses/
