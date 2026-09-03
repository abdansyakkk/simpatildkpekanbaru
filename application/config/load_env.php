<?php
defined('BASEPATH') OR $__standalone = TRUE;

/**
 * Env loader sederhana (tanpa dependency composer) untuk memuat
 * kredensial (DB, dsb) dari file .env di root project, supaya
 * kredensial TIDAK ditulis langsung di file config yang ikut ter-commit
 * ke Git.
 *
 * Cara pakai:
 * 1. Copy `.env.example` jadi `.env` di root project (satu folder
 *    dengan index.php).
 * 2. Isi `.env` dengan nilai asli (DB_HOST, DB_USERNAME, dst).
 * 3. Pastikan `.env` masuk ke .gitignore (sudah ditambahkan) supaya
 *    TIDAK pernah ter-commit ke Git.
 * 4. Di server produksi, buat file `.env` langsung di server (upload
 *    manual via SFTP/File Manager), bukan lewat git push.
 */
$envFile = __DIR__ . '/../../.env';

if (is_readable($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        if (strpos($line, '=') === FALSE) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        // Buang tanda kutip pembungkus kalau ada
        if (strlen($value) >= 2 && (
            ($value[0] === '"' && substr($value, -1) === '"') ||
            ($value[0] === "'" && substr($value, -1) === "'")
        )) {
            $value = substr($value, 1, -1);
        }
        if ($name !== '' && getenv($name) === FALSE) {
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }
}
