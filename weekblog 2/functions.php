<?php
// functions.php - Helper functies

function h($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDatumNL($datum) {
    $maanden = [
        1 => 'januari', 2 => 'februari', 3 => 'maart', 4 => 'april',
        5 => 'mei', 6 => 'juni', 7 => 'juli', 8 => 'augustus',
        9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'december'
    ];
    $ts = strtotime($datum);
    if (!$ts) return h($datum);
    return date('j', $ts) . ' ' . $maanden[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

/**
 * Verwerkt een geüploade afbeelding.
 * Retourneert de bestandsnaam (relatief t.o.v. uploads/) of null als er geen bestand is.
 * Gooit een Exception bij een ongeldig bestand.
 */
function verwerkAfbeeldingUpload($fileField, $uploadDir) {
    if (empty($_FILES[$fileField]['name'])) {
        return null;
    }

    $file = $_FILES[$fileField];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Fout bij uploaden van de afbeelding (code ' . $file['error'] . ').');
    }

    $toegestaneTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!isset($toegestaneTypes[$mime])) {
        throw new Exception('Ongeldig bestandstype. Toegestaan: JPG, PNG, GIF, WEBP.');
    }

    $maxBytes = 5 * 1024 * 1024; // 5 MB
    if ($file['size'] > $maxBytes) {
        throw new Exception('Bestand is te groot. Maximaal 5 MB toegestaan.');
    }

    $ext = $toegestaneTypes[$mime];
    $nieuweNaam = uniqid('post_', true) . '.' . $ext;
    $doelpad = rtrim($uploadDir, '/') . '/' . $nieuweNaam;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $doelpad)) {
        throw new Exception('Kon de afbeelding niet opslaan.');
    }

    return $nieuweNaam;
}

function verwijderAfbeeldingBestand($bestandsnaam, $uploadDir) {
    if ($bestandsnaam) {
        $pad = rtrim($uploadDir, '/') . '/' . $bestandsnaam;
        if (file_exists($pad)) {
            unlink($pad);
        }
    }
}
