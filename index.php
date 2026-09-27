<?php
require 'db.php';

// Fitur Bonus: Search filter via GET
$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $sql = "SELECT * FROM products WHERE name LIKE :q OR category LIKE :q ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':q' => "%$q%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
}

$products = $stmt->fetchAll();
$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace Kamera Digicam & Retro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>RetroCam Marketplace</h1>
            <p>Manajemen Produk Kamera Digicam & Analog</p>
        </header>

        <!-- Pesan Status (Hasil PRG) -->
        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] === 'created'): ?>
                <div class="alert alert-success">Kamera berhasil ditambahkan!</div>
            <?php elseif ($_GET['status'] === 'updated'): ?>
                <div class="alert alert-success">Data kamera berhasil diperbarui!</div>
            <?php elseif ($_GET['status'] === 'deleted'): ?>
                <div class="alert alert-success">Kamera berhasil dihapus!</div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="top-bar">
            <!-- Form Pencarian GET (Fitur Bonus) -->
            <form method="GET" action="index.php" class="search-form">
                <input type="text" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="Cari nama atau kategori...">
                <button type="submit" class="btn btn-primary">Cari</button>
                <?php if ($q !== ''): ?>
                    <a href="index.php" class="btn btn-danger">Reset</a>
                <?php endif; ?>
            </form>

            <a href="create.php" class="btn btn-success">+ Tambah Kamera</a>
        </div>

        <!-- Layout Flexbox Card Responsif -->
        <div class="card-grid">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $p): ?>
                    <div class="card">
                        <div>
                            <h3><?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <span class="badge"><?= htmlspecialchars($p['category'], ENT_QUOTES, 'UTF-8') ?></span>
                            <div class="price">Rp <?= number_format($p['price'], 0, ',', '.') ?></div>
                            <p>Stok: <strong><?= htmlspecialchars($p['stock'], ENT_QUOTES, 'UTF-8') ?></strong> unit</p>
                        </div>
                        <div class="actions">
                            <a href="update.php?id=<?= $p['id'] ?>" class="btn btn-warning" style="flex: 1; text-align: center;">Edit</a>
                            
                            <form action="delete.php" method="POST" style="flex: 1;" onsubmit="return confirm('Yakin ingin menghapus kamera ini?')">
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button type="submit" class="btn btn-danger" style="width: 100%;">Hapus</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tidak ada produk kamera ditemukan.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
