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

    public function getAllMetingen(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query('SELECT * FROM meting');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMetingById(int $id): array | bool
    {
        $sql = 'SELECT * FROM meting WHERE MetingID = :id';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addMeting(array $data): string
    {
        $sql = 'INSERT INTO meting (SensorID, Waarde)
                Values (:Sensor, :Waarden)';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        if (empty($data['SensorID'])) {
            $stmt->bindValue(':Sensor', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':Sensor', $data['SensorID'], PDO::PARAM_STR);
        }

        if (empty($data['Waarde'])) {
            $stmt->bindValue(':Waarden', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':Waarden', $data['Waarde'], PDO::PARAM_STR);
        }

        $stmt->execute();

        return $pdo->lastInsertId();
    }

    public function getFilterdMetingen($Params)
    {
        $pdo = $this->database->getConnection();

        $sql = "SELECT * FROM meting WHERE 1=1";

        if (!empty($Params['startDatum'])) {
            $sql .= " AND Timestamp >= :startDatum";
        }

        if (!empty($Params['eindDatum'])) {
            $sql .= " AND Timestamp <= :eindDatum";
        }

        if (!empty($Params['sensorID'])) {
            $sql .= " AND SensorID = :sensorID";
        }

        // Sortering toevoegen aan SQL
        if (
            !empty($params['sorteerVolgorde']) &&
            in_array(strtolower($params['sorteerVolgorde']), ['asc', 'desc'])
        ) {
            $sql .= " ORDER BY MetingID " .
                strtoupper($params['sorteerVolgorde']);
        }

        if (!empty($Params['aantal']) && is_numeric($Params['aantal'])) {
            $sql .= " LIMIT :aantal";
        }

        $stmt = $pdo->prepare($sql);

        if (!empty($Params['startDatum'])) {
            $stmt->bindValue(':startDatum', $Params['startDatum'], PDO::PARAM_STR);
        }

        if (!empty($Params['eindDatum'])) {
            $stmt->bindValue(':eindDatum', $Params['eindDatum'], PDO::PARAM_STR);
        }

        if (!empty($Params['sensorID'])) {
            $stmt->bindValue(':sensorID', $Params['sensorID'], PDO::PARAM_INT);
        }

        if (!empty($Params['aantal']) && is_numeric($Params['aantal'])) {
            $stmt->bindValue(':aantal', (int)$Params['aantal'], PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
