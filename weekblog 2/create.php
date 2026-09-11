<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$uploadDir = __DIR__ . '/uploads';

$fouten = [];
$titel = '';
$tekst = '';
$datum = date('Y-m-d');
$locatie = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titel   = trim($_POST['titel'] ?? '');
    $tekst   = trim($_POST['tekst'] ?? '');
    $datum   = trim($_POST['datum'] ?? '');
    $locatie = trim($_POST['locatie'] ?? '');

    if ($titel === '') $fouten[] = 'Titel is verplicht.';
    if ($tekst === '') $fouten[] = 'Tekst is verplicht.';
    if ($datum === '') $fouten[] = 'Datum is verplicht.';

    $afbeeldingNaam = null;
    if (empty($fouten)) {
        try {
            $afbeeldingNaam = verwerkAfbeeldingUpload('afbeelding', $uploadDir);
        } catch (Exception $e) {
            $fouten[] = $e->getMessage();
        }
    }

    if (empty($fouten)) {
        $stmt = $pdo->prepare("INSERT INTO posts (titel, tekst, datum, locatie, afbeelding) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$titel, $tekst, $datum, $locatie ?: null, $afbeeldingNaam]);

        header('Location: index.php?succes=aangemaakt');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe blogpost — Mijn Weekblog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <h1>📔 Mijn Weekblog</h1>
        <p>Een overzicht van mijn week — in tekst, beeld en plek</p>
    </header>

    <div class="container">
        <a href="index.php" class="terug-link">&larr; Terug naar overzicht</a>
        <h2>Nieuwe blogpost</h2>

        <?php if (!empty($fouten)): ?>
            <div class="alert alert-error">
                <ul style="margin:0; padding-left:1.2rem;">
                    <?php foreach ($fouten as $fout): ?>
                        <li><?= h($fout) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="post-form" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titel">Titel</label>
                <input type="text" id="titel" name="titel" value="<?= h($titel) ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="datum">Datum</label>
                    <input type="date" id="datum" name="datum" value="<?= h($datum) ?>" required>
                </div>
                <div class="form-group">
                    <label for="locatie">Locatie</label>
                    <input type="text" id="locatie" name="locatie" value="<?= h($locatie) ?>" placeholder="bijv. Amsterdam">
                </div>
            </div>

            <div class="form-group">
                <label for="tekst">Tekst</label>
                <textarea id="tekst" name="tekst" required><?= h($tekst) ?></textarea>
            </div>

            <div class="form-group">
                <label for="afbeelding">Afbeelding (optioneel, max 5MB — JPG/PNG/GIF/WEBP)</label>
                <input type="file" id="afbeelding" name="afbeelding" accept="image/jpeg,image/png,image/gif,image/webp">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Opslaan</button>
                <a href="index.php" class="btn btn-secondary">Annuleren</a>
            </div>
        </form>
    </div>
</body>
</html>
