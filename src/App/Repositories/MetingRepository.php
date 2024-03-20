<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class MetingRepository
{
    public function __construct(private Database $database)
    {
    }

    public function getAllMetingen():array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query('SELECT * FROM meting');
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMetingById(int $id) : array | bool
    {
        $sql = 'SELECT * FROM meting WHERE MetingID = :id';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}