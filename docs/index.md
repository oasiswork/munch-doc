# MunchMailer − Guide du développeur

MunchMailer est une **API REST** permettant la gestion et l'expédition de
campagnes d'email vers **un grand nombre de destinataires**.

Cette documentation vise à vous aider dans la réalisation d'une application
exploitant l'API MunchMailer.

## TODO pour cette doc

- Mettre la bonne clef DKIM
- Rôle des *owners*

## Portée

MunchMailer est :

- une API HTTP/REST
- une infrastructure dédiée à la remise de mails en nombre

MunchMailer n'est pas :

- une application utilisateur : c'est à vous de construire une interface
  exploitant MunchMailer
- une relais de spam non sollicité : c'est votre devoir et votre
  responsabilité de respecter
  [les règles en vigueur](http://www.arobase.org/spam/comprendre-regulation.htm)
  sur l'envoi de courriels marketting.


## Vocabulaire

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

## Prérequis

Pour un *taux de remise* optimal de vos messages, nous respectons un certain
nombre de bonne pratiques. *MunchMailer* gère l'essentiel de ces pratiques pour
vous. Cependant, certaines opérations sur le nom de domaine **restent à votre
charge** :

### Enregistrement SPF

L'enregistrement **SPF** (*Sender Policy Framework*) permet d'autoriser nos serveurs à
envoyer des mails en provenance de votre domaine.

Si vous souhaitez envoyer vos emails depuis *elvis@mailling.example.com*, vous
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

Si vous souhaitez envoyer vos emails depuis *elvis@mailling.example.com*, vous
devez ajouter l'enregistrement `TXT` suivant :

	KEY-FINGER-PRINT._domainkey.mailling.example.com.	341222 IN TXT "v=DKIM1\; k=rsa\; p=KEY-CONTENT"

## Fonctionnement

*MunchMailer* définit plusieurs notions :


### Campagne

C'est simplement l'envoi d'un mail identique à un certain nombre de
personne. Cette notion est au-dessus des autres. Une campagne peut-être
programmée pour être envoyée à une certaine date.

### Message

Le corps du texte de votre communication. MunchMailer requiert que vous fournissiez une
version HTML et se chargera de proposer aux destinataires à la fois

- cette version HTML est « nettoyée » pour respecter un certain nombre de bonnes
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

Cette fonctionnalité n'est activée qu'au cas par cas, merci de nous contacter.
