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
        $this->pdo->exec( 'PRAGMA foreign_keys = ON;' );

    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function insert(EntityInterface $entity): EntityInterface
    {
        $reflection = new \ReflectionClass($entity);

        $tableName = $this->getTableName($entity);
        $data = $this->getData($reflection, $entity);

        $columns = array_column($data, 'column');
        $values = array_column($data, 'value');

        $columnsStr = implode(', ', $columns);
        $parametersStr = ":" . implode(", :", $columns);

        $sql = "INSERT INTO $tableName ($columnsStr) VALUES ($parametersStr)";
        $stmt = $this->pdo->prepare($sql);
        foreach ($columns as $key => $column) {
            $stmt->bindValue(":$column", $values[$key]);
        }

        $result = $stmt->execute();
        if ($result === false) {
            throw new \Exception('Error saving object ->' . print_r($this->pdo->errorInfo(), true));
        }

        $entity->setId($this->pdo->lastInsertId());
        return $entity;
    }

    public function selectAll(string $entityClass, int $limit = 0): array
    {
        $tableName = $this->getTableName($entityClass);

        $sql = "SELECT * FROM $tableName";
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }

        $stmt = $this->pdo->prepare($sql);
        $result =  $stmt->execute();

        if ($result === false) {
            throw new \Exception('Error fetching data ->' . print_r($this->pdo->errorInfo(), true));
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getData(ReflectionClass $reflection, EntityInterface $object): array
    {
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);
        $data = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $data[] = [
                'column' => $property->getName(),
                'value' => $property->getValue($object),
            ];
            $property->setAccessible(false);
        }

        return $data;
    }

    private function getTableName($entityOrClassName): string
    {
        $reflection = new \ReflectionClass($entityOrClassName);
        return strtolower($reflection->getShortName());
    }

    public function findOneBy(string $entityClass, string $column, $value): ?EntityInterface
    {
        $tableName = $this->getTableName($entityClass);

        $sql = "SELECT * FROM $tableName WHERE $column = :value";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':value', $value);
        $result = $stmt->execute();

        if ($result === false) {
            throw new \Exception('Error fetching data ->' . print_r($this->pdo->errorInfo(), true));
        }

        $entity = $stmt->fetchObject($entityClass);

        return $entity === false ? null : $entity;
    }
}
