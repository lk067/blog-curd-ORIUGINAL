<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$posts = $pdo->query("SELECT * FROM posts ORDER BY datum DESC, id DESC")->fetchAll();

$succes = $_GET['succes'] ?? null;
$berichten = [
    'aangemaakt'   => 'Blogpost is succesvol toegevoegd.',
    'bijgewerkt'   => 'Blogpost is succesvol bijgewerkt.',
    'verwijderd'   => 'Blogpost is verwijderd.',
];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Weekblog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <h1>📔 Mijn Weekblog</h1>
        <p>Een overzicht van mijn week — in tekst, beeld en plek</p>
    </header>

    <div class="container">
        <?php if ($succes && isset($berichten[$succes])): ?>
            <div class="alert alert-success"><?= h($berichten[$succes]) ?></div>
        <?php endif; ?>

        <div class="toolbar">
            <a href="create.php" class="btn">+ Nieuwe blogpost</a>
        </div>

        <?php if (empty($posts)): ?>
            <div class="empty-state">
                <p>Er zijn nog geen blogposts. Maak je eerste post aan!</p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <?php if (!empty($post['afbeelding'])): ?>
                        <img src="uploads/<?= h($post['afbeelding']) ?>" alt="<?= h($post['titel']) ?>">
                    <?php endif; ?>

                    <h2 class="post-title"><?= h($post['titel']) ?></h2>
                    <div class="post-meta">
                        <span class="datum"><?= h(formatDatumNL($post['datum'])) ?></span>
                        <?php if (!empty($post['locatie'])): ?>
                            <span class="locatie"><?= h($post['locatie']) ?></span>
                        <?php endif; ?>
                    </div>
                    <p class="post-text"><?= nl2br(h($post['tekst'])) ?></p>

                    <div class="post-actions">
                        <a href="edit.php?id=<?= (int)$post['id'] ?>" class="btn btn-secondary btn-small">Bewerken</a>
                        <form method="post" action="delete.php" onsubmit="return confirm('Weet je zeker dat je deze blogpost wilt verwijderen?');" style="margin:0;">
                            <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-small">Verwijderen</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
