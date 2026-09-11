<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$uploadDir = __DIR__ . '/uploads';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);

    $stmt = $pdo->prepare("SELECT afbeelding FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();

    if ($post) {
        verwijderAfbeeldingBestand($post['afbeelding'], $uploadDir);
        $del = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $del->execute([$id]);
    }
}

header('Location: index.php?succes=verwijderd');
exit;
