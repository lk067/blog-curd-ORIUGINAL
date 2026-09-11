<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$uploadDir = __DIR__ . '/uploads';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: index.php');
    exit;
}

$fouten = [];
$titel   = $post['titel'];
$tekst   = $post['tekst'];
$datum   = $post['datum'];
$locatie = $post['locatie'];
$verwijderAfbeelding = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titel   = trim($_POST['titel'] ?? '');
    $tekst   = trim($_POST['tekst'] ?? '');
    $datum   = trim($_POST['datum'] ?? '');
    $locatie = trim($_POST['locatie'] ?? '');
    $verwijderAfbeelding = isset($_POST['verwijder_afbeelding']);

    if ($titel === '') $fouten[] = 'Titel is verplicht.';
    if ($tekst === '') $fouten[] = 'Tekst is verplicht.';
    if ($datum === '') $fouten[] = 'Datum is verplicht.';

    $nieuweAfbeelding = null;
    if (empty($fouten)) {
        try {
            $nieuweAfbeelding = verwerkAfbeeldingUpload('afbeelding', $uploadDir);
        } catch (Exception $e) {
            $fouten[] = $e->getMessage();
        }
    }

    if (empty($fouten)) {
        $afbeeldingNaam = $post['afbeelding'];

        if ($nieuweAfbeelding) {
            // Nieuwe afbeelding geüpload: oude verwijderen, nieuwe gebruiken
            verwijderAfbeeldingBestand($post['afbeelding'], $uploadDir);
            $afbeeldingNaam = $nieuweAfbeelding;
        } elseif ($verwijderAfbeelding) {
            // Gebruiker wil huidige afbeelding verwijderen zonder nieuwe
            verwijderAfbeeldingBestand($post['afbeelding'], $uploadDir);
            $afbeeldingNaam = null;
        }

        $stmt = $pdo->prepare("UPDATE posts SET titel = ?, tekst = ?, datum = ?, locatie = ?, afbeelding = ? WHERE id = ?");
        $stmt->execute([$titel, $tekst, $datum, $locatie ?: null, $afbeeldingNaam, $id]);

        header('Location: index.php?succes=bijgewerkt');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogpost bewerken — Mijn Weekblog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <h1>📔 Mijn Weekblog</h1>
        <p>Een overzicht van mijn week — in tekst, beeld en plek</p>
    </header>

    <div class="container">
        <a href="index.php" class="terug-link">&larr; Terug naar overzicht</a>
        <h2>Blogpost bewerken</h2>

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
            <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">

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
                <label>Huidige afbeelding</label>
                <?php if (!empty($post['afbeelding'])): ?>
                    <div class="huidige-afbeelding">
                        <img src="uploads/<?= h($post['afbeelding']) ?>" alt="Huidige afbeelding">
                        <label style="font-weight:normal; display:flex; align-items:center; gap:0.4rem;">
                            <input type="checkbox" name="verwijder_afbeelding" value="1">
                            Afbeelding verwijderen
                        </label>
                    </div>
                <?php else: ?>
                    <p class="confirm-note">Geen afbeelding aanwezig.</p>
                <?php endif; ?>
                <label for="afbeelding">Nieuwe afbeelding uploaden (optioneel, vervangt de huidige)</label>
                <input type="file" id="afbeelding" name="afbeelding" accept="image/jpeg,image/png,image/gif,image/webp">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Wijzigingen opslaan</button>
                <a href="index.php" class="btn btn-secondary">Annuleren</a>
            </div>
        </form>
    </div>
</body>
</html>
