# RuSh – Ride Sharing Inteligent

RuSh e o aplicație web de ride-sharing pe care am făcut-o pentru proiectul de la Tehnologii Web. Ideea de la care am plecat: în loc ca fiecare client să aștepte propriul șofer, un șofer poate lua până la 4 pasageri care pleacă din aceeași zonă. Așa scade și costul cursei, și timpul de așteptare.

E scrisă în PHP cu MySQL, rulează în Docker și folosește sesiuni PHP pentru login și pentru a face diferența între cele două tipuri de utilizatori: client și șofer.

## Ce face

- Înregistrare și login, cu rol de **client** sau **șofer** (șoferii completează în plus datele mașinii: nr. de înmatriculare, model, culoare).
- Clientul poate comanda o cursă (de unde pleacă, unde vrea să ajungă), iar costul se generează automat.
- Când un șofer preia o cursă, aplicația verifică întâi dacă există deja un șofer la aceeași locație de plecare care are sub 4 pasageri — dacă da, îi dă lui cursa. Asta e partea de ride-sharing propriu-zisă.
- Cursa trece prin stările: `cauta_sofer → acceptata → in_desfasurare → finalizata` (sau `anulata`).
- Șoferul își poate finaliza cursele fie pe rând, fie pe toate odată (când are mai mulți pasageri).
- La final, clientul dă rating șoferului și invers, șoferul dă rating clienților (inclusiv un rating de grup când au fost mai mulți în mașină).
- Pagina principală e responsive și schimbă conținutul (Acasă / Despre / Tarife) din JavaScript, fără reîncărcare.

## Ce am folosit

- **PHP 8.2** pe Apache
- **MySQL 5.7**
- extensia **mysqli** pentru baza de date
- HTML, CSS (am lucrat cu CSS Grid și variabile CSS) și JavaScript simplu, fără framework
- **Docker** + **Docker Compose** ca să nu trebuiască instalat nimic local

N-am folosit Composer sau npm — singura dependență e extensia mysqli, care se instalează singură când se construiește imaginea Docker.

## Structura fișierelor

Tot codul aplicației stă în folderul `www/` (care se montează în `/var/www/html` în container):

```
docker-compose.yml      # serviciile: web (Apache+PHP) și db (MySQL)
proiect_rush.sql        # schema bazei de date
www/
  Dockerfile            # imaginea PHP 8.2 + Apache cu mysqli
  db.php                # conexiunea la baza de date
  index.php             # pagina principală
  signup2.php           # înregistrare
  login1.php            # autentificare
  logout.php            # deconectare
  contul4.php           # profilul (vezi și editezi datele)
  PP3.php               # formularul de comandă a cursei (client)
  client_comanda5.php   # starea comenzii clientului
  preluare_sofer6.php   # logica de ride-sharing / atribuirea șoferului
  sofer_comanda9.php    # cursele disponibile pentru șofer
  cursa_sofer10.php     # cursa activă a șoferului
  cursa_activa7.php     # cursa activă a clientului
  fin_cursa_client8.php # ratingul dat de client
  fin_cursa_sofer_11.php# ratingul dat de șofer
  fin_cursa_grup.php    # ratingul de grup
  Images/               # pozele folosite
```

## Cum o pornești

Ai nevoie doar de Docker și Docker Compose instalate. PHP și MySQL nu trebuie instalate separat, totul e în containere.

1. Clonezi proiectul și intri în folder:

   ```bash
   git clone <URL_REPO>
   cd Tehnologii_Web_Stancu_Catinca_632AB
   ```

2. Pornești containerele:

   ```bash
   docker compose up --build
   ```

3. Importezi schema în baza de date:

   ```bash
   docker exec -i RuSh_DB mysql -uroot -proot rush_app < proiect_rush.sql
   ```

4. Deschizi în browser:

   ```
   http://localhost:8080
   ```

Web-ul rulează pe portul **8080**, iar MySQL e expus pe **3307** (în caz că vrei să te conectezi la bază din afară). Baza se numește `rush_app`, iar userul de root e `root` cu parola `root` — sunt valori de development, setate în `docker-compose.yml`.

## Baza de date

Sunt două tabele, definite în `proiect_rush.sql`:

- `utilizatori` – conturile (client sau șofer), cu datele mașinii pentru șoferi.
- `curse` – cursele, cu legături către client și șofer, status, cost și ratingurile pe care și le dau reciproc.

Hostul din `db.php` (`RuSh_DB`) e chiar numele containerului de MySQL din docker-compose, așa că cele două trebuie să rămână la fel dacă schimbi ceva.

## Cum circulă lucrurile prin aplicație

**Client:** index → signup/login → PP3 (comandă) → client_comanda5 (așteaptă șofer) → cursa_activa7 (e în cursă) → fin_cursa_client8 (dă rating).

**Șofer:** login → sofer_comanda9 (vede cursele) → preluare_sofer6 (preia, aici intervine ride-sharing-ul) → cursa_sofer10 (cursa activă) → fin_cursa_sofer_11 / fin_cursa_grup (dă rating).

## De știut

Proiectul e făcut pentru școală, așa că am lăsat câteva lucruri simple intenționat. Dacă l-aș duce mai departe, aș schimba în primul rând:

- parolele se salvează acum ca text simplu — ar trebui trecute prin `password_hash()`;
- interogările SQL pun direct ce scrie userul în query, deci sunt vulnerabile la SQL injection — ar trebui folosite prepared statements;
- datele de conectare la bază sunt scrise direct în cod, ar fi mai corect în variabile de mediu.
