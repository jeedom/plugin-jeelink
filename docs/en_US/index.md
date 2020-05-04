Jeelink 
=======

Description 
-----------

Plugin used to link 2 Jeedoms

PrincipThe of operation 
==========================

The * Jeedom Link * plugin (also called jeelink) allows the ascent of a
or more equipment (s) from one Jeedom to another.

![jeelink1](../images/jeelink1.png)

> **Important**
>
> The plugin must be installed on all Jeedoms, Source
> and Target.

> **Tip**
>
> For a good understanding, it is important to understand the
> some following terms :\
> \
> **Jeedom Source** : Jeedom server on which the
> equipment to be reassembled on the **Jeedom Target**\
> \
> **Jeedom Target** : Jeedom server which will receive the reassembled equipment
> by the (s) **Jeedom (s) Source (s)**\
> The **Jeedom Target** centralize this equipment and that of everyone (s)
> **Jeedom (s) Source (s)** configured (s). \
> \
> **Affectation** : configuration performed on the **Jeedom Source**
> to include the equipment that will be reassembled on the **Jeedom
> Cible**

> **Note**
>
> For a better reading and understanding of this tutorial :\
> \
> The screenshots on a black background correspond to the **Jeedom Target**.\
> \
> The screenshots on a white background correspond to **Jeedom Source**.\

Plugin configuration 
=======================

After installation, you just need to activate the plugin. This one does
requires no specific configuration.

Configuration of target jeedoms 
================================

From the **Jeedom Source**, once on the plugin page (by going
on Plugins management → Communication → Jeedom link), you just need
click on "Configure Target Jeedoms".

A window will appear and you can from it
configure or add **Jeedom (s) Target (s)**.

To add a **Jeedom Target**, just give :

-   The name of **Jeedom Target**.

-   The IP address or DNS name of the **Jeedom Target**.

-   The API key of **Jeedom Target**.

-   Indicate whether the communication is internal or external (used for
    feedback, from **Jeedom Source** to the **Jeedom
    Cible**). And save the configuration.

![jeelink2](../images/jeelink2.png)

> **Important**
>
> It is necessary **ABSOLUMENT** that the network configurations of all
> Jeedoms (Source and Target) are OK otherwise the plugin will not work
> not.

Equipment allocation 
===========================

After performing the configuration of the **Jeedom Target** On your
**Jeedom Source**, you have to go to the * Assignment * tab to
specify the equipment to be transmitted to **Jeedom Target**. All the
equipment orders will be automatically created and configured
On the **Jeedom Target**.

In the * Assignment * tab, add the equipment you want
go up to the **Jeedom Target**.

![jeelink3](../images/jeelink3.png)

Click on * Add equipment * Select object and equipment
to add :

![jeelink5](../images/jeelink5.png)

> **Tip**
>
> Be careful : plugins with a specific widget will not have it on
> The **Jeedom Target** (camera plugin, network…).

> **Important**
>
> The deletion of the equipment on the configuration page of the
> **Target Jeedoms** does not automatically delete it on the **Jeedom
> Source**, this is voluntary and not a bug (it is security).

"My jeelinks" equipment" 
==============================

After refreshing the page * My JeeLinks * of **Jeedom Target**, vous
must note the automatic creation of the equipment :

![jeelink4](../images/jeelink4.png)

Like all Jeedom equipment, you can activate / deactivate and display
or not the equipment, its controls, ... or change the category. But
aussi

![jeelink6](../images/jeelink6.png)

In the * Orders * tab, you access all the parameters of the
equipment controls :

![jeelink7](../images/jeelink7.png)

Modifying the Jeedom Source of a JeeLink 
==========================================

The following 3 parameters allow you to change the Jeedom Source,
for exampThe when replacing a Jeedom without losing data
(history for example). To do this, simply put the
new address and API key of Jeedom Source and change the
equipment and control identifiers (you will find them in
advanced configuration of these by clicking on the toothed wheel).

-   Jeedom Source address;

-   Jeedom Source API key;

-   Source equipment and order IDs.

Old Slave Mode Migration
=============================

A tutorial is available,
[here](https://jeedom.github.io/documentation/howto/fr_FR/jeelink.migration.html)
specifying the procedure to follow to migrate a Jeedom
Slave to the new Jeedom Link operating mode.

FAQ 
===

>**When deleting equipment on the source jeedom these are not deleted from the target jeedom**
>
>This is normally the source / target synchronization only creates creation, never deletion.
