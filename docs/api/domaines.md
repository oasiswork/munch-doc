Configuration des domaines d'envoi
==================================

Si vous envoyez avec l'email *newsletter@example.com*, les étapes suivantes sont
nécessaires.

1. Enregistrer le domaine *example.com* dans munchmail
2. Créer ou modifier les bons enregistrement DNS pour [DKIM](/#enregistrement-dkim) et [SPF](/#enregistrement-spf) (cf [notre guide](/#enregistrement-spf))
3. Vérifier le tout

----

*⚠ Il est nécessaire d'être [authentifié](../auth/) pour manipuler les domaines*.

----

Enregistrer un nouveau domaine :

    POST /api/v1/domains/
    {
        "name": "example.com"
    }

L'objet retourné est du style:

    HTTP 201 CREATED
    {
        "url": "https://api.munchmail.net/api/v1/domains/1/",
        "name": "example.com",
        "spf_status": "bad",
        "dkim_status": "ko",
        "mx_status": "ok",
        "_links": {
            "revalidate": {
                "href": "https://api.munchmail.net/api/v1/domains/1/revalidate/"
            }
        }
    }

Les 3 vérifications automatiques sont `spf_status`, `dkim_status` et
`mx_status`. Elles peuvent prendre 3 états :

* *ok* La configuration DNS est correcte sur ce point
* *ko* L'enregistrement DNS ne peut pas être trouvé
* *bad* L'enregistrement DNS existe mais est incorrect

Une fois que vous avez modifié vos paramètres DNS en suivant
[la documentation SPF](/#enregistrement-spf), vous pouvez relancer une
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


