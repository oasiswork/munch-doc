## Besoins matériels

---

⚠ Cette section concerne une installation de munchmail sur vos propres serveurs.

---

Il est recommandé de prévoir 3 machines (virtuelles) :

- une machine RabbitMQ
- une machine PostgreSQL
- une machine pour l'application elle-même

Elles doivent-être sur le même réseau local (connectivité >=1Gbps).

Les besoins les plus pressants sont en RAM, et dépendent du nombre de mails que vous souhaitez pouvoir gérer de manière concurente :


Nb. mails concurents | RAM app | RAM postgresql | RAM rabbitmq |
---------------------|---------|----------------|--------------|
 <= 10.000           | 256Mio  | 256Mio         | 128Mio       |
 <= 100.000          | 512Mio  | 512Mio         | 512Mio       |
 <= 1.000.000        | 2Gio    | 512Mio         | 4Gio         |
