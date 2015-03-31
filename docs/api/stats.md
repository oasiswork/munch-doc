Statistiques
============

*Munchmail* expose des statistiques à propos des envois, permettant de calculer :

 - le nombre de rejet des messages
 - le nombre d'ouverture des messages
 - le nombre ou taux de clic sur chaque lien
 - le nombre de désabonnements
 - ...

Elles sont mises à jour en direct, au fur et à mesure des évènements.

Les nombres donnés sont en nombre de mails, à votre application de calculer les
pourcentages si vous le souhaitez.

Par Message
-----------

Il est possible d'obtenir des statistiques à l'échelle d'un Message.

    GET /v1/messages/1/stats/
    {
        "count": {
            "had_delay": 1,
            "done": 2,
            "in_transit": 1,
            "total": 3

        },
       "last_status": {
            "delivered": 1,
            "unknown": 0,
            "hardbounced": 1,
            "softbounced": 1,
            "error": 0,
            "sent": 0,
        },
        "timing": {
            "delivery_total": 45,
            "delivery_median": 0
        },
        "tracking": {
            "clicked": {
                "http://oasiswork.fr": 1,
                "http://doc.munchmail.net": 1,
                "any": 1
            },
            "opened": 1
        },
        "optout": {
            "web": 0,
            "bounce": 0,
            "feedback-loop": 1,
            "abuse": 0,
            "mail": 0,
            "total": 1
        }
    }

- **count** : nombre de mails en fonction de l'état d'avancement
    - *total* : nombre de mails envoyés
    - *had_delay* : subissent ou ont subi des délais (*soft-bounce*)
    - *in_transit* : toujours en cours d'acheminement
    - *done* : traitement terminé
- **last_status** : Nombre de mail parvenus à chaque statut. (détail des statuts
    [en annexe](../../annexes/#statuts-de-mails))
- **timing** (en secondes) : durée d'envoi pour l'ensemble des mails, et durée
    médiane d'un envoi
- **tracking** : informations de suivi, les compteurs sont systématiquement à
    zéro si [le suivi](../tracking) n'est pas activé.
    - **clicked** : nombre de correspondants ayant cliqué sur chaque lien, *any*
      donne le nombre de destinataires ayant cliqué sur un lien quelconque.
    - **opened** : nombre d'utilisateurs ayant ouvert le message et affiché le
      pixel caché (cf [suivi](../tracking))
- **optout** : nombre de désinscriptions totales et par type (types
               détaillés
               [en annexe](../../annexes/#types-de-desinscriptions-opt-outs))
