L'API
=====

L'API est accessible à l'adresse *https://api.munchmail.fr*. Elle
requiert une authentification pour fonctionner.

-----

**⚠ Ne faites pas vos tests sur
  [api.munchmail.net](https://api.munchmail.net), utilisez
  l'[instance de démonstration](/demonstration/)**.

----


Elle suit les principes
[HTTP/REST](http://fr.wikipedia.org/wiki/Representational_State_Transfer), et utilise
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

Points notables
----------------

* Les urls des objets (rappelés dans des attributs JSON **url**) sont
  l'identifiant unique d'un objet dans le système. Elles peuvent-être
  utilisées à la fois pour consulter l'objet (requête `GET`) ou le modifier
  (requête `PUT`).
* Si accédée depuis un client `HTTP` ne présentant pas de `Content-type` (ex:
  *php5-curl*), l'API dialogue par défaut en JSON.

Authentification
----------------

L'authentification de cette API s'appuie sur SSL, ce qui offre une manière
standard les fonctionalités suivantes :

* Nous **authentifions votre application** (*certificat client*)
* Votre application **authentifie notre serveur** (*certificat serveur*)
* Le traffic entre votre application et notre serveur est **chiffré**

Référez-vous à la documentation de votre langage de programmation pour savoir
comment utiliser un certificat client (tous les langages courants le proposent,
sans installation de bibliothèque supplémentaire).

L'authentification est abordée notamment dans nos
[exemples pour PHP](/exemples/php/).

Une campagne de A à Z
---------------------

### 1. Création/édition de la campagne avec ses attributs

    POST /api/v1/campaigns/
	{
	    "name"         :"Newsletter de Juillet",
		"sender_email" :"newsletter@example.com",
        "sender_name"  :"Communication ACME chaussures",
        "tech_contacts":"admins@example.com, communication@example.com",
        "owners"       :"communication@example.com"
    }

* **name** : Le nom interne donné à la campagne, il ne sera pas visible de vos
   destinataires.
* **sender_email** : le mail d'expéditeur de la campagne, vous devez être
   propriétaire du domaine concerné (ici *example.com*), et en y avoir
   [correctement configuré SPF et DKIM](/#prerequis).
* **sender_name** : le nom de l'expéditeur, qui apparaîtra dans le client mail de
   vos destinataires.
* **tech_contacts** : liste d'emails, séparées par des virgules, ils recevront
   notamment les notifications de résiliation
* **owners** : liste d'emails, séparées par des virgules, FIXME

Si tout se passe bien, l'API devrait vous retourner :

    HTTP 201 CREATED
    {
        "customer": 1024,
        "message": "https://demo.munchmail.fr/api/v1/campaigns/6/message/",
        "mails": "http://localhost:8000/api/v1/campaigns/6/mails/",
        "completion_date": null,
        "preview": "https://demo.munchmail.fr/api/v1/campaigns/6/preview/",
        "url": "http://localhost:8000/api/v1/campaigns/6/",
        "name": "Newsletter de Juillet",
        "status": "new",
        "sender_email": "newsletter@example.com",
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
* **message** est un lien vers le message de la campagne (voir [étape 3.](/#3-ajoutmodification-et-previsualisation-du-message))
* **mails** est un lien vers la liste des destinataires et leur état courant (voir
   [étape 2](#2-ajoutmodification-dune-liste-de-destinataires))
* **preview** Permet de voir un résumé de la campagne, utile notamment avant de
   [valider la campagne](#4-validation-de-la-campagne-envoi)

En cas d'erreur, vous recevrez une erreur 400 détaillant l'erreur, qui peuvent
notamment concerner les enregistrements [DKIM](/#enregistrement-dkim) ou [SPF](/#enregistrement-spf)


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

#### Ajout des destinataires par lot

    POST /api/v1/campaigns/6/mails/
    [
	    {"to" : "john@domaine.tld"},
	    {"to" : "jane@domaine.tld"},
	    {"to" : "fox@autredomaine.tld"},
    ]

Que vous ajoutiez les destinataires individuellement ou en lot, vous recevrez
les statuts HTTP suivants :

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
à la main, il peut également être produit avec un éditeur WYSIWYG, un traitement
de texte… etc.

Pour des conseils sur la rédaction de mails en HTML, vous pouvez vous référer à
[ce guide](http://kb.mailchimp.com/article/how-to-code-html-emails/) ou encore
[ce dépôt d'exemples](https://github.com/mailchimp/Email-Blueprints).

Niveau API, on défini le message de notre campagne d'exemple de la manière
suivante :

    PUT /api/v1/campaigns/1/message/
	{
       "subject": "Tu peux faire tout ce que tu veux",
       "html": "<h1>Mais ne marche pas sur mes chaussures en suédine bleue</h1>"
	}


* **subject** est le sujet de l'email
* **html** est le corps du message, en HTML

Le message fourni dans le paramètre **html** fera l'objet de plusieurs traitements :

* Un « nettoyage » pour apparaître au mieux sur tous les clients mail (voir
  [liste des traitements appliqués](/annexes/#details-des-modifications-appliquees-aux-emails))
* La génération d'une version « texte brut » à partir de ce dernier, fourni en
  tant qu'alternative (il est nécessaire de fournir une version texte brut
  dans les emails).
* Un test anti-spam, pour vérifier qu'il ne risque pas d'être considéré comme
  spam.



La réponse à notre requête PUT ressemblerait à :


    HTTP 201 CREATED
    {
        "url": "https://demo.munchmail.fr/api/v1/campaigns/1/message/",
        "subject": "Tu peux faire tout ce que tu veux",
        "html": "<h1>Mais ne marche pas sur mes chaussures en su\u00e9dine bleue</h1>",
        "is_spam": false,
        "spam_score": 0.0,
        "spam_details": "https://demo.munchmail.fr/api/v1/campaigns/1/message/spam_details/",
        "preview": "http://localhost:8000/api/v1/campaigns/1/message/preview/",
        "html_preview": "https://demo.munchmail.fr/api/v1/campaigns/1/message/preview/.html",
        "plaintext_preview": "http://localhost:8000/api/v1/campaigns/1/message/preview/.txt"
    }


* **is_spam** devrait vous alerter si il est à `true` (viser le zéro de
    **spam_score** peut-être un bon objectif), le système refusera quoi qu'il en
    **soit d'envoyer un **message considéré comme spam ;
* **spam_details** permet d'accéder au barème détaillé du *spam_score*, afin de
    pouvoir apporter les modifications nécessaires au contenu de **html**.
* **preview**, **html_preview** et **plaintext_preview** permettent d'avoir une
    idée du code et du rendu du mail une fois « nettoyé » par MunchMail.

### 4. Validation de la campagne (envoi)

Tout est prêt, la campagne n'est pas considérée comme spam, le département de
communication est aux anges, il est temps de démarrer l'envoi de la campagne.

Une dernière vue de validation est offerte (mentionnée dans l'objet *campagne*,
attribut **preview**) (cf [étape 1.](#1-creationedition-de-la-campagne-avec-ses-attributs)).

Par exemple…


    GET /api/v1/campaigns/1/preview/

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

Les informations de spam sont les mêmes qu'à l'étape précédente, ainsi que la
prévisualisation.

* **recipients** et **excluded_recipients** se partagent les destinataires que
    vous avez définis à l'[étape 2.](#2-ajoutmodification-dune-liste-de-destinataires), dans excluded_recipients sont listés les
    destinataires auxquels la campagne ne sera pas envoyée pour des raisons de
    désinscription ([résiliation](/#desinscription-opt-out)) de leur part ou des raisons techniques ([bounces](/#bounce)).

Pour avoir le détail des résiliation, se référer à l'étape suivante.

Une fois que tout semble bon, il n'y a qu'à changer l'attribut **status** de la
campagne à sending à l'aide d'une requête `PUT` (en passant tous les attributs
de la campagne) ou `PATCH` (en ne passant que l'attribut *status*):


    PATCH /api/v1/campaigns/1/
	{
		"status"       :"sending"
    }

Si tout va bien, l'API retourne un `200 OK`.

Les mails commencent à-partir de ce moment à être envoyés aux destinataires.

### 5. Suivi des envois

#### Suivi des emails

Vous pouvez avoir tous les emails en cours d'acheminement et utilisant
l'attribut `mails` de la campagne. Par exemple :

    GET https://demo.munchmail.fr/api/v1/campaigns/6/mails/"
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

    GET /api/v1/customers/42/opt-outs/

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
