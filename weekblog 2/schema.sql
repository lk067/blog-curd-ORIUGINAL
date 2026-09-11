-- schema.sql
-- Handmatig importeren via Plesk > Databases > phpMyAdmin (of Adminer)
-- Nodig is dit NIET verplicht: db.php maakt deze tabel ook automatisch aan
-- bij het eerste bezoek. Handig als je de structuur liever vooraf wilt zien
-- of importeren zonder dat de site eerst hoeft te draaien.

CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titel VARCHAR(255) NOT NULL,
    tekst TEXT NOT NULL,
    datum DATE NOT NULL,
    locatie VARCHAR(255),
    afbeelding VARCHAR(255),
    aangemaakt_op TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
