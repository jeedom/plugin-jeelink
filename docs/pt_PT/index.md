Jeelink 
=======

Descrição 
-----------

Plugin usado para vincular 2 Jeedoms

Princípio de operação 
==========================

O plugin * Jeedom Link * (também chamado jeelink) permite a subida de um
ou mais equipamentos de um Jeedom para outro.

![jeelink1](../images/jeelink1.png)

> **Important**
>
> O plugin deve ser instalado em todos os Jeedoms, Fonte
> e Alvo.

> **Tip**
>
> Para um bom entendimento, é importante entender o
> alguns termos a seguir :\
> \
> **Fonte Jeedom** : Servidor Jeedom no qual o
> equipamento a ser remontado no **Jeedom Target**\
> \
> **Jeedom Target** : Servidor Jeedom que receberá o equipamento remontado
> pelo (s) **Jeedom (s) Origem (s)**\
> O **Jeedom Target** centralizar este equipamento e o de todos
> **Jeedom (s) Origem (s)** configurados. \
> \
> **Affectation** : configuração realizada no **Fonte Jeedom**
> incluir o equipamento que será remontado no **Jeedom
> Target**

> **Note**
>
> Para uma melhor Oitura e compreensão deste tutorial :\
> \
> As capturas de tela em um fundo preto correspondem à **Jeedom Target**.\
> \
> As capturas de tela em um fundo branco correspondem a **Fonte Jeedom**.\

Configuração do plugin 
=======================

Após a instalação, você só precisa ativar o plugin. Este faz
não requer configuração específica.

Configuração de jeedoms de destino 
================================

A partir de **Fonte Jeedom**, uma vez na página do plugin (indo
em Gerenciamento de plug-ins → Comunicação → Link Jeedom), você só precisa
clique em "Configurar Target Jeedoms".

Uma janela aparecerá e você poderá
configurar ou adicionar **Jeedom (s) Alvo (s)**.

Para adicionar um **Jeedom Target**, apenas dê :

-   O nome de **Jeedom Target**.

-   O endereço IP ou o nome DNS do **Jeedom Target**.

-   A chave da API de **Jeedom Target**.

-   Indique se a comunicação é interna ou externa (usada para
    feedback, de **Fonte Jeedom** para **Jeedom
    Target**) E salve a configuração.

![jeelink2](../images/jeelink2.png)

> **Important**
>
> Você deve **ABSOLUMENT** que as configurações de rede de todos
> Jeedoms (origem e destino) estão OK, caso contrário, o plug-in não funcionará
> Não.

Alocação de equipamentos 
===========================

Depois de executar a configuração do **Jeedom Target** no seu
**Fonte Jeedom**, você precisa ir para a guia * Tarefa * para
especificar o equipamento a ser transmitido para **Jeedom Target**. Todos
pedidos de equipamentos serão criados e configurados automaticamente
no **Jeedom Target**.

Na guia * Atribuição *, adicione o equipamento que deseja
vá até o **Jeedom Target**.

![jeelink3](../images/jeelink3.png)

Clique em * Adicionar equipamento * Selecione objeto e equipamento
adicionar :

![jeelink5](../images/jeelink5.png)

> **Tip**
>
> Atenção : plugins com um widget específico não o terão em
> O **Jeedom Target** (plug-in de câmera, rede ...).

> **Important**
>
> A exclusão do equipamento na página de configuração do
> **Target Jeedoms** não o exclui automaticamente no **Jeedom
> Fonte**, isso é voluntário e não é um bug (é segurança).

Equipamento "Meus jeelinks"" 
==============================

Após atualizar a página * My JeeLinks * de **Jeedom Target**, você
deve-se observar a criação automática do equipamento :

![jeelink4](../images/jeelink4.png)

Como todos os equipamentos Jeedom, você pode ativar / desativar e exibir
ou não o equipamento, seus controles, ... ou altere a categoria. Mas
aussi

![jeelink6](../images/jeelink6.png)

Na guia * Pedidos *, você acessa todos os parâmetros do
controles de equipamentos :

![jeelink7](../images/jeelink7.png)

Modificando a fonte Jeedom de um JeeLink 
==========================================

Os 3 parâmetros a seguir permitem alterar a fonte Jeedom,
por exemplo, ao substituir um Jeedom sem perder dados
(histórico por exemplo). Para fazer isso, basta colocar o
novo endereço e chave de API do Fonte Jeedom e altere o
identificadores de equipamento e controO (você os encontrará em
configuração avançada destes clicando na roda dentada).

-   Endereço de origem Jeedom;

-   Chave da API de origem Jeedom;

-   IDs de equipamento e pedido de origem.

Migração do modo antigo escravo
=============================

Um tutorial está disponível,
[aqui](https://jeedom.github.io/documentation/howto/fr_FR/jeelink.migration.html)
especificando o procedimento a seguir para migrar um Jeedom
Escravo para o novo modo de operação Jeedom Link.

Faq 
===

>**Ao excluir equipamentos no jeedom de origem, eles não são excluídos do jeedom de destino**
>
>Normalmente, a sincronização de origem / destino cria apenas criação, nunca exclusão.
