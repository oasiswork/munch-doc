## Annexes

Modifications appliquées aux emails
-----------------------------------------------

- les images sont détachées et hébergées sur nos serveurs (pour éviter de
  surcharger les votres)
- le javascript et le flash sont retirés (ils ne sont que
  [rarement supportés](https://www.campaignmonitor.com/resources/will-it-work/))
- les éventuelles erreurs HTML sont corrigées
- les règles CSS sont appliquées directement aux attributs (on parle
  d'*inlining*), la plupart des clients mail ne supportant pas les styles externes.

Statuts de Mail
---------------

Statuts pour les ressource de type `mail` qu'on également dans
[les statistiques](../api/stats/).

* **unknown** : le message n'est pas encore entré dans
    l'infrastructure mail
* **sent** : le message a été accepté par les serveurs d'oasiswork est en cours
    d'acheminement
* **delivered** : le message a été remis au serveur du destinataire
* **softbounced** : le message a été rejeté à plusieurs reprises,
    il n'a pu être remis.
* **hardbounced** : le message a été rejeté net par le serveur distant
    (hard-bounce).


Types de désinscriptions (opt-outs)
------------------------------------

* **mail** Un mail de désinscription a été envoyé à l'adresse `List-Unsubscribe`
    mentionnée dans un mail reçu par un de vos destinataires ;
* **web** Un destinataire a utilisé le formulaire du lien inséré en bas de
    l'email pour se désinscrire
* **feedback-loop** Le destinataire a marqué un de vos messages comme spam, et
    son hébergeur nous a remonté l'information
* **bounce** L'adresse du destinataire a produit trop d'erreurs de livraisons
    (ex: boite pleine, adresse inexistante…)
* **abuse** Le destinataire a signalé le message comme étant un abus
