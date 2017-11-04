<?php
$dbFile = 'data.sqlite';
if (file_exists($dbFile)) {
    unlink($dbFile);
}

$database = new SQLite3($dbFile);
$database->close();

$pdo = new PDO('sqlite:data.sqlite',NULL, NULL);
$querys = file_get_contents(__DIR__.'/../src/data/schema.sql');

$pdo->exec($querys);

$password = password_hash('123456', PASSWORD_BCRYPT);

$pdo->exec("INSERT INTO company VALUES ('20000000001', 'COMPANY', '$password', 1)");

$pdo = null;


