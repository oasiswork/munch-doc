## FAQ

### Pourquoi un outil dédié ?

Il est théoriquement possible d'envoyer un email à de nombreux destinataires,
cependant :

- on finit vite classé en tant que spam ou rejetés (les serveurs de destination
  sont plus stricts sur les mails « massifs ») ;
- on n'a aucune idée de qui reçoit ou ne reçoit pas les mails ;
- quand les mails ne sont pas reçus, on reçoit tout un tas de mails d'erreur
  dans sa boite mail.

### Comment personnaliser le lien « Se désinscrire » ?

Un lien est inséré par défaut à la fin de votre document HTML pour permettre à
vos destinataires de se désabonner.

Si vous voulez que ce lien aie l'apparence de votre choix, il vous suffit de
mettre la chaîne `UNSUBSCRIBE_URL` là où vous voulez que l'adresse effective de
désinscription apparaisse, par exemple :

    Message ennuyeux ? Vous pouvez <a href="UNSUBSCRIBE_URL">vous désinscrire</a>


## Annexes

Détails des modifications appliquées aux emails :

- les images sont détachées et hébergées sur nos serveurs (pour éviter de
  surcharger les votres)
- le javascript et le flash sont retirés (ils ne sont que
  [rarement supportés](https://www.campaignmonitor.com/resources/will-it-work/))
- les éventuelles erreurs HTML sont corrigées
- les règles CSS sont appliquées directement aux attributs (on parle
  d'*inlining*), la plupart des clients mail ne supportant pas les styles externes.

<!--  LocalWords:  A-minima
 -->
