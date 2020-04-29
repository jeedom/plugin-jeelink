Jeelink 
=======

Beschreibung 
-----------

Plugin zum Verknüpfen von 2 Jeedoms

Funktionsprinzip 
==========================

Das * Jeedom Link * Plugin (auch Jeelink genannt) ermöglicht den Aufstieg von a
oder mehr Ausrüstung (en) von einem Jeedom zum anderen.

![jeelink1](../images/jeelink1.png)

> **Important**
>
> Das Plugin muss auf allen Jeedoms, QuelDie installiert sein
> und Ziel.

> **Tip**
>
> Für ein gutes Verständnis ist es wichtig, das zu verstehen
> einige folgende Begriffe :\.
> \.
> **Jeedom Quelle** : Jeedom Server auf dem die
> Ausrüstung, die auf dem wieder zusammengebaut werden soll **Jeedom Ziel**\.
> \.
> **Jeedom Ziel** : Jeedom-Server, der die wieder zusammengebauten Geräte erhält
> von den (s) **Jeedom (s) QuelDie (n)**\.
> Die **Jeedom Ziel** Zentralisieren Sie diese Ausrüstung und die aller Personen.
> **Jeedom (s) QuelDie (n)** konfiguriert (en). \.
> \.
> **Affectation** : Konfiguration durchgeführt am **Jeedom Quelle**
> um die Ausrüstung einzuschließen, die auf dem wieder zusammengebaut wird **Jeedom
> Ziel**

> **Note**
>
> Zum besseren Diesen und Verstehen dieses Tutorials :\.
> \.
> Die Screenshots auf schwarzem Hintergrund entsprechen dem **Jeedom Ziel**.\.
> \.
> Die Screenshots auf weißem Hintergrund entsprechen **Jeedom Quelle**.\.

Plugin Konfiguration 
=======================

Nach der Installation müssen Sie nur das Plugin aktivieren. Dieser tut es
erfordert keine spezifische Konfiguration.

Konfiguration der Ziel-Jeedome 
================================

Von **Jeedom Quelle**, einmal auf der Plugin-Seite (indem Sie gehen
auf Plugins Management → Kommunikation → Jeedom Link) brauchen Sie nur
Klicken Sie auf "Ziel-Jeedoms konfigurieren"".

Ein Fenster wird angezeigt und Sie können es öffnen
konfigurieren oder hinzufügen **Jeedom (s) Ziel (e)**.

Hinzufügen eines **Jeedom Ziel**, gib einfach :

-   Der Name von **Jeedom Ziel**.

-   Die IP-Adresse oder der DNS-Name des **Jeedom Ziel**.

-   Der API-Schlüssel von **Jeedom Ziel**.

-   Geben Sie an, ob die Kommunikation intern oder extern ist (verwendet für
    Feedback von **Jeedom Quelle** in Richtung **Jeedom
    Ziel**). Und speichern Sie die Konfiguration.

![jeelink2](../images/jeelink2.png)

> **Important**
>
> Du musst **ABSOLUMENT** dass die Netzwerkkonfigurationen aller
> Jeedoms (QuelDie und Ziel) sind in Ordnung, sonst funktioniert das Plugin nicht
> nicht.

Ausrüstungszuordnung 
===========================

Nach der Konfiguration der **Jeedom Ziel** Auf deine
**Jeedom Quelle**, Sie müssen zur Registerkarte * Zuordnung * gehen
Geben Sie das Gerät an, an das gesendet werden soll **Jeedom Ziel**. Alle
Ausrüstungsaufträge werden automatisch erstellt und konfiguriert
Auf dem **Jeedom Ziel**.

Fügen Sie auf der Registerkarte * Zuordnung * die gewünschte Ausrüstung hinzu
geh rauf zum **Jeedom Ziel**.

![jeelink3](../images/jeelink3.png)

Klicken Sie auf * Ausrüstung hinzufügen * Objekt und Ausrüstung auswählen
hinzufügen :

![jeelink5](../images/jeelink5.png)

> **Tip**
>
> Achtung : Plugins mit einem bestimmten Widget haben es nicht aktiviert
> Die **Jeedom Ziel** (Kamera-Plugin, Netzwerk…).

> **Important**
>
> Das Löschen des Gerätes auf der Konfigurationsseite des
> **Ziel Jeedoms** löscht es nicht automatisch auf dem **Jeedom
> Quelle**, Dies ist freiwillig und kein Fehler (es ist Sicherheit).

"Meine Jeelinks" Ausrüstung" 
==============================

Nach dem Aktualisieren der Seite * My JeeLinks * von **Jeedom Ziel**, Sie
muss die automatische Erstellung der Ausrüstung beachten :

![jeelink4](../images/jeelink4.png)

Wie bei allen Jeedom-Geräten können Sie diese aktivieren / deaktivieren und anzeigen
oder nicht das Gerät, seine Bedienelemente, ... oder ändern Sie die Kategorie. Aber
aussi

![jeelink6](../images/jeelink6.png)

Auf der Registerkarte * Bestellungen * greifen Sie auf alDie Parameter der
Gerätesteuerung :

![jeelink7](../images/jeelink7.png)

Ändern der Jeedom-QuelDie eines JeeLink 
==========================================

Mit den folgenden 3 Parametern können Sie die Jeedom-QuelDie ändern,
Zum Beispiel beim Ersetzen eines Jeedom ohne Datenverlust
(Geschichte zum Beispiel). Dazu einfach die
neue Adresse und API-Schlüssel von Jeedom QuelDie und ändern Sie die
Geräte- und Steuerungskennungen (Sie finden sie in
erweiterte Konfiguration dieser durch Klicken auf das Zahnrad).

-   Jeedom Quelladresse;

-   Jeedom QuelDie API-Schlüssel;

-   Quellausrüstung und Bestell-IDs.

Migration im alten Slave-Modus
=============================

Ein Tutorial ist verfügbar,
[hier](https://jeedom.github.io/documentation/howto/fr_FR/jeelink.migration.html)
Angabe des Verfahrens zur Migration eines Jeedom
Slave in die neue Jeedom Link-Betriebsart.

Faq 
===

>**Beim Löschen von Geräten auf dem Quell-Jeedom werden diese nicht aus dem Ziel-Jeedom gelöscht**
>
>Dies ist normalerweise die Quell- / Zielsynchronisation, die nur zum Erstellen und niemals zum Löschen führt.
