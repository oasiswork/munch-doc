L'API
=====

L'API est accessible à l'adresse *[https://api.munchmail.net](https://api.munchmail.net)*. Elle
requiert une authentification pour fonctionner.

Elle suit les principes
[HTTP/REST](http://fr.wikipedia.org/wiki/Representational_State_Transfer), et utilise
le format [JSON](http://fr.wikipedia.org/wiki/JavaScript_Object_Notation), ce
qui la rend exploitable depuis un grand nombre de langages de programmation.

Points notables
----------------

* Les urls des objets (rappelés dans des attributs **url** de chaque ressource)
  constituent l'identifiant unique d'un objet dans le système. Elles
  peuvent-être utilisées à la fois pour consulter l'objet (requête `GET`) ou le
  modifier (requête `PUT` ou `PATCH`).
* Si accédée depuis un client `HTTP` ne présentant pas de `Content-type` (ex:
  *php5-curl*), l'API dialogue par défaut en JSON (cela peut
  [être changé](export/)).

Pagination
-----------

Les requêtes renvoyant des listes d'objets sont *paginées*, elles adoptent la structure suivante :


    {
        "count": 78,
        "next": "https://api.munchmail.net/v1/mails/?page=2",
        "previous": null,
        "page_count": 2,
        "results": [

        ...

        ]
    }

Les liens "next" et "previous" permettent d'aller à la page suivante ou
précédente sans se poser d'autre question.

Vous pouvez augmenter le nombre d'objets par page (maximum : 1000) en ajoutant le
querystring `page_size`. Par exemple, pour avoir des pages de 200 objets,
ajoutez`?page_size=200` à l'URL.


Critère de tri
--------------

Il est possible d'indiquer un critère de tri pour chaque vue de l'API présentant
une liste  grâce au paramètre **ordering**. Par exemple, pour avoir les `mails`
d'un message par adresse d'expéditeur anti-alphabétique (noter le « `-` ») :

    GET /messages/?ordering=-address

Vous pouvez obtenir la liste des critères de tri / filtre disponibles et le
critère de tri par défaut directement depuis l'[API navigable](#api-navigable)
(ex, pour les messages :
[/v1/messages/](https://api.munchmail.net/v1/messages/)).

Filtrage des résultats
----------------------

Toutes les vues en liste vous permettent de filtrer les résultats selon des
valeurs de champs en spécifiant le champ et la valeur en
[querystring](https://en.wikipedia.org/wiki/Query_string).

Par exemple, pour avoir tous les mails délivrés avec succès :

    GET /v1/mails/?last_status=delivered

Les critères de filtrage sont les mêmes que ceux disponibles
[pour le tri](#critere-de-tri).

API navigable
---------------

L'API est *navigable*, en accédant à
*[https://api.munchmail.net/v1](https://api.munchmail.net/v1)*
directement depuis votre navigateur, puis en vous
[authentifiant avec votre email](../auth/#par-session-pour-testsdebug), vous
pouvez exploiter toutes les possibilités offertes à votre application depuis une
interface conviviale, conçue pour les humains.

Cet usage est évidemment à réserver à des fins de debug et de test. Elle n'est
en aucun cas une interface pour les utilisateurs finaux.

Les **attributs des différentes ressources** sont documentées de manière
précise au sein de cette interface.
