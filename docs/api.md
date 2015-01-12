L'API
=====

L'API est accessible à l'adresse *[https://api.munchmail.net](https://api.munchmail.net)*. Elle
requiert une authentification pour fonctionner.

Elle suit les principes
[HTTP/REST](http://fr.wikipedia.org/wiki/Representational_State_Transfer), et utilise
le format [JSON](http://fr.wikipedia.org/wiki/JavaScript_Object_Notation), ce
qui la rend exploitable depuis un grand nombre de langages de programmation.

Les étapes de la vie d'une campagne sont :

1. Création/édition d'un message et de ses attributs
2. Ajout/modification d'une liste de destinataires
4. Validation de la campagne (envoi)
5. Suivi des envois

Tant que la campagne n'est pas validée, elle peut-être modifiée.

Points notables
----------------

* Les urls des objets (rappelés dans des attributs JSON **url**) sont
  l'identifiant unique d'un objet dans le système. Elles peuvent-être
  utilisées à la fois pour consulter l'objet (requête `GET`) ou le modifier
  (requête `PUT`).
* Si accédée depuis un client `HTTP` ne présentant pas de `Content-type` (ex:
  *php5-curl*), l'API dialogue par défaut en JSON.
* En accédant à
  *[https://api.munchmail.net/api/v1](https://api.munchmail.fr/api/v1)* depuis
  votre navigateur, vous pouvez naviguer l'API manuellement pour la découvrir ou
  tester.


Authentification
----------------

Il existe 2 modes d'authentification l'une dédiée à la navigation manuelle de
l'API et l'autre à l'exploitation par des applications.

### Authentification par formulaire (API naviguable) pour tests/debug

Vous pouvez vous authentifier manuellement pour naviguer l'API à l'adresse
https://api.munchmail.net/api/v1/api-auth/login/ à l'aide de votre adresse
e-mail et de votre mot de passe.

Une fois connecté, votre clef d'API vous est indiquée.

### Récupérer la clef d'API

Une fois connecté via formulaire, vous pouvez vous rendre à l'adresse
[https://api.munchmail.net/api/v1/profile/](https://api.munchmail.net/api/v1/profile/)
pour récupérer la clef d'API.

### Authentification HTTP Basic

L'authentification de cette API s'appuie sur le mécanisme
[HTTP Basic](https://fr.wikipedia.org/wiki/Authentification_HTTP#M.C3.A9thode_Basic),
par ailleurs, la communication est chiffrée via HTTPS. ce qui
offre d'une manière standard les fonctionalités suivantes :

* Nous **authentifions votre application** (*clef d'API*) et isolons vos données
  de celles de nos autres clients
* Votre application **authentifie notre serveur** (*certificat serveur*)
* Le traffic entre votre application et notre serveur est **chiffré**

Référez-vous à la documentation de votre langage de programmation pour savoir
comment utiliser l'authentification *HTTP Basic*.

Les détails à fournir sont :

* login: *api*
* mot de passe: votre clef d'API

L'authentification est abordée notamment dans nos
[exemples pour PHP](/exemples/php/).


Configuration des domaines d'envoi
----------------------------------

Si vous envoyer avec l'email *newsletter@example.com*, les étapes suivantessont
nécessaires.

1. Enregistrer le domaine *example.com* dans munchmail
2. Le configurer (cf [DKIM](/#enregistrement-dkim) ou
[SPF](/#enregistrement-spf))
3. De vérifier qu'il est bien configuré.

Enregistrer un nouveau domaine :

    POST /api/v1/domains/
    {
        "name": "example.com"
    }

L'objet retourné est du style:

    HTTP 201 CREATED
    {
        "url": "http://localhost:8000/api/v1/domains/1/",
        "name": "example.com",
        "spf_status": "bad",
        "dkim_status": "ko",
        "mx_status": "ok",
        "_links": {
            "revalidate": {
                "href": "http://localhost:8000/api/v1/domains/1/revalidate/"
            }
        }
    }

Les 3 vérifications automatiques sont `spf_status`, `dkim_status` et
`mx_status`. Elles peuvent prendre 3 états :

* *ok* La configuration DNS est correcte sur ce point
* *ko* L'enregistrement DNS ne peut pas être trouvé
* *bad* L'enregistrement DNS existe mais est incorrect

Une fois que vous avez modifié vos paramètres DNS en suivant
[la documentation]([SPF](/#enregistrement-spf)), vous pouvez relancer une
vérification :

    POST /api/v1/domains/revalidate/

Qui devrait, si tout va bien, mettre à jour le statut de votre domaine et vous
retourner :

    HTTP 200 OK
    {
        "dkim_status": "ok",
        "spf_status": "ok",
        "mx_status": "ok"
    }

Un Envoi de A à Z
---------------------

### 1. Création/édition d'un message avec ses attributs

    POST /api/v1/messages/
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
    adresse doit préalablement avoir été [configuré correctement](/domaines/).
* **sender_name** : le nom de l'expéditeur, qui apparaîtra dans le client mail de
   vos destinataires.
* **subject** : Le sujet de votre email.
* **html** : Le corps en HTML de votre email.

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

*ⓘ Factultativement, un message peut-être rattaché à une campagne, ce
point est traité dans la [section dédiée de la documentation](/campagnes/).*

----

Si tout se passe bien, l'API devrait vous retourner :

    HTTP 201 CREATED
    {
        "url": "https://api.munchmail.net/api/v1/messages/4/",
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
                "href": "https://api.munchmail.net/api/v1/messages/4/preview_send/"
            },
            "preview": {
                "href": "https://api.munchmail.net/api/v1/messages/4/preview/"
            },
            "preview/.html": {
                "href": "https://api.munchmail.net/api/v1/messages/4/preview/.html/"
            },
            "preview/.txt": {
                "href": "https://api.munchmail.net/api/v1/messages/4/preview/.txt/"
            },
            "mails": {
                "href": "https://api.munchmail.net/api/v1/messages/4/mails/"
            },
            "spam_details": {
                "href": "https://api.munchmail.net/api/v1/messages/4/spam_details/"
            }
        }
    }

Notons quelques champs :

* **url** est à conserver, elle vous permet d'accéder à votre message pour la
   visualiser ou la modifier.
* **status** nous donne l'état courant de la campagne
* **mails** est un lien vers la liste des destinataires et leur état courant (voir
   [étape 2](#2-ajoutmodification-dune-liste-de-destinataires))
* **preview** Permet de voir un résumé de la campagne, utile notamment avant de
   [valider la campagne](#4-validation-de-la-campagne)
* **is_spam** devrait vous alerter si il est à `true` (viser le zéro de
    **spam_score** peut-être un bon objectif), le système refusera quoi qu'il en
    **soit d'envoyer un **message considéré comme spam ;
* **spam_details** permet d'accéder au barème détaillé du *spam_score*, afin de
    pouvoir apporter les modifications nécessaires au contenu de **html**.
* **preview**, **html_preview** et **plaintext_preview** permettent d'avoir une
    idée du code et du rendu du mail une fois « nettoyé » par MunchMail.
* **preview_send** Permet d'envoyer la campagne à quelques destinataires de test
    (cf FIXME)

En cas d'erreur, vous recevrez une erreur 400 détaillant l'erreur, qui peuvent
notamment concerner les enregistrements [DKIM](/#enregistrement-dkim) et [SPF](/#enregistrement-spf)


    HTTP 400 BAD REQUEST
    {
        "sender_email": [
            "Le champ SPF pour example.com est mal configur\u00e9",
            "Pas de configuration DKIM pour le domaine example.com"
        ]
    }

En cas d'erreur de validation, **la campagne n'est pas créée**.

Pour *modifier* la campagne, il faut effectuer une requête de type `PUT` à
l'adresse retournée dans l'attribut **url** en envoyant la totalité du contenu
de l'objet, modifié comme souhaité.


#### À propos de la rédaction du corps HTML

Si la méthode qui donne les meilleurs résultats est de rédiger ce document HTML
à la main, il peut également être produit avec un éditeur WYSIWYG, un traitement
de texte… etc.

Pour des conseils sur la rédaction de mails en HTML, vous pouvez vous référer à
[ce guide](http://kb.mailchimp.com/article/how-to-code-html-emails/) ou encore
[ce dépôt d'exemples](https://github.com/mailchimp/Email-Blueprints).


### 2. Ajout/modification d'une liste de destinataires

Il est possible d'ajouter des destinataires soit un par un, soit en lots :

(on utilise l'adresse contenue dans l'attribut **message** de notre objet, voir
étape précédente).

#### Ajout des destinataires un par un

Il suffit de faire autant de requêtes POST que de destinataires à ajouter.

    POST /api/v1/mails/
    {
	    "to" : "mon-destinataire@domaine.tld",
        "message": "https://api.munchmail.net/api/v1/messages/4/"
    }

#### Ajout des destinataires par lot

    POST /api/v1/mails/
    [
	    {"to" : "john@domaine.tld",     "message": "https://api.munchmail.net/api/v1/messages/4/"},
	    {"to" : "jane@domaine.tld",     "message": "https://api.munchmail.net/api/v1/messages/4/"},
	    {"to" : "fox@autredomaine.tld", "message": "https://api.munchmail.net/api/v1/messages/4/"},
    ]

Que vous ajoutiez les destinataires individuellement ou en lot, vous recevrez
les statuts HTTP suivants :

- **201 (created)** si tout se passe bien
- **400 (bad request)** si l'email est invalide (adresse mal formée ou domaine
non habilité à recevoir du courriel)

Par exemple…


     POST /api/v1/mails/
     {
	    "to" : "jenesuispasunemail",
        "message": "https://api.munchmail.net/api/v1/messages/4/"
     }

… retournera

    400 BAD REQUEST
	{
	    "to": [
           "Saisissez une adresse de courriel valide."
        ]
	}


### 5. Prévisualisation et envoi à quelques destinataire « pilotes »

#### Prévisualisation

Une vue de validation est offerte (cf
[étape 1.](#1-creationedition-de-la-campagne-avec-ses-attributs)).

Par exemple…

    GET /api/v1/messages/4/preview/

… retourne :

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

Pour avoir le détail des résiliation, se référer à l'étape suivante.

#### Envoi à quelques destinataires pilotes

À ce stade, vous pouvez envoyer votre message à un ou plusieurs destinataires
« pilotes », par exemple vous-même, des collègues…

Cette étape peut vous servir, par exemple, à valider l'affichage de la campagne
sur différents webmails, terminaux mobiles…

Les mails générés seront identiques à ceux réellement envoyés à vos
destinataires, à ces exceptions près :

- le sujet sera préfixé par « *TEST* » ;
- les liens de désinscription et d'abus pointeront vers des pages inactives ;
- vous ne pourrez pas suivre l'état de livraison de ces emails.

    POST /api/v1/messages/1/preview_send/
	{
	   "to": "peter@example.com, steven@example.com"
	}

### 6. Déclenchement de l'envoi

Une fois que tout semble bon, il n'y a qu'à changer l'attribut **status** du
message à *sending*, par exemple à l'aide d'une requête `PATCH` (en ne passant
que l'attribut *status*):

    PATCH /api/v1/campaigns/1/
	{
		"status"       :"sending"
    }

Si tout va bien, l'API retourne un `200 OK`.

Les mails commencent à-partir de ce moment à être envoyés aux destinataires.

### 7. Suivi des envois

#### Suivi des emails

Vous pouvez avoir tous les emails en cours d'acheminement et utilisant
le lien `mails` de la campagne. Par exemple :

    GET https://api.munchmail.net/api/v1/messages/4/mails/"
	[
    {
        "to": "jane@domain.tld",
        "date": "2014-08-05T12:44:28.556Z",
        "last_status": {
            "status": "sent",
            "date": "2014-08-05T12:59:11Z",
            "raw_msg": "Accepted by local MTA"
        }
    },
    {
        "to": john@domain.tld",
        "date": "2014-07-25T08:52:34.261Z",
        "last_status": {
            "status": "delivered",
            "date": "2014-07-25T12:07:49Z",
            "raw_msg": Delivered to remote server"
        }
    },
    {
        "to": "mon-destinataire@domaine.tld",
        "date": "2014-07-30T08:40:56Z",
        "last_status": {
            "status": "hardbounced",
            "date": "2014-07-30T08:41:44Z",
            "raw_msg": "recipient mon-destinataire@domain.tld do not exist"
        }
    }
	]


Les différents statuts possibles sont :

* **unknown** : le message n'est pas encore entré dans
    l'infrastructure mail
* **sent** : le message a été accepté par les serveurs d'oasiswork est en cours
    d'acheminement
* **delivered** : le message a été remis au serveur du destinataire
* **softbounced** : le message a été rejeté à chaque tentative  pour
    *soft-bounce*, il n'a pu être remis.
* **hardbounced** : le message a été rejeté net par le serveur distant
    (hard-bounce).

#### Suivi des résiliations

Les contacts techniques sont notifiés par email à chaque résiliation.

Par ailleurs, il est possible de consulter les résiliations via l'API ; si votre
identifiant de client est le 42 :

    GET /api/v1/customers/42/opt_outs/

	[
	{
        "address": "jane-blacklist@example.com",
        "date": "2014-08-08T14:54:35Z",
        "origin": "mail"
    },
    {
        "address": "john-greylist@example.com",
        "date": "2014-08-08T14:55:16Z",
        "origin": "feedback-loop"
    },
	]


Le champ **origin** contient la raison de la désinscription, qui peut-être
automatique ou bien volontaire de la part de l'utilisateur ; il peut contenir
les valeurs suivantes:

* **mail** Un mail de désinscription a été envoyé à l'adresse `List-Unsubscribe`
    mentionnée dans un mail reçu par un de vos destinataires ;
* **web** Un destinataire a utilisé le formulaire du lien inséré en bas de
    l'email pour se désinscrire
* **feedback-loop** Le destinataire a marqué un de vos messages comme spam, et
    son hébergeur nous a remonté l'information
* **bounce** L'adresse du destinataire a produit trop d'erreurs de livraisons
    (ex: boite pleine, adresse inexistante…)
* **abuse** Le destinataire a signalé le message comme étant un abus

Si vous recevez de nombreuses désinscriptions de type **abuse**, il faut vous
poser des question sur la légitimité de votre liste de
contacts. **N'utilisez-pas de liste de mails achetées**… et respectez la
législation en vigueur.
