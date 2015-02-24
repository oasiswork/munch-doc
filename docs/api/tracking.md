Suivi des actions côté destinataire
===================================


Munch offre la possibilité, dans une certaine mesuer,  de suivre les messages
envoyés jusque dans la boîte de réception :

- destinataires ayant cliqué sur les liens de l'email
- destinataires ayant ouvert le mail

----

*⚠ La vérification à l'ouverture du mail n'est pas à considérer comme un accusé
 de réception, car elle nécessite que l'utilisateur autorise les images externes
 dans son client mail.*

 *Cela reste un bon indicateur pour comparer l'intérêt  porté à deux envois.*

---


Configuration
-------------

Le suivi peut-être activé ou non pour chaque `message`, grace aux deux
paramètres de la ressource (par défaut désactivés) :

* **track_open**   : surveille les ouvertures
* **track_clicks** : surveille les clics


Par exemple, pour créer un message incluant les deux formes de suivi :

    POST /api/v1/messages/
	{
	    "name"         :"Newsletter de Juillet",
		"sender_email" :"newsletter@example.com",
        "sender_name"  :"Communication ACME chaussures",
        "subject": "Tu peux faire tout ce que tu veux",
        "html": "<h1>Mais ne marche pas sur mes chaussures en suédine bleue</h1>",
        "track_open"   : true,
        "track_clicks" : true,
    }


Consultation
------------

### Statistiques par message

L'usage le plus intéressant de ce suivi est certainement [les
statistiques](../stats). Munchmail vous permet donc de consulter, pour chaque
`message` :

- nombre d'ouverture détectées
- nombre de clics, global et par url

Voir la [section statistiques](../stats).

### Détail par destinataire

Il est ensuite possible de voir le détail des clics et ouvertures pour chaque
`mail` envoyé.

    GET /api/v1/messages/1/mails
    [
        {
            "url": "https://api.munchmail.net/api/v1/mails/1/",
            "to": "testrcpt@example.com",
            "date": "2014-07-25T08:52:34.261335Z",
            "last_status": {
                "status": "delivered",
                "date": "2014-07-25T12:07:49Z",
                "raw_msg": "k.xau"
            },
            "message": "https://api.munchmail.net/api/v1/messages/1/",
            "tracking": {
                "opened": "2015-02-24T09:18:54.597617Z",
                "clicked": [
                    {
                        "url": "http://oasiswork.fr",
                        "date": "2015-02-24T13:52:26Z"
                    },
                    {
                        "url": "http://doc.munchmail.net",
                        "date": "2015-02-24T13:58:38Z"
                    }
                ]
            }
        },
        {
            "url": "https://api.munchmail.net/api/v1/mails/2/",
            "to": "john-greylist@example.com",
            "date": "2014-07-30T08:40:56Z",
            "last_status": {
                "status": "softbounced",
                "date": "2014-07-30T08:41:44Z",
                "raw_msg": "greylisted !"
            },
            "message": "https://api.munchmail.net/api/v1/messages/1/",
            "tracking": {
                "opened": null,
                "clicked": []
            }
        }
    ]


