Tutoriel : Un envoi de A à Z
=============================

Les étapes de la vie d'une campagne sont :

1. Création/édition d'un message avec ses attributs
2. Ajout/modification d'une liste de destinataires
3. Prévisualisation et envoi à quelques destinataires « pilotes »
4. Déclenchement de l'envoi
5. Suivi des envois


## 1. Création/édition d'un message avec ses attributs

    POST /v1/messages/
	{
	    "name"         :"Newsletter de Juillet",
		"sender_email" :"newsletter@example.com",
        "sender_name"  :"Communication ACME chaussures",
        "subject": "Tu peux faire tout ce que tu veux",
        "html": "<h1>Mais ne marche pas sur mes chaussures en suédine bleue</h1>"
    }

* **name** : Le nom interne donné à la campagne, il ne sera pas visible de vos
   destinataires.
* **sender_email** : le mail d'expéditeur de la campagne, le domaine de cette
    adresse doit préalablement avoir été [configuré correctement](../domaines/).
* **sender_name** : le nom de l'expéditeur, qui apparaîtra dans le client mail de
   vos destinataires.
* **subject** : Le sujet de votre email.
* **html** : Le corps en HTML de votre email.

---

*Des [options de suivi](tracking) des messages sont également disponibles.*

---

Le message fourni dans le paramètre **html** fera l'objet de plusieurs
traitements :

