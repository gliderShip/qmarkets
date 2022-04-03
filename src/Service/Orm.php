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

    public const CRITERIA_TYPE_OR = 'OR';
    public const CRITERIA_TYPE_AND = 'AND';

    public const CRITERIA_TYPES = [
        self::CRITERIA_TYPE_OR,
        self::CRITERIA_TYPE_AND,
    ];

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

        $sql = "INSERT INTO '$tableName' ($columnsStr) VALUES ($parametersStr)";
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

    public function update(EntityInterface $entity, string $idColumn, $idValue): EntityInterface
    {
        $reflection = new \ReflectionClass($entity);

        $tableName = $this->getTableName($entity);
        $data = $this->getData($reflection, $entity);

        $columns = array_column($data, 'column');
        $values = array_column($data, 'value');

        $updatedValues = '';

        foreach ($columns as $key => $column) {
            if($key !== 0) {
                $updatedValues .= ", ";
            }
            $updatedValues .= "$column = :$column";
        }

        $sql = "UPDATE '$tableName' SET $updatedValues WHERE $idColumn = :$idColumn";
        $stmt = $this->pdo->prepare($sql);
        foreach ($columns as $key => $column) {
            $stmt->bindValue(":$column", $values[$key]);
        }
        $stmt->bindValue(":$idColumn", $idValue);

        $result = $stmt->execute();
        if ($result === false) {
            throw new \Exception('Error saving object ->' . print_r($this->pdo->errorInfo(), true));
        }

        return $entity;
    }

    public function selectAll(string $entityClass, int $limit = 0): array
    {
        $tableName = $this->getTableName($entityClass);

        $sql = "SELECT * FROM '$tableName'";
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

    public function findOneBy(string $entityClass, string $column, $value): ?EntityInterface
    {
        $tableName = $this->getTableName($entityClass);

        $sql = "SELECT * FROM '$tableName' WHERE $column = :value LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':value', $value);
        $result = $stmt->execute();

        if ($result === false) {
            throw new \Exception('Error fetching data ->' . print_r($this->pdo->errorInfo(), true));
        }

        $entity = $stmt->fetchObject($entityClass);

        return $entity === false ? null : $entity;
    }

    public function findAllBy(string $entityClass, array $criteria, string $criteriaType = self::CRITERIA_TYPE_AND): array
    {
        $tableName = $this->getTableName($entityClass);

        if (!in_array($criteriaType, self::CRITERIA_TYPES)) {
            throw new \Exception("Criteria type ->:$criteriaType not allowed");
        }

        $condition = '';
        foreach ($criteria as $key => $value) {
            if (!empty($condition)) {
                $condition .= " $criteriaType ";
            }
            $condition .= "$key = :$key";
        }

        $sql = "SELECT * FROM '$tableName' WHERE $condition";

        $stmt = $this->pdo->prepare($sql);
        foreach ($criteria as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $result = $stmt->execute();

        if ($result === false) {
            throw new \Exception('Error fetching data ->' . print_r($this->pdo->errorInfo(), true));
        }

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results === false ? [] : $results;
    }


    public function delete(string $entityClass, string $column, $value): int
    {
        $tableName = $this->getTableName($entityClass);

        $sql = "DELETE FROM '$tableName' WHERE $column = :value";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':value', $value);
        $result = $stmt->execute();

        if ($result === false) {
            throw new \Exception('Error deleting data ->' . print_r($this->pdo->errorInfo(), true));
        }

        return $stmt->rowCount();
    }

    public function startTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollback(): bool
    {
        return $this->pdo->rollBack();
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
}
