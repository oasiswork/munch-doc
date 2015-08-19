Gestion des utilisateurs
=======================

Par défaut, vous avez un unique compte utilisateur avec les droits de
**manager**. Cela peut vous suffire dans certains cas, mais munch vous offre la
possibilité de gérer plusieurs utilisateurs et applications, avec des droits
différents.


## Utilisateurs & applications

Deux types d'utilisateurs existent :

* **utilisateur humain** (s'authentifie traditionnellement
  [via email / password](/api/auth/#authentification-par-session-pour-testsdebug))
    : consulter le portail de statistiques, lancer des envois manuellement
    depuis le portail ou zimbra, tester l'API depuis
    l'[API navigable](/api/intro/#api-navigable).
* **application** (s'authentifie
    [via clef d'API](/api/auth/#par-clef-dapi-http-basic)) : lancer des envois
    via l'API depuis votre intranet, CMS...


## Groupes

Ces utilisateurs peuvent appartenir à un ou plusieurs groupes par mis :

- **managers** : Ont tout pouvoir (au sein d'un compte client donné), notamment
  celui de [gérer les comptes utilisateurs](/api/users/#gerer-les-comptes)
- **users** : Peuvent créer, envoyer, consulter, modifier des messages, mais ne
  peuvent voir et/ou modifier ceux des autres utilisateurs.

## Gérer les comptes

### Lister les utilisateurs.


Vous pouvez lister tous les utilisateurs humains :

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
                "groups": ["managers"],
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
                "groups": ["users"],
                "api_key": "key-RTANLQBoG3lQIdklblgH3W6HFi",
                "_links": {
                    "regen_api_key": {
                        "href": "http://localhost:8000/v1/users/5/regen_api_key/"
                    }
                }
            }
        ]
    }


... ou les applications :


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
                "groups": ["users"],
                "api_key": "key-8EgL2KEwrYOh3JP0mTAFSpY1yH",
                "_links": {
                    "regen_api_key": {
                        "href": "http://localhost:8000/v1/users/6/regen_api_key/"
                    }
                }
            }
       ]
    }


L'URL */v1/users/* vous permet de lister tous les utilisateurs quels que soient
leurs types.

### Ajouter un utilisateur humain

Pour le moment, l'ajout d'utilisateur humain doit passer par nous, merci de
[nous contacter](http://www.oasiswork.fr/contact/).

Il vous suffit de renseigner un email dans le champ *identifier* et une liste
de groupes :

    POST /v1/users/humans/
    {
        "identifier": "foo@example.com",
        "groups": ["users"]
    }


devrait retourner


    HTTP 201 CREATED
    {
        "url": "http://localhost:8000/v1/users/humans/12/",
        "identifier": "jane-doe@example.com",
        "type": "human",
        "groups": ["users"]
        "api_key": "key-5mqt3p8LfxItl0UvmkZ1G3oYn6",
        "_links": {
            "regen_api_key": {
                "href": "http://localhost:8000/v1/users/12/regen_api_key/"
            }
        }
    }


### Ajouter une application


Il vous suffit de renseigner un identifiant que vous choisissez dans le champ *identifier*:

    POST /v1/users/apps/
    {
        "identifier": "foo@example.com",
        "groups": ["users"]
    }


Ce qui devrait vous retourner :

    HTTP 201 CREATED
    {
        "url": "http://localhost:8000/v1/users/apps/13/",
        "identifier": "my-erp",
        "type": "app",
        "groups": ["users"],
        "api_key": "key-XuZD7VSeq6ccy0s9548O0Rqqv6",
        "_links": {
            "regen_api_key": {
                "href": "http://localhost:8000/v1/users/13/regen_api_key/"
            }
        }
    }



---

*ⓘ Vous pouvez bien entendu modifier ou supprimer des utilisateurs existants à
l'aide de requêtes `DELETE` ou `PUT`.*

---