* Un « nettoyage » pour apparaître au mieux sur tous les clients mail (voir
  [liste des traitements appliqués](/annexes/#details-des-modifications-appliquees-aux-emails))
* La génération d'une version « texte brut » à partir de ce dernier, fourni en
  tant qu'alternative (il est nécessaire de fournir une version texte brut
  dans les emails).
* Un test anti-spam, pour vérifier qu'il ne risque pas d'être considéré comme
  spam.

----

*ⓘ Facultativement, un message peut-être rattaché à une campagne, ce
point est traité dans la [section dédiée de la documentation](../campagnes/).*

----

Si tout se passe bien, l'API devrait vous retourner :

    HTTP 201 CREATED
    {
        "url": "https://api.munchmail.net/v1/messages/4/",
        "name": "ACME − Newsletter Juillet",
        "sender_email": "newsletter@example.com",
        "sender_name": "Communication ACME chaussures",
        "subject": "Tu peux faire tout ce que tu veux",
        "html": "<h1>Mais ne marche pas sur mes chaussures en suédine bleue</h1>"
        "status": "message_ok",
        "campaign": null,
        "creation_date": "2015-01-12T15:37:29.803Z",
        "send_date": null,
        "completion_date": null,
        "external_optout": false,
        "detach_images": false,
        "spam_score": 0.0,
        "is_spam": false,
        "msg_issue": "",
        "_links": {
            "preview_send": {
                "href": "https://api.munchmail.net/v1/messages/4/preview_send/"
            },
            "preview": {
                "href": "https://api.munchmail.net/v1/messages/4/preview/"
            },
            "preview/.html": {
                "href": "https://api.munchmail.net/v1/messages/4/preview/.html/"
            },
            "preview/.txt": {
                "href": "https://api.munchmail.net/v1/messages/4/preview/.txt/"
            },
            "mails": {
                "href": "https://api.munchmail.net/v1/messages/4/mails/"
            },
            "spam_details": {
                "href": "https://api.munchmail.net/v1/messages/4/spam_details/"
            }
        }
    }

Notons quelques champs :

* **url** est à conserver, elle vous permet d'accéder à votre message pour la
   visualiser ou la modifier.
* **status** nous donne l'état courant du message
* **mails** est un lien vers la liste des destinataires et leur état courant (voir
   [étape 2](#2-ajoutmodification-dune-liste-de-destinataires))
* **preview** Permet de voir un résumé du message ; utile notamment avant de
   [valider le message](#4-validation-du-message)
* **is_spam** devrait vous alerter si il est à `true` (viser le zéro de
    **spam_score** peut-être un bon objectif), le système **refusera quoi qu'il
    en soit d'envoyer** un message considéré comme spam ;
* **spam_details** permet d'accéder au barème détaillé du *spam_score*, afin de
    pouvoir apporter les modifications nécessaires au contenu de **html**.
* **preview**, **html_preview** et **plaintext_preview** permettent d'avoir une
    idée du code et du rendu du mail une fois « nettoyé » par MunchMail (voir [Prévisualisation](#previsualisation))
* **preview_send** Permet d'envoyer le message à quelques destinataires de test
    (voir [plus bas](#envoi-a-quelques-destinataires-pilote))

En cas d'erreur, vous recevrez une erreur 400 détaillant le ou les problèmes,
qui peuvent notamment concerner les enregistrements
[DKIM](/#enregistrement-dkim) et [SPF](/#enregistrement-spf)


    HTTP 400 BAD REQUEST
    {
        "sender_email": [
            "Le champ SPF pour example.com est mal configur\u00e9",
            "Pas de configuration DKIM pour le domaine example.com"
        ]
    }

En cas d'erreur de validation, **le message n'est pas créé**.

Pour *modifier* le message , il faut effectuer une requête de type `PUT` à
l'adresse retournée dans l'attribut **url** en envoyant la totalité du contenu
de l'objet, modifié comme souhaité.


### À propos de la rédaction du corps HTML

Si la méthode qui donne les meilleurs résultats est de rédiger ce document HTML
à la main, il peut également être produit avec un éditeur WYSIWYG, un traitement
de texte… etc.

Pour des conseils sur la rédaction de mails en HTML, vous pouvez vous référer à
[ce guide](http://kb.mailchimp.com/article/how-to-code-html-emails/) ou encore
[ce dépôt d'exemples](https://github.com/mailchimp/Email-Blueprints).


## 2. Ajout/modification d'une liste de destinataires

Il est possible d'ajouter des destinataires soit un par un, soit en lots :

(on utilise l'URL pointée par l'attribut `_links.message` de notre objet, voir
étape précédente).

### Ajout des destinataires un par un

Il suffit de faire autant de requêtes POST que de destinataires à ajouter.

    POST /v1/mails/
    {
	    "to" : "mon-destinataire@domaine.tld",
        "message": "https://api.munchmail.net/v1/messages/4/"
    }

### Ajout des destinataires par lot

    POST /v1/mails/
    [
	    {"to" : "john@domaine.tld",     "message": "https://api.munchmail.net/v1/messages/4/"},
	    {"to" : "jane@domaine.tld",     "message": "https://api.munchmail.net/v1/messages/4/"},
	    {"to" : "fox@autredomaine.tld", "message": "https://api.munchmail.net/v1/messages/4/"},
    ]

*ⓘ Évitez les lots de plus de 10 000 destinataires en une seule (longue)
 requête. Séparer par exemple en lots de 10 000 destinataires permet à votre
 application de suivre la progression de l'ajout des destinataires étape par
 étape.*

Que vous ajoutiez les destinataires individuellement ou en lot, vous recevrez
les statuts HTTP suivants :

- **201 (created)** si tout se passe bien
- **400 (bad request)** si l'email est invalide (adresse mal formée ou domaine
non habilité à recevoir du courriel)

Par exemple…


     POST /v1/mails/
     {
	    "to" : "jenesuispasunemail",
        "message": "https://api.munchmail.net/v1/messages/4/"
     }

… retournera

    400 BAD REQUEST
	{
	    "to": [
           "Saisissez une adresse de courriel valide."
        ]
	}


## 3. Prévisualisation et envoi à quelques destinataires « pilotes »

### Prévisualisation

Une vue de validation est offerte sur les `messages` ; comme toutes les actions
liées aux ressources, son URL est proposée dans l'attribut `_links` ; celle-ci
l'est sous le nom `preview_send`.

On appelle la prévisualisation :

    GET /v1/messages/4/preview/

… elle retourne :

    HTTP 200 OK
    {
        "recipients": [
            "mon-destinataire@domaine.tld",
            "john@domaine.tld",
            "fox@autre-domaine.tld"
        ],
        "excluded_recipients": [
            "jane@domaine.tld",
        ],
        "spam_score": 0.0,
        "is_spam": false,
        "html_message": "<div><body><h1>Mais ne marche pas sur mes chaussures en su\u00e9dine bleue</h1><p style=\"font-size: small\"><a href=\"UNSUBSCRIBE_URL\">Se d\u00e9sinscrire</a> pour ne plus recevoir ces emails</p></body></div>",
        "plaintext_message": "# Mais ne marche pas sur mes chaussures en su\u00e9dine bleue\n\n[Se d\u00e9sinscrire][1] pour ne plus recevoir ces emails\n\n   [1]: UNSUBSCRIBE_URL\n\n"
    }

* **recipients** et **excluded_recipients** se partagent les destinataires que
    vous avez définis à l'[étape 2.](#2-ajoutmodification-dune-liste-de-destinataires), dans excluded_recipients sont listés les
    destinataires auxquels la campagne ne sera pas envoyée pour des raisons de
    désinscription ([résiliation](/#desinscription-opt-out)) de leur part ou des raisons techniques ([bounces](/#bounce)).

### Envoi à quelques destinataires pilotes

À ce stade, vous pouvez envoyer votre message à un ou plusieurs destinataires
« pilotes », par exemple vous-même, des collègues, proches…

Cette étape peut vous servir, par exemple, à valider l'affichage de la campagne
sur différents webmails, terminaux mobiles…

Les mails générés seront identiques à ceux réellement envoyés à vos
destinataires, à ces exceptions près :

- le sujet sera préfixé par « *TEST* » ;
- les liens de désinscription et d'abus pointeront vers des pages inactives ;
- vous ne pourrez pas suivre l'état de livraison de ces emails.

La requête est la suivante :

    POST /v1/messages/1/preview_send/
    {
       "to": "peter@example.com, steven@example.com"
    }


## 4. Déclenchement de l'envoi

Une fois que tout semble bon, il n'y a plus qu'à changer l'attribut **status**
du message à *sending*, par exemple à l'aide d'une requête `PATCH` (en ne
passant que l'attribut *status*):

    PATCH /v1/campaigns/1/
	{
		"status"       :"sending"
    }

Si tout va bien, l'API retourne un `200 OK`.

Les mails commencent immédiatement à être envoyés aux destinataires, une
[notification](/#votre-compte-munchmail) vous est envoyée, une seconde sera
envoyée à la fin du processus.

## 5. Suivi des envois

### Suivi des emails

Vous pouvez avoir tous les emails en cours d'acheminement et utilisant
le lien `mails` de la campagne. Par exemple :

    GET /v1/messages/4/mails/
    {
        "count": 3,
        "next": "https://api.munchmail.net/v1/mails/?page=2",
        "previous": null,
        "results": [
        {
            "url": "https://api.munchmail.net/v1/messages/4/mails/1/",
            "to": "jane@domain.tld",
            "date": "2014-08-05T12:44:28.556Z",
            "last_status": {
                "status": "sent",
                "date": "2014-08-05T12:59:11Z",
                "raw_msg": "Accepted by local MTA"
            },
            "message": "https://api.munchmail.net/v1/messages/4/",

        },
        {
            "url": "https://api.munchmail.net/v1/messages/4/mails/2/",
            "to": john@domain.tld",
            "date": "2014-07-25T08:52:34.261Z",
            "last_status": {
                "status": "delivered",
                "date": "2014-07-25T12:07:49Z",
                "raw_msg": Delivered to remote server"
            },
            "message": "https://api.munchmail.net/v1/messages/4/",
        },
        {
            "url": "https://api.munchmail.net/v1/messages/4/mails/3/",
            "to": "mon-destinataire@domaine.tld",
            "date": "2014-07-30T08:40:56Z",
            "last_status": {
                "status": "hardbounced",
                "date": "2014-07-30T08:41:44Z",
                "raw_msg": "recipient mon-destinataire@domain.tld do not exist"
            },
            "message": "https://api.munchmail.net/v1/messages/4/",
        }
        ]
    }


Les différents `status` possibles sont
[décrits en annexe](../../annexes/#statuts-de-mails).


### Suivi des résiliations

Une [notification](/#votre-compte-munchmail) est envoyée à chaque résiliation.

Par ailleurs, il est possible de consulter les résiliations via l'API ; si votre
identifiant de client est le 42 :

    GET /v1/customers/42/opt_outs/

    {
        "count": 2,
        "next": null,
        "previous": null,
        "results": [
        	{
                "address": "jane-blacklist@example.com",
                "date": "2014-08-08T14:54:35Z",
                "origin": "mail",
                "campaign": null
            },
            {
                "address": "john-greylist@example.com",
                "date": "2014-08-08T14:55:16Z",
                "origin": "feedback-loop",
                "campaign": null
            },
    	]
    }


Le champ **origin** contient la raison de la désinscription, qui peut-être
automatique ou bien volontaire de la part de l'utilisateur ; les valeurs
possibles sont décrites
[en annexe](../../annexes/#types-de-desinscriptions-opt-outs).

Si vous recevez de nombreuses désinscriptions de type **abuse**, il faut vous
poser des question sur la légitimité de votre liste de
contacts. **N'utilisez pas de liste de mails achetées**… et respectez la
législation en vigueur.
