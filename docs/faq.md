## FAQ

### Pourquoi un outil dédié ?

Il est théoriquement possible d'envoyer un email à de nombreux destinataires,
cependant :

- on finit vite classé en tant que spam ou rejetés (les serveurs de destination
  sont plus stricts sur les mails « massifs ») ;
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


### Puis-je ajouter des pièces jointes ?


Oui, il faut toutefois que votre compte soit autorisé à le faire, contactez-nous
pour cela.

---

**⚠ Il est déconseillé d'utiliser des pièces-jointes dans des envois
en masse**. Il est beaucoup plus efficace d'héberger images et pièces-jointes
sur un serveur web et de fournir des liens. Cela allège l'envoi, l'accélère et
évite d'encombrer la boite mail de vos destinataires.

---


Pour ajouter une pièce-jointe sur la campagne `/api/v1/campaigns/1/` :

    POST /api/v1/campaigns/1/attachments/
	file: votre fichier

Cette requête *doit* avoir un `Content-Type` à `multipart/form-data` à l'inverse
de beaucoup d'autres qui transfèrent leur contenu en `application/json`.

<!--  LocalWords:  A-minima
 -->
