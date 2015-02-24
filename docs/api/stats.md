Statistiques
============

*Munchmail* expose des statistiques à propos des envois, permettant de calculer :

 - le taux de rejet des messages
 - le taux d'ouverture des messages
 - le taux de clic sur chaque lien

Elles sont mises à jour en direct, au fur et à mesure des évènements.

Par Message
-----------

Il est possible d'obtenir des statistiques à l'échelle d'un Message.

    GET /api/v1/messages/1/stats/
    {
       "mail_count": 3,
       "statuses": {
            "delivered": 1,
            "unknown": 0,
            "hardbounced": 1,
            "softbounced": 1,
            "error": 0,
            "sent": 0
        },
        "tracking": {
            "clicked": {
                "http://oasiswork.fr": 1,
                "http://doc.munchmail.net": 1,
                "any": 1
            },
            "opened": 1
        }
    }

* **mail_count** : nombre total de destinataires du message, quel que soit
  leur statut.
* **statuses** : distribution des mails envoyés par dernier statut connu.
* **tracking** : informations de suivi, les compteurs sont systématiquement à
    zéro si [le suivi](../tracking) n'est pas activé.
    * **clicked** : nombre de correspondants ayant cliqué sur chaque lien, *any*
      donne le nombre de destinataires ayant cliqué sur un lien quelconque.
    * **opened** : nombre d'utilisateurs ayant ouvert le message et affiché le
      pixel caché (cf [suivi](../tracking))

