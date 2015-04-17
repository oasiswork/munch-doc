Authentification & utilisateurs
===============================

La communication est chiffrée via HTTPS.

* Nous **authentifions votre application** (*clef d'API*) et isolons vos données
  de celles de nos autres clients
* Votre application **authentifie notre serveur** (*certificat serveur*)
* Le traffic entre votre application et notre serveur est **chiffré**

## Authentification par session (pour tests/debug)

Vous pouvez vous authentifier avec votre email et votre mot de passe, dans un
navigateur, à l'adresse
[https://api.munchmail.net/v1/api-auth/login/](https://api.munchmail.net/v1/api-auth/login/).

## Par clef d'API (HTTP Basic)

### Récupérer la clef d'API

Une fois connecté via la méthode précédente, vous pouvez vous rendre à l'adresse
[https://api.munchmail.net/v1/profile/](https://api.munchmail.net/v1/profile/)
pour récupérer la clef d'API de votre propre utilisateur humain.

Vous également aussi récupérer la clef d'une application via
[https://api.munchmail.net/v1/users/](https://api.munchmail.net/v1/users/apps/)

### S'authentifier

L'authentification de cette API s'appuie sur le mécanisme standard
[HTTP Basic](https://fr.wikipedia.org/wiki/Authentification_HTTP#M.C3.A9thode_Basic),

Référez-vous à la documentation de votre langage de programmation pour savoir
comment utiliser l'authentification *HTTP Basic*.

Les détails à fournir sont :

* login : *« api »*
* password : votre clef d'API (commençant par *« key- »*)

Les détails d'authentification sont à attacher à chaque requête.

L'authentification est abordée notamment dans nos
[exemples pour PHP](/exemples/php/).


## Utilisateurs & applications

Deux types d'utilisateurs existent :

* **utilisateur humains** (s'authentifie traditionellement via email / password)
    : consulter le portail de statistiques, lancer des envois manuellement
    depuis le portail, tester l'API depuis l'API navigable.
* **application** (s'authentifie via clef d'API): lancer des envois via l'API
    depuis votre intranet, CMS...


### Lister les utilisateurs.

Vous pouvez lister individuellement les applications :


    GET /v1/users/apps/
    {
        "count": 1,
        "next": null,
        "previous": null,
        "page_count": 1,
        "results": [
            {
                "url": "http://localhost:8000/v1/users/apps/6/",
                "identifier": "erp",
                "type": "app",
                "api_key": "key-8EgL2KEwrYOh3JP0mTAFSpY1yH",
                "_links": {
                    "regen_api_key": {
                        "href": "http://localhost:8000/v1/users/6/regen_api_key/"
                    }
                }
            }
       ]
    }

Ou pour les utilisateurs humains :

    GET /v1/users/humans/
    {
        "count": 2,
        "next": null,
        "previous": null,
        "page_count": 1,
        "results": [
            {
                "url": "http://localhost:8000/v1/users/humans/1/",
                "identifier": "admin@example.com",
                "type": "human",
                "api_key": "key-N5sej2NEOlMcbldXE9GcjhTax1",
                "_links": {
                    "regen_api_key": {
                        "href": "http://localhost:8000/v1/users/1/regen_api_key/"
                    }
                }
            },
            {
                "url": "http://localhost:8000/v1/users/humans/2/",
                "identifier": "john@example.com",
                "type": "human",
                "api_key": "key-RTANLQBoG3lQIdklblgH3W6HFi",
                "_links": {
                    "regen_api_key": {
                        "href": "http://localhost:8000/v1/users/5/regen_api_key/"
                    }
                }
            }
        ]
    }

L'URL */v1/users/* vous permet de lister tous les utilisateurs quels que soient
leurs types.

### Ajouter une application


Il vous suffit de renseigner un identifiant que vous choisissez dans le champ *identifier*:

    POST /v1/users/apps/
    {
        "identifier": "foo@example.com"
    }


Ce qui devrait vous retourner :

    HTTP 201 CREATED
    {
        "url": "http://localhost:8000/v1/users/apps/13/",
        "identifier": "my-erp",
        "type": "app",
        "api_key": "key-XuZD7VSeq6ccy0s9548O0Rqqv6",
        "_links": {
            "regen_api_key": {
                "href": "http://localhost:8000/v1/users/13/regen_api_key/"
            }
        }
    }

### Ajouter un utilisateur humain

Pour le moment, l'ajout d'utilisateur humain doit passer par nous, merci de
[nous contacter](http://www.oasiswork.fr/contact/).

<!--
Il vous suffit de renseigner un email dans le champ *identifier* :

    POST /v1/users/humans/
    {
        "identifier": "foo@example.com"
    }


devrait retourner


    HTTP 201 CREATED
    {
        "url": "http://localhost:8000/v1/users/humans/12/",
        "identifier": "jane-doe@example.com",
        "type": "human",
        "api_key": "key-5mqt3p8LfxItl0UvmkZ1G3oYn6",
        "_links": {
            "regen_api_key": {
                "href": "http://localhost:8000/v1/users/12/regen_api_key/"
            }
        }
    }
-->

## Droits

Tous vos utilisateurs & applications possèdent les droits sur l'ensemble de vos objets
(messages, catégories...).

---

*ⓘ Des droits plus fins pourront être appliqués dans des version ultérieures de
 l'API.*

---
