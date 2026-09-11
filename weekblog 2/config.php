<?php
// config.php - Databasegegevens voor Plesk (MySQL)
//
// Vul hieronder de gegevens in die je in Plesk hebt aangemaakt:
// Plesk > Websites & Domains > Databases > (jouw database) > Databasegebruikers

define('DB_HOST', 'localhost');        // meestal 'localhost', soms een apart adres — zie Plesk
define('DB_NAME', 'jouw_database_naam');
define('DB_USER', 'jouw_database_gebruiker');
define('DB_PASS', 'jouw_database_wachtwoord');
define('DB_CHARSET', 'utf8mb4');
