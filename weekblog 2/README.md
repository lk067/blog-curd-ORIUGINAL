# Mijn Weekblog — PHP CRUD Applicatie

Een eenvoudige, complete weekblog-applicatie in PHP waarmee je blogposts kunt
aanmaken, bekijken, bewerken en verwijderen (CRUD). Elke post bevat:

- **Titel**
- **Tekst** (het verhaal van je week)
- **Datum**
- **Locatie**
- **Afbeelding** (upload van JPG, PNG, GIF of WEBP)

## Vereisten

- PHP 7.4 of hoger (met de `pdo_mysql` en `fileinfo` extensies — deze zijn
  standaard aanwezig op Plesk-hosting)
- Een MySQL-database in Plesk (zie hieronder)

## Installatie op Plesk

### 1. Database aanmaken in Plesk

1. Log in op je Plesk-paneel.
2. Ga naar **Websites & Domains** > je domein > **Databases**.
3. Klik op **Database toevoegen** (Add Database).
4. Geef de database een naam, bijv. `weekblog`.
5. Maak een databasegebruiker aan met een wachtwoord (of gebruik een
   bestaande), en onthoud: **databasenaam**, **gebruikersnaam**,
   **wachtwoord** en de **host** (bij de meeste Plesk-installaties is dit
   `localhost`, maar Plesk toont het juiste adres bij de databasegegevens).

### 2. Bestanden uploaden

1. Upload de hele inhoud van de map `weekblog/` naar de `httpdocs`-map van
   je domein (via **Bestandenbeheer** in Plesk, of via FTP/SFTP).
2. Zorg dat de map `uploads/` schrijfbaar is (rechten `755` of, indien nodig,
   `775`).

### 3. Databasegegevens invullen

Open `config.php` en vul de gegevens in die je in stap 1 hebt genoteerd:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'weekblog');
define('DB_USER', 'jouw_gebruiker');
define('DB_PASS', 'jouw_wachtwoord');
```

### 4. Tabel aanmaken

Dit gaat **automatisch**: zodra je de site voor het eerst bezoekt, maakt
`db.php` de tabel `posts` aan als die nog niet bestaat.

Wil je het liever vooraf zelf doen? Gebruik dan `schema.sql`:
1. Ga in Plesk naar **Databases** > jouw database > **phpMyAdmin** (of
   Adminer).
2. Open het tabblad **SQL** / **Uitvoeren**.
3. Plak de inhoud van `schema.sql` en voer uit.

### 5. Klaar

Bezoek `https://jouwdomein.nl/index.php` (of de submap waarin je hebt
geüpload) — de weekblog draait nu op je Plesk-database.

### Lokaal testen (optioneel)

Wil je eerst lokaal testen voordat je naar Plesk uploadt? Zorg dan voor een
lokale MySQL-database (bijv. via XAMPP/MAMP), vul die gegevens in
`config.php` in, en start:

```bash
cd weekblog
php -S localhost:8000
```

## Bestandenoverzicht

| Bestand         | Functie                                              |
|-----------------|-------------------------------------------------------|
| `config.php`    | Databasegegevens (host, naam, gebruiker, wachtwoord)  |
| `db.php`        | Databaseverbinding (MySQL) + tabel aanmaken indien nodig |
| `schema.sql`    | Optioneel: tabelstructuur handmatig importeren        |
| `functions.php` | Helperfuncties (uploads verwerken, HTML escapen, ...) |
| `index.php`     | Overzicht van alle blogposts (Read)                   |
| `create.php`    | Formulier + verwerking nieuwe post (Create)           |
| `edit.php`      | Formulier + verwerking bewerken post (Update)         |
| `delete.php`    | Verwerking verwijderen post (Delete)                  |
| `style.css`     | Vormgeving van de applicatie                          |
| `uploads/`      | Map waar geüploade afbeeldingen worden opgeslagen     |

## Beveiliging & aandachtspunten

- Alle gebruikersinvoer wordt ge-escaped bij het tonen (voorkomt XSS).
- Alle database-queries gebruiken **prepared statements** (voorkomt SQL-injectie).
- Bestandsuploads worden gecontroleerd op werkelijk MIME-type (niet alleen
  de bestandsextensie) en op maximale grootte (5 MB).
- Geüploade bestanden krijgen een unieke, gegenereerde bestandsnaam — de
  originele bestandsnaam van de gebruiker wordt nooit gebruikt.
- Wil je de app openbaar online zetten? Overweeg dan een eenvoudige
  login-check toe te voegen vóór `create.php`, `edit.php` en `delete.php`,
  zodat niet iedereen posts kan aanmaken of verwijderen.

## Uitbreidingsideeën

- Inlogscherm toevoegen zodat alleen jij posts kunt beheren.
- Meerdere afbeeldingen per post (fotogalerij).
- Tags of categorieën per week.
- Zoekfunctie op tekst, locatie of datum.
