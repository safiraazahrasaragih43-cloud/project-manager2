<?php
require 'db.php';

$errors = [];
$name = $category = $price = $stock = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die("Akses ditolak: Token CSRF tidak valid.");
    }

    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $stock = trim($_POST['stock'] ?? '');

    if (strlen($name) < 3) {
        $errors[] = "Nama produk minimal harus 3 karakter!";
    }

    if (!is_numeric($price) || (int)$price <= 0) {
        $errors[] = "Harga harus angka dan lebih besar dari 0!";
    }

    if (!is_numeric($stock) || (int)$stock < 0) {
        $errors[] = "Stok tidak boleh negatif!";
    }

    if (empty($category)) {
        $errors[] = "Kategori tidak boleh kosong!";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, category, price, stock) VALUES (:name, :category, :price, :stock)");
            $stmt->execute([
                ':name' => $name,
                ':category' => $category,
                ':price' => (int)$price,
                ':stock' => (int)$stock
            ]);

            header("Location: index.php?status=created");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "Nama produk sudah terdaftar, gunakan nama unik lainnya!";
            } else {
                $errors[] = "Gagal menyimpan data: " . $e->getMessage();
            }
        }
    }
}

$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kamera - RetroCam</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Tambah Kamera Baru</h2><br>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul style="padding-left: 20px;">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="create.php">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                <div class="form-group">
                    <label>Nama Kamera (min. 3 karakter):</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="form-group">
                    <label>Kategori:</label>
                    <select name="category" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Digicam" <?= $category === 'Digicam' ? 'selected' : '' ?>>Digicam</option>
                        <option value="Analog" <?= $category === 'Analog' ? 'selected' : '' ?>>Analog / Film</option>
                        <option value="Lensa" <?= $category === 'Lensa' ? 'selected' : '' ?>>Lensa & Aksesoris</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Harga (Rp):</label>
                    <input type="number" name="price" value="<?= htmlspecialchars($price, ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="form-group">
                    <label>Stok:</label>
                    <input type="number" name="stock" value="<?= htmlspecialchars($stock, ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <button type="submit" class="btn btn-success" style="width: 100%;">Simpan Produk</button>
                <a href="index.php" class="btn btn-primary" style="width: 100%; text-align: center; margin-top: 10px;">Kembali</a>
            </form>
        </div>
    </div>
</body>
</html>
