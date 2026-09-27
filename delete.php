<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $token = $_POST['csrf_token'] ?? '';

    if (!verify_csrf_token($token)) {
        die("Akses ditolak: Token CSRF tidak valid.");
    }

    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    header("Location: index.php?status=deleted");
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>
