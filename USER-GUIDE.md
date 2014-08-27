MunchMailer − Guide du développeur
=======================================

MunchMailer est une **API REST** permettant la gestion et l'expédition de
campagnes d'email vers **un grand nombre de destinataires**.

TODO
----

- Mettre la bonne clef DKIM
- Authentification
- Rôle des *owners*
- Remplacer les localhost

Portée
------

MunchMailer est :

- une API HTTP/REST
- une infrastructure dédiée à la remise de mails en nombre

MunchMailer n'est pas :

- une application utilisateur : c'est à vous de construire une interface
  exploitant MunchMailer
- une relais de spam non sollicité : nous imposons et vérifions que nos clients
  respectent
  [les règles en vigueur](http://www.arobase.org/spam/comprendre-regulation.htm)
  sur l'envoi de courriels marketting.


Vocabulaire
-----------

### email transactionnel vs email massif

Un email transactionnel est destiné précisément à une personne ou à un petit
groupe de personnes connues.

Un email massif est envoyé de manière automatique ou non à un grand
nombre de destinataire, il s'agit d'un outil de communication de masse.

Les emails massifs incluent :
- les newsletters
- les communications à destination des adhérents d'une association
- les mails marketing
- ...

### Bounce

On parle de *Bounce* (littéralement *Rebond*) lorsqu'un mail envoyé ne peut pas
être remis au destinataire. Les raisons sont multiples : adresse inexistante,
boite pleine, serveur mal configuré…

MunchMailer se charge de désinscrire automatiquement les adresses produisant des
*bounces* trop fréquents, en vous notifiant.

Prérequis
---------

Pour un *taux de remise* optimal de vos messages, nous respectons un certain
nombre de bonne pratiques. *MunchMailer* gère l'essentiel de ces pratiques pour
vous. Cependant, certaines opérations sur le nom de domaine **restent à votre
charge** :

### Enregistrement SPF

L'enregistrement **SPF** (*Sender Policy Framework*) permet d'autoriser nos serveurs à
envoyer des mails en provenance de votre domaine.

Si vous souhaitez envoyer vos emails depuis *jdoe@mailling.example.com*, vous
devez ajouter un enregistrement `TXT` sur le domaine *mailling.example.com*.

A-minima, si vous ne disposez pas encore d'un enregistrement SPF sur votre
domaine, vous pouvez ajouter quelque chose comme :

    mailling.example.com. 720 IN TXT "v=spf1 include:_spf.mailling.oasismail.fr ?all"

Si vous disposez déjà d'un enregistrement SPF, vous pouvez vous contenter
d'ajouter la règle `include:_spf.mailling.oasismail.fr` à votre chaîne afin
d'autoriser nos serveurs.

[Plus d'informations sur SPF](http://fr.wikipedia.org/wiki/Sender_Policy_Framework).

### Enregistrement DKIM

L'enregistrement **DKIM** (*Domain Keys Identified Mail*) permet d'autoriser nos
serveurs à signer cryptographiquement les mails envoyés en votre nom avec notre
propre clef.

Si vous souhaitez envoyer vos emails depuis *jdoe@mailling.example.com*, vous
devez ajouter l'enregistrement `TXT` suivant :

	KEY-FINGER-PRINT._domainkey.mailling.example.com.	341222 IN TXT "v=DKIM1\; k=rsa\; p=KEY-CONTENT"

Fonctionnement
--------------

*MunchMailer* définit plusieurs notions :


### Campagne

C'est simplement l'envoi d'un mail identique à un certain nombre de
personne. Cette notion est au-dessus des autres. Une campagne peut-être
programmée pour être envoyée à une certaine date.

### Message

Le corps du texte de votre communication. MunchMailer requiert que vous fournissiez une
version HTML et se chargera de proposer aux destinataires à la fois

- cette version HTML « nettoyée » pour respecter un certain nombre de bonnes
  pratiques et s'afficher correctement chez tous vos destinataires (voir [détail
  des modifications]())
- une version texte pour les clients ne supportant que ce format ou les
  destinataires ayant fait ce choix.


