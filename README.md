# RetroCam Marketplace - Mini Project Product Manager

Aplikasi Web manajemen produk sederhana untuk Marketplace Kamera Digicam & Retro menggunakan PHP & MySQL Native.

## Panduan Cara Menjalankan

1. **Import Database:**
   - Buka phpMyAdmin (`http://localhost/phpmyadmin`).
   - Buat database baru dengan nama `store_db`.
   - Import file `database/store_db.sql` ke dalam database `store_db`.

2. **Jalankan Project:**
   - Salin seluruh folder project `Praktikum3_NIM_Nama` ke direktori server web Anda (contoh: `htdocs/` di XAMPP).
   - Akses melalui browser pada URL: `http://localhost/Praktikum3_NIM_Nama/`

---

## Jawaban Refleksi Keamanan (Sesuai Checklist Praktikum)

### Di bagian mana aplikasi paling rentan dan kontrol keamanan yang diimplementasikan:
1. **Input Vulnerability (XSS & Injection):**
   - *Risiko:* Pengguna memasukkan script berbahaya (seperti `<b>Promo</b>` atau SQL Injection).
   - *Kontrol Keamanan:* Menggunakan **PDO Prepared Statements** untuk semua operasi query database (INSERT, SELECT, UPDATE, DELETE, SEARCH) serta fungsi **`htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`** saat render output ke HTML.

2. **Alur Request & Data Duplikasi:**
   - *Risiko:* User me-refresh halaman setelah submit form yang mengakibatkan data ganda.
   - *Kontrol Keamanan:* Menerapkan **PRG Pattern (Post-Redirect-Get)** setelah melakukan operasi `CREATE`, `UPDATE`, dan `DELETE`.

3. **CSRF (Cross-Site Request Forgery):**
   - *Risiko:* Eksekusi penghapusan data secara tidak sah via metode GET.
   - *Kontrol Keamanan:* Proses delete wajib menggunakan metode **POST** disertai validasi **CSRF Token** berbasis Session.
