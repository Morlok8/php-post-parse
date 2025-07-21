<?php
// db connection data
$db_host = 'localhost';
$db_name = 'post-parse';
$db_user = 'root';
$db_pass = '';

// db connection
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);