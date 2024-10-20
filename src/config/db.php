<?php
$config = require __DIR__ . '/../../env.php';

$host = $config['db']['host'];
$dbname = $config['db']['dbname'];
$user = $config['db']['user'];
$password = $config['db']['password'];
$port = $config['db']['port'];

try {
  $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
  $pdo = new PDO($dsn, $user, $password);

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo 'Ошибка подключения к базе данных: ' . $e->getMessage();
}
