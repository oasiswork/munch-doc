Catégories
==========

Les **catégories** sont une notion facultative, qui permet de grouper
de façon logique plusieurs `messages`, la ressource d'API associée est
`category`.

Des cas d'utilisation sont :

- Les différents numéros d'une Newsletter
- Les communications commerciales envoyées à une certaine base de clients
- ...

Si vous utilisez la notion de `category`, les
désinscriptions ne sont honorées qu'au sein d'une même catégorie. Un même
destinataire peut donc s'être désinscrit d'une catégorie mais continuer à
recevoir les communications d'une autre catégorie.


## Création d'une catégorie

    POST /v1/categories/
    {
        "name": Newsletter clients ACME"
    }


Retourne

    HTTP 201 CREATED
    {
        "url": "https://api.munchmail.net/v1/categories/2/",
        "name": "Newsletter clients ACME"
        "messages": [],
        "_links": {
            "opt_outs": {
                "href": "http://localhost:8000/v1/categories/2/opt_outs/"
            }
        }
    }

Notez le lien `opt_outs` permettant de lire la liste des [opt_outs](../tutoriel/#suivi-des-resiliations) associés à
une catégorie.

## Attacher des messages à une catégorie

Cela se passe via l'attribut `category` de la ressource `message`. Par exemple
si vous voulez attacher la catégorie précédemment créée au message
`https://api.munchmail.net/v1/messages/4/`, faites simplement :

    PATCH /v1/messages/4/
    {
        "category": "https://api.munchmail.net/v1/categories/2/"
    }

Vous pouvez bien évidemment définir cet attribut dès
[la création du message](../tutoriel/#1-creationedition-dun-message-avec-ses-attributs).

Placer l'attribut à `null` pour retirer l'association à la catégorie.
