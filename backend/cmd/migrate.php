<?php

use engine\db\DB;

$main_dir = dirname(__DIR__);
require_once($main_dir . "/vendor/autoload.php");
$config = include($main_dir . '/config.php');
[
    'driver' => $driver,
    'host' => $host,
    'username' => $username,
    'password' => $password,
    'dbname' => $dbname
] = $config;
$pdo = (new DB($driver, $host, $username, $password, $dbname))->connect();

$dir = $main_dir . '/migrations/';
$allFiles = glob($dir . '*.sql');

$query = $pdo->query("SHOW TABLES LIKE 'versions';");
$data = $query->fetchAll();

$versionsFiles = array();
if (count($data)) {
    $data = $pdo->query('SELECT `name` FROM `versions`;');
    foreach ($data as $row) {
        $versionsFiles[] = $dir . $row['name'];
    }
} else {
    $pdo->query("CREATE TABLE versions (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100))");
}

$files = array_diff($allFiles, $versionsFiles);

if (empty($files)) {
    echo 'База в актуальном состоянии' . PHP_EOL;
} else {
    foreach ($files as $file) {
        $command = file_get_contents($file);
        $pdo->exec($command);

        $baseName = basename($file);
        $query = $pdo->prepare('INSERT INTO `versions` (`name`) VALUES(:basename)');
        $query->bindParam(':basename', $baseName);
        $query->execute();
    }
    echo 'Миграции успешно применены' . PHP_EOL;
}
