<?php
    $dbHost = getenv('DB_HOST');
    $dbPort = getenv('DB_PORT');
    $dbName = getenv('DB_NAME');
    $dbUser = getenv('DB_USER');
    $dbPass = getenv('DB_PASS');
    $dsn = "mysql:dbname=$dbName;host=$dbHost:$dbPort";
    $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
    $dbh = new PDO($dsn, $dbUser, $dbPass, $options);
?>