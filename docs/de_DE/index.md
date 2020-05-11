Jeelink 
=======

Beschreibung 
-----------

Plugin zum Verknüpfen von 2 Jeedoms

Funktionsprinzip 
==========================

Das Plugin *Jeedom Link* (auch Jeelink genannt) ermöglicht den Aufstieg von a
oder mehr Ausrüstung (en) von einem Jeedom zum anderen.

![jeelink1](../images/jeelink1.png)

> **Wichtig**
>
> Das Plugin muss auf allen Jeedoms, Source installiert sein
> und Ziel.

> **Spitze**
>
> Für ein gutes Verständnis ist es wichtig, das zu verstehen
> einige folgende Begriffe :\.
> \.
> **Jeedom Quelle** : Jeedom Server auf dem die
> Ausrüstung, die auf dem wieder zusammengebaut werden soll **Jeedom Ziel**\.
> \.
> **Jeedom Ziel** : Jeedom-Server, der die wieder zusammengebauten Geräte erhält
> von den (s) **Jeedom(s) Source(s)**\.
> Die **Jeedom Ziel** Zentralisieren Sie diese Ausrüstung und die aller Personen
> **Jeedom(s) Source(s)** konfiguriert (en). \
> \.
> **Zuordnung** : Konfiguration durchgeführt am **Jeedom Quelle**
> um die Ausrüstung einzuschließen, die auf dem wieder zusammengebaut wird **Jeedom
> Ziel**

> **Notiz**
>
> Zum besseren Lesen und Verstehen dieses Tutorials :\.
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
konfigurieren oder hinzufügen **Jeedom(s) Cible(s)**.

Hinzufügen eines **Jeedom Ziel**, gib einfach :

-   Der Name von **Jeedom Ziel**.

-   Die IP-Adresse oder der DNS-Name des **Jeedom Ziel**.

-   Der API-Schlüssel von **Jeedom Ziel**.

-   Geben Sie an, ob die Kommunikation intern oder extern ist (verwendet für
    Feedback von **Jeedom Quelle** in Richtung **Jeedom
    Ziel**). Und speichern Sie die Konfiguration.

![jeelink2](../images/jeelink2.png)

> **Wichtig**
>
> Du musst **ABSOLUT** dass die Netzwerkkonfigurationen aller
> Jeedoms (Quelle und Ziel) sind in Ordnung, sonst funktioniert das Plugin nicht
> nicht.

Ausrüstungszuordnung 
===========================

Nach der Konfiguration der **Jeedom Ziel** Auf deine
**Jeedom Quelle**, Sie müssen zur Registerkarte gehen *Zuordnung* pour
Geben Sie das Gerät an, an das gesendet werden soll **Jeedom Ziel**. Alle
Ausrüstungsaufträge werden automatisch erstellt und konfiguriert
Auf dem **Jeedom Ziel**.

In der Registerkarte *Zuordnung*, Fügen Sie die gewünschte Ausrüstung hinzu
geh rauf zum **Jeedom Ziel**.

![jeelink3](../images/jeelink3.png)

Klicken Sie auf *Ausrüstung hinzufügen* Objekt und Ausrüstung auswählen
hinzufügen :

![jeelink5](../images/jeelink5.png)

> **Spitze**
>
> Achtung : Plugins mit einem bestimmten Widget haben es nicht aktiviert
> Die **Jeedom Ziel** (Kamera-Plugin, Netzwerk…).

> **Wichtig**
>
> Das Löschen des Gerätes auf der Konfigurationsseite des
> **Jeedoms Cibles** löscht es nicht automatisch auf dem **Jeedom
> Quelle**, Dies ist freiwillig und kein Fehler (es ist Sicherheit).

"Meine Jeelinks" Ausrüstung" 
==============================

Nach dem Aktualisieren der Seite *Meine Jeelinks* die **Jeedom Ziel**, vous
muss die automatische Erstellung der Ausrüstung beachten :

![jeelink4](../images/jeelink4.png)

Wie bei allen Jeedom-Geräten können Sie diese aktivieren / deaktivieren und anzeigen
oder nicht das Gerät, seine Bedienelemente, ... oder ändern Sie die Kategorie. Aber
aussi

![jeelink6](../images/jeelink6.png)

In der Registerkarte *Befehle*, Sie greifen auf alle Parameter des
Gerätesteuerung :

![jeelink7](../images/jeelink7.png)

Ändern der Jeedom-Quelle eines JeeLink 
==========================================

Mit den folgenden 3 Parametern können Sie die Jeedom-Quelle ändern,
Zum Beispiel beim Ersetzen eines Jeedom ohne Datenverlust
(Geschichte zum Beispiel). Dazu einfach die
neue Adresse und API-Schlüssel von Jeedom Source und ändern Sie die
Geräte- und Steuerungskennungen (Sie finden sie in
erweiterte Konfiguration dieser durch Klicken auf das Zahnrad).

-   Jeedom Quelladresse;

-   Jeedom Source API-Schlüssel;

-   Quellausrüstung und Bestell-IDs.

Migration im alten Slave-Modus
=============================

Ein Tutorial ist verfügbar,
[hier](https://jeedom.github.io/documentation/howto/de_DE/jeelink.migration.html)
Angabe des Verfahrens zur Migration eines Jeedom
Slave in die neue Jeedom Link-Betriebsart.

Faq 
===

>**Beim Löschen von Geräten auf dem Quell-Jeedom werden diese nicht aus dem Ziel-Jeedom gelöscht**
>
>Dies ist normalerweise die Quell- / Zielsynchronisation, die nur zum Erstellen und niemals zum Löschen führt