### Contacts techniques

Une liste d'emails qu'Oasiswork utilisera  pour signaler :

- les résiliations (quelles que soient leur origine)
- les rapports d'abus

Cette liste n'est ni rendue publique ni mentionnée dans les messages envoyés.

### Désinscription (Opt-out)

Vous **devez** (c'est la loi), offrir une possibilité à vos destinataires de se
désinscrire de vos envois. MunchMailer gère cela pour vous :

- un lien est inclus en bas des emails pour offrir cette possibilité ;
- une adresse de désinscription est encodée dans le mail. Certains clients de
  mail (gmail notamment) exploitent cette adresse pour fournir automatiquement un
  bouton « se désinscrire » directement depuis leur interface.
- si un de vos destinataires marque le courriel comme *spam*, et que son hébergeur
  nous le notifie, il sera désinscrit
- si une adresse [bounce]() trop, elle sera désinscrite

Vous ne pouvez pas passer outre ces *résiliations*. Cependant, dans certains cas très
spécifiques, nous pouvons rediriger vos destinataires vers un moyen de vous
contacter plutôt que de leur proposer une désinscription en ligne.

*Exemple : Communication d'une association à ses membres, où le refus de recevoir
 les informations équivaut à quitter l'association.*

Cette fonctionnalité n'est activée qu'après vérification de la légitimité , au
cas par cas, merci de nous contacter.

L'API
-----

L'API est accessible à l'adresse *https://api.mailling.oasismail.fr*. Elle
requiert une authentification pour fonctionner.

Elle suit les principes
[REST](http://fr.wikipedia.org/wiki/Representational_State_Transfer), et utilise
le format [JSON](http://fr.wikipedia.org/wiki/JavaScript_Object_Notation), ce
qui la rend exploitable depuis un grand nombre de langages de programmation.

Les étapes de la vie d'une campagne sont :

1. Création/édition de la campagne avec ses attributs
2. Ajout/modification d'une liste de destinataires
3. Ajout/modification (et prévisualisation) du message
4. Validation de la campagne (envoi)
5. Suivi des envois

Les étapes 2 et 3 peuvent être interverties ; de même, tant que la campagne
n'est pas validée, elle peut-être modifiée (1.).

### 1. Création/édition de la campagne avec ses attributs

    POST /api/v1/campaigns/
	{
	    "name"         :"Newsletter de Juillet",
		"sender_email" :"newslettert@example.com",
        "sender_name"  :"Newsletter ACME chaussures",
        "tech_contacts":"admins@example.com, communication@example.com",
        "owners"       :"communication@example.com"
    }

* *name* : Le nom interne donné à la campagne, il ne sera pas visible de vos
   destinataires.
* *sender_email* : le mail d'expéditeur de la campagne, vous devez être
   propriétaire du domaine concerné (ici *example.com*), et en y avoir
   [correctement configuré SPF et DKIM]().
* *sender_name* : le nom de l'expéditeur, qui apparaîtra dans le client mail de
   vos destinataires.
* *tech_contacts* : liste d'emails, séparées par des virgules, ils recevront
   notamment les notifications de résiliation
* *owners* : liste d'emails, séparées par des virgules, FIXME

Si tout se passe bien, l'API devrait vous retourner :

    HTTP 201 CREATED
    {
        "customer": 1024,
        "message": "http://localhost:8000/api/v1/campaigns/6/message/",
        "mails": "http://localhost:8000/api/v1/campaigns/6/mails/",
        "completion_date": null,
        "preview": "http://localhost:8000/api/v1/campaigns/6/preview/",
        "url": "http://localhost:8000/api/v1/campaigns/6/",
        "name": "Newsletter de Juillet",
        "status": "new",
        "sender_email": "newslettert@example.com",
        "sender_name": "Newsletter ACME chaussures",
        "creation_date": "2014-08-27T14:55:58.470Z",
        "send_date": null,
        "owners": "communication@example.com",
        "tech_contacts": "admins@example.com,communication@example.com",
        "external_optout": false
    }

Notons quelques champs :

* **url** est à conserver, elle vous permet d'accéder à votre campagne pour la
   visualiser ou la modifier.
* **status** nous donne l'état courant de la campagne
* **message** est un lien vers le message de la campagne (voir [étape 3.]())
* **mails** est un lien vers la liste des destinataires et leur état courant (voir
   [étape 2]())
* **preview** Permet de voir un résumé de la campagne, utile notamment avant de
   [valider la campagne]()

Pour *modifier* la campagne, il faut effectuer une requête de type `PUT` à
l'adresse retournée dans l'attribut **url** en envoyant la totalité du contenu
de l'objet, modifié comme souhaité.

### 2. Ajout/modification d'une liste de destinataires

Il est possible d'ajouter des destinataires soit un par un, soit en lots :

(on utilise l'adresse contenue dans l'attribut **message** de notre objet, voir
étape précédente).

#### Ajout des destinataires un par un

Il suffit de faire autant de requêtes POST que de destinataires à ajouter.

    POST /api/v1/campaigns/6/mails/
    {
	    "to" : "mon-destinataire@domaine.tld"
    }

#### Ajout des destinataires en masse

    POST /api/v1/campaigns/6/mails/
    [
	    {"to" : "john@domaine.tld"},
	    {"to" : "jane@domaine.tld"},
	    {"to" : "gromit@autredomaine.tld"},
    ]

Que vous ajoutiez les destinataires individuellement ou en lot :

Vous recevrez les statuts suivants :

- **201 (created)** si tout se passe bien
- **400 (bad request)** si l'email est invalide (adresse mal formée ou domaine
non habilité à recevoir du courriel)

Par exemple…


     POST /api/v1/campaigns/6/mails/
     {
	    "to" : "jenesuispasunemail"
     }

… retournera

    400 BAD REQUEST
	{
	    "to": [
           "Saisissez une adresse de courriel valide."
        ]
	}

### 3. Ajout/modification (et prévisualisation) du message

C'est le point le plus crucial de votre campagne.

Si la méthode qui donne les meilleurs résultats est de rédiger ce document HTML
à la main, il peut également être produit avec un éditeur WYSWYG, un traitement
de texte… etc.

Pour des conseils sur la rédaction de mails en HTML, vous pouvez vous référer à
[ce guide](http://kb.mailchimp.com/article/how-to-code-html-emails/) ou encore
[ce dépôt d'exemples](https://github.com/mailchimp/Email-Blueprints).

TODO

### 4. Validation de la campagne (envoi)

### 5. Suivi des envois


FAQ
---

### Pourquoi un outil dédié ?

Il est théoriquement possible d'envoyer un email à de nombreux destinataires,
cependant :

- on finit vite classé en tant que spam ou rejetés (les serveurs de destination
  sont plus stricts sur les mails « massifs ») ;
- on a aucune idée de qui reçoit ou ne reçoit pas les mails ;
- quand les mails ne sont pas reçus, on reçoit tout un tas de mails d'erreur
  dans sa boite mail.

### Comment personnaliser le lien « Se désinscrire » ?

Un lien est inséré par défaut à la fin de votre document HTML pour permettre à
vos destinataires de se désabonner.

Si vous voulez que ce lien aie l'apparence de votre choix, il vous suffit de
mettre la chaîne `UNSUBSCRIBE_URL` là où vous voulez que l'adresse effective de
désinscription apparaisse, par exemple :

    Message ennuyeux ? Vous pouvez <a href="UNSUBSCRIBE_URL">vous désinscrire</a>


Annexes
=======

Détails des modifications appliqués aux emails :

- les images sont détachées et hébergées sur nos serveurs (pour éviter de
  surcharger les votres)
- le javascript et le flash sont retirés (ils ne sont que
  [rarement supportés](https://www.campaignmonitor.com/resources/will-it-work/))
- les éventuelles erreurs HTML sont corrigées
- les règles CSS sont appliquées directement aux attributs (on parle
  d'*inlining*), la plupart des clients mail ne supportant pas les styles externes.

<!--  LocalWords:  A-minima
 -->
