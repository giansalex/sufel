<?php
$ruc = '20000000001';
$password = '123456';

$password = password_hash($password, PASSWORD_BCRYPT);

$pdo = new PDO('mysql:host=127.0.0.1;dbname=sufel_dev', 'root', '');
$success = $pdo->exec("INSERT INTO company(ruc, nombre, password, enable) VALUES('$ruc', 'EMPRESA SAC', '$password', 1)");

if ($pdo->errorCode() != '0000') {
    var_dump($pdo->errorInfo());
    exit(-1);
}
