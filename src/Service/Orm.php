<?php

namespace App\Service;

use App\Config\DbConfig;
use App\Model\EntityInterface;
use PDO;
use ReflectionClass;
use ReflectionProperty;

class Orm
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(DbConfig::DATABASE_DSN);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    public function insert(EntityInterface $object): EntityInterface
    {
        $reflection = new \ReflectionClass($object);
        $tableName = $reflection->getShortName();

        $data = $this->getData($reflection, $object);

        $this->createTableIfNotExist($tableName, $data);

        $columnsStr = implode(', ', array_column($data, 'column'));
        $valuesStr = implode(', ', array_column($data, 'value'));

        $sql = "INSERT OR REPLACE INTO $tableName ($columnsStr) VALUES ($valuesStr)";
        var_dump($sql);
//        $stmt = $this->pdo->prepare($sql);
        $result = $this->pdo->exec($sql);
        if ($result === false) {
            throw new \Exception('Error saving object ->' . print_r($this->pdo->errorInfo(), true));
        }


        $object->setId($this->pdo->lastInsertId());
        return $object;
    }

    private function getData(ReflectionClass $reflection, EntityInterface $object): array
    {
        $properties = $reflection->getProperties();
        $data = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $data[] = [
                'column' => $property->getName(),
                'value' => $property->getValue($object),
                'type' => $this->getDbType($object, $property)
            ];
            $property->setAccessible(false);
        }

        return $data;
    }

    private function createTableIfNotExist(string $tableName, array $data)
    {
        $columns = '';
        foreach ($data as $key => $property) {
            if ($key > 0) {
                $columns .= ', ';
            }
            $columns .= $property['column'] . ' ' . $property['type'];
        }

        $sql = "CREATE TABLE IF NOT EXISTS $tableName ($columns)";;
        var_dump($sql);
        var_dump('ffffffffffff');
        $result = $this->pdo->exec($sql);
        if ($result === false) {
            throw new \Exception('Error saving object ->' . print_r($this->pdo->errorInfo(), true));
        }
    }

    private function getDbType(EntityInterface $object, ReflectionProperty $property): string
    {
        $type = $property->getType()->getName();
        switch ($type) {
            case 'int':
                return 'INTEGER';
            case 'string':
                return 'TEXT';
            case 'bool':
                return 'INTEGER';
            default:
                throw new \Exception('Unknown type: ' . $type);
        }
    }

}
