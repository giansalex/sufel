<?php
$ruc = '20000000001';
$ruc2 = '20484433359';
$password = '123456';

$password = password_hash($password, PASSWORD_BCRYPT);

$check = function (PDO $obj) {
    if ($obj->errorCode() != '0000') {
        var_dump($obj->errorInfo());
        exit(-1);
    }
};

$pdo = new PDO('mysql:host=127.0.0.1;dbname=sufel_dev', 'root', '');
$pdo->exec("INSERT INTO company(ruc, nombre, password, enable) VALUES('$ruc', 'EMPRESA SAC', '$password', 1)");
$check($pdo);
$pdo->exec("INSERT INTO client(documento, nombres, password) VALUES('$ruc2', 'CLIENTE SAC', '$password')");
$check($pdo);
