L'API
=====

L'API est accessible à l'adresse *[https://api.munchmail.net](https://api.munchmail.net)*. Elle
requiert une authentification pour fonctionner.

Elle suit les principes
[HTTP/REST](http://fr.wikipedia.org/wiki/Representational_State_Transfer), et utilise
le format [JSON](http://fr.wikipedia.org/wiki/JavaScript_Object_Notation), ce
qui la rend exploitable depuis un grand nombre de langages de programmation.

Les étapes de la vie d'une campagne sont :

1. Création/édition d'un message et de ses attributs
2. Ajout/modification d'une liste de destinataires
4. Validation de la campagne (envoi)
5. Suivi des envois

Tant que la campagne n'est pas validée, elle peut-être modifiée.

Points notables
----------------

* Les urls des objets (rappelés dans des attributs **url** de chaque ressource)
  constituent l'identifiant unique d'un objet dans le système. Elles
  peuvent-être utilisées à la fois pour consulter l'objet (requête `GET`) ou le
  modifier (requête `PUT` ou `PATCH`).
* Si accédée depuis un client `HTTP` ne présentant pas de `Content-type` (ex:
  *php5-curl*), l'API dialogue par défaut en JSON.


API navigable
---------------

L'API est *navigable*, en accédant à
*[https://api.munchmail.net/api/v1](https://api.munchmail.fr/api/v1)*
directement depuis votre navigateur, puis en vous
[authentifiant avec votre email](../auth/#par-session-pour-testsdebug), vous
pouvez exploiter toutes les possibilités offertes à votre application depuis une
interface conviviale, conçue pour les humains.

Cet usage est évidemement à réserver à des fins de debug et de test. Elle n'est
en aucun cas une interface pour les utilisateurs finaux.

Les **attributs des différentes resources** sont documentées de manière
précise au sein de cette interface.
