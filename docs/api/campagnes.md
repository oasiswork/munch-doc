Campagnes
=========

Les campagnes sont une notion facultative, qui permet de grouper
de façon logique plusieurs `messages`, la ressource d'API associée est
`campaign`.

Des cas d'utilisation sont :

- Les différents numéros d'une Newsletter
- Les communications commerciales envoyées à une certaine base de clients
- ...

Si vous utilisez la notion de `campaign`, les
désinscriptions ne sont honorées qu'au sein d'une même campagne. Un même
destinataire peut donc s'être désinscrit d'une campagne mais continuer à
recevoir les communications d'une autre campagne.


## Création d'une campagne

    POST /api/v1/campaigns/
    {
        "name": Newsletter clients ACME"
    }


Retourne

    HTTP 201 CREATED
    {
        "url": "https://api.munchmail.net/api/v1/campaigns/2/",
        "name": "Newsletter clients ACME"
        "messages": [],
        "_links": {
            "opt_outs": {
                "href": "http://localhost:8000/api/v1/campaigns/2/opt_outs/"
            }
        }
    }

Notez le lien `opt_outs` permettant de lire la liste des [opt_outs](../tutoriel/#suivi-des-resiliations) associés à
une campagne.

## Attacher des messages à une campagne

Cela se passe via l'attribut `campaign` de la ressource `message`. Par exemple
si vous voulez attacher la campagne précédemment créée au message
`https://api.munchmail.net/api/v1/messages/4/`, faites simplement :

    PATCH /api/v1/messages/4/
    {
        "campaign": "https://api.munchmail.net/api/v1/campaigns/2/"
    }

Vous pouvez bien évidemment définir cet attribut dès
[la création du message](../tutoriel/#1-creationedition-dun-message-avec-ses-attributs).

Placer l'attribut à `null` pour retirer l'association à la campagne.
