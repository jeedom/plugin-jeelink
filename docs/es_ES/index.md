Jeelink 
=======

Descripción 
-----------

Plugin utilizado para vincular 2 Jeedoms

Principio de funcionamiento 
==========================

El plugin * Jeedom Link * (también llamado jeelink) permite el ascenso de un
o más equipos de un Jeedom a otro.

![jeelink1](../images/jeelink1.png)

> **Important**
>
> El complemento debe estar instalado en todos los Jeedoms, Fuente
> y Target.

> **Tip**
>
> Para una buena comprensión, es importante comprender el
> algunos términos siguientes :\
> \
> **Fuente de la libertad** : Servidor Jeedom en el que
> equipo para volver a montar en el **Jeedom Target**\
> \
> **Jeedom Target** : Servidor Jeedom que recibirá el equipo reensamblado
> por el (los) **Jeedom (s) Fuente (s)**\
> El **Jeedom Target** Centralizar este equipo y el de todos.
> **Jeedom (s) Fuente (s)** configurado (s). \
> \
> **Affectation** : configuración realizada en el **Fuente de la libertad**
> para incluir el equipo que se volverá a montar en el **Jeedom
> Target**

> **Note**
>
> Para una mejor Elctura y comprensión de este tutorial :\
> \
> Las capturas de pantalla en un fondo negro corresponden a la **Jeedom Target**.\
> \
> Las capturas de pantalla en un fondo blanco corresponden a **Fuente de la libertad**.\

Configuración del plugin 
=======================

Después de la instalación, solo necesita activar el complemento. Este sí
no requiere configuración específica.

Configuración de jeedoms objetivo 
================================

A partir de **Fuente de la libertad**, una vez en la página del complemento (yendo
en Gestión de complementos → Comunicación → Enlace de libertad), solo necesita
haga clic en "Configurar Target Jeedoms".

Aparecerá una ventana y puedes desde ella
configurar o agregar **Jeedom (s) Target (s)**.

Para agregar un **Jeedom Target**, solo dame :

-   El nombre de **Jeedom Target**.

-   La dirección IP o el nombre DNS del **Jeedom Target**.

-   La clave API de **Jeedom Target**.

-   Indique si la comunicación es interna o externa (utilizada para
    comentarios, de **Fuente de la libertad** hacia **Jeedom
    Target**) Y guarda la configuración.

![jeelink2](../images/jeelink2.png)

> **Important**
>
> Hay que **ABSOLUMENT** que las configuraciones de red de todos
> Jeedoms (Fuente y Destino) están bien, de lo contrario el complemento no funcionará
> no.

Asignación de equipos 
===========================

Después de realizar la configuración de la **Jeedom Target** En su
**Fuente de la libertad**, tienes que ir a la pestaña * Asignación * para
especificar el equipo a transmitir **Jeedom Target**. Todas las
los pedidos de equipos se crearán y configurarán automáticamente
Sobre **Jeedom Target**.

En la pestaña * Asignación *, agregue el equipo que desee
subir a la **Jeedom Target**.

![jeelink3](../images/jeelink3.png)

Haga clic en * Agregar equipo * Seleccionar objeto y equipo
para agregar :

![jeelink5](../images/jeelink5.png)

> **Tip**
>
> Atención : los complementos con un widget específico no lo tendrán activado
> El **Jeedom Target** (plugin de cámara, red ...).

> **Important**
>
> La eliminación del equipo en la página de configuración del
> **Jeedoms objetivo** no lo elimina automáticamente en el **Jeedom
> Fuente**, esto es voluntario y no es un error (es seguridad).

Equipo "Mis jeelinks"" 
==============================

Después de actualizar la página * My JeeLinks * de **Jeedom Target**, Vosotras
debe tener en cuenta la creación automática del equipo :

![jeelink4](../images/jeelink4.png)

Como todos los equipos Jeedom, puede activar / desactivar y mostrar
o no el equipo, sus controles, ... o cambiar la categoría. Pero
aussi

![jeelink6](../images/jeelink6.png)

En la pestaña * Pedidos *, accede a todos los parámetros de
controles de equipo :

![jeelink7](../images/jeelink7.png)

Modificación de la fuente Jeedom de un JeeLink 
==========================================

Los siguientes 3 parámetros El permiten cambiar la fuente Jeedom,
por ejemplo al reemplazar un Jeedom sin perder datos
(historia por ejemplo). Para hacer esto, simplemente ponga el
nueva dirección y clave API de Fuente de la libertad y cambie el
identificadores de equipos y control (los encontrará en
configuración avanzada de estos haciendo clic en la rueda dentada).

-   Dirección de la fuente Jeedom;

-   Clave API de Fuente de la libertad;

-   Equipo de origen e ID de pedido.

Vieja migración de modo esclavo
=============================

Un tutorial esta disponible,
[aquí](https://jeedom.github.io/documentation/howto/fr_FR/jeelink.migration.html)
especificando el procedimiento a seguir para migrar un Jeedom
Esclavo del nuevo modo de funcionamiento Jeedom Link.

Preguntas frecuentes 
===

>**Al eliminar equipos en la fuente de libertad, estos no se eliminan de la libertad de destino.**
>
>Normalmente, esta sincronización de origen / destino solo crea creación, nunca eliminación.
