# Ringkasan Perbaikan Keamanan — simpatildkpekanbaru

Paket ini berisi HANYA file yang diubah (48 file). Folder `vendor/`
dan `system/` tidak disentuh sama sekali. Cara pakai: timpa
(overwrite) file-file ini ke posisi yang sama persis di project
kamu, lalu commit & push seperti biasa.

## 1. Kredensial database tidak lagi hardcoded
- `application/config/database.php` sekarang mengambil kredensial dari
  environment variable (`getenv()`), bukan ditulis langsung di file.
- Tambahan file `.env.example` (template) dan `application/config/load_env.php`
  (loader sederhana, tanpa dependency composer).
- **YANG HARUS KAMU LAKUKAN:** buat file `.env` (copy dari `.env.example`)
  di root project, isi dengan kredensial ASLI (setelah kamu ganti
  password database). File `.env` sudah masuk `.gitignore` — jangan
  pernah commit file ini.

## 2. SQL Injection diperbaiki (32 titik)
Semua raw query yang menyisipkan variabel langsung ke string SQL
(`WHERE id='$id'`) diubah jadi parameterized query
(`WHERE id=?`, `array($id)`), tersebar di:
`Data.php`, `Transaksi.php`, `User.php`, `M_login.php`, dan beberapa
file view yang ternyata juga menjalankan query langsung (`sidebar_view.php`,
`header_view.php`, `buku/detail.php`, `pinjam/kembali.php`, dll).

## 3. Error handler yang membocorkan info dihapus
`index.php` sebelumnya print pesan error + path file + full backtrace
ke browser untuk SEMUA pengunjung setiap ada PHP warning. Sudah dihapus.
Default environment juga diubah fail-safe ke `production` (sebelumnya
default ke `development`, artinya kalau server lupa set `CI_ENV`,
detail error database bisa tampil ke publik).

## 4. CSRF Protection diaktifkan
`csrf_protection` diubah dari `FALSE` ke `TRUE`. Karena SEMUA 29 form
di aplikasi pakai tag `<form>` mentah (bukan `form_open()`), saya
sisipkan hidden CSRF token field ke tiap form secara otomatis, dan ke
2 AJAX POST call yang ada (`pinjam/tambah_view.php`) — supaya tidak
ada form yang gagal submit setelah proteksi ini aktif.

**PENTING:** kalau nanti kamu tambah form atau AJAX POST baru, harus
disertai CSRF token juga, atau pakai helper `form_open()` yang
otomatis menanganinya.

## 5. Encryption key diisi
`encryption_key` yang tadinya kosong sekarang diambil dari
`.env` (`ENCRYPTION_KEY`). Generate nilainya dengan:
```
php -r "echo bin2hex(random_bytes(32));"
```

## 6. Password hashing: MD5 -> bcrypt (dengan migrasi otomatis)
- `Login::auth()` sekarang mendukung dua format: kalau hash lama
  (MD5) cocok, otomatis di-upgrade ke bcrypt saat user itu login —
  **tidak perlu reset password massal**.
- `User::add()` dan `User::upd()` (buat/edit user baru) sekarang
  menyimpan password dengan `password_hash()` (bcrypt), bukan MD5.
- Kolom `pass` di database sudah `varchar(255)`, jadi tidak perlu
  ubah struktur tabel — hash bcrypt (60 karakter) muat.

## 7. Broken Access Control diperbaiki (User.php)
- `add()` (buat user baru): sebelumnya semua orang yang login bisa
  akses, sekarang dibatasi Admin & Panitia saja.
- `upd()` (proses edit user): sebelumnya field `id_login` dan `level`
  dipercaya mentah dari form POST — artinya user biasa bisa
  mengedit akun ORANG LAIN dan menaikkan levelnya sendiri jadi Admin.
  Sekarang: hanya Admin yang boleh mengedit id_login manapun & ubah
  level; user lain otomatis dibatasi ke akun sendiri saja dan level-nya
  tidak bisa diubah dari form.
- `del()` (hapus user): dibatasi Admin saja.
- `index()` (lihat daftar semua user): dibatasi Admin saja, sesuai
  menu sidebar yang memang hanya menampilkan link ini untuk Admin.

---

## Yang BELUM saya sentuh (butuh keputusan/waktu terpisah)
- **Refactor `Data.php`** (6.283 baris jadi satu file) — berisiko
  tinggi kalau dipecah otomatis tanpa testing menyeluruh, sebaiknya
  dilakukan bertahap dengan environment staging.
- **Duplikasi `WordGenerator*.php`** — butuh pemahaman perbedaan
  fungsional ketiga file sebelum digabung.
- **Pembersihan git history** — password lama yang sempat bocor di
  commit awal masih ada di history git meskipun sudah dihapus dari
  file saat ini. Kalau mau benar-benar hilang dari history, perlu
  `git filter-repo` atau BFG Repo-Cleaner (saya bisa pandu terpisah).
- **Cek role-based access control** di controller lain (`Data.php`,
  `Transaksi.php`) belum saya audit selengkap `User.php` — worth
  direview juga, terutama modul yang bisa hapus/edit data penting.
