<?php

declare (stricttypes=1); 

namespace App;

use PDO;
class Database 
{ 
    public function getConnection(): PDO
    {
        $dsn = "mysql:host=127.0.0.1;dbname=slim api;charset=utf8";

        $pdo = new PDO($dsn, 'Lars', 'Welkom01', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        return $pdo;
    }
}