<?php

/**
 * ============================================================================
 * APP AUTO - Database Connection
 * ============================================================================
 * 
 * Classe para gerenciar conexão com banco de dados
 * 
 * @author APP AUTO Team
 * @version 1.0.0
 */

namespace Core;

use PDO;
use PDOException;

class Database
{
    /**
     * Instância da conexão
     * @var PDO|null
     */
    private static $instance = null;

    /**
     * Conexão PDO
     * @var PDO
     */
    private $connection;

    /**
     * Construtor privado (Singleton)
     */
    private function __construct()
    {
        $this->connect();
    }

    /**
     * Conectar ao banco de dados
     * 
     * @return void
     * @throws PDOException
     */
    private function connect()
    {
        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                getenv('DB_HOST'),
                getenv('DB_PORT'),
                getenv('DB_NAME'),
                getenv('DB_CHARSET')
            );

            $this->connection = new PDO(
                $dsn,
                getenv('DB_USER'),
                getenv('DB_PASS'),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            // Definir timezone
            $this->connection->exec("SET time_zone = '-03:00'");

        } catch (PDOException $e) {
            error_log("Erro de conexão com BD: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obter instância (Singleton)
     * 
     * @return Database
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Obter conexão PDO
     * 
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Executar query com prepared statement
     * 
     * @param string $sql SQL
     * @param array $params Parâmetros
     * @return \PDOStatement
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Erro na query: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obter um registro
     * 
     * @param string $sql SQL
     * @param array $params Parâmetros
     * @return array|null
     */
    public function fetchOne($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Obter vários registros
     * 
     * @param string $sql SQL
     * @param array $params Parâmetros
     * @return array
     */
    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Inserir registro
     * 
     * @param string $table Tabela
     * @param array $data Dados
     * @return int ID inserido
     */
    public function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        $this->query($sql, array_values($data));

        return (int)$this->connection->lastInsertId();
    }

    /**
     * Atualizar registros
     * 
     * @param string $table Tabela
     * @param array $data Dados
     * @param array $where Condição WHERE
     * @return int Linhas afetadas
     */
    public function update($table, $data, $where)
    {
        $set = implode(', ', array_map(function ($key) {
            return "{$key} = ?";
        }, array_keys($data)));

        $whereClause = implode(' AND ', array_map(function ($key) {
            return "{$key} = ?";
        }, array_keys($where)));

        $sql = "UPDATE {$table} SET {$set} WHERE {$whereClause}";

        $params = array_merge(array_values($data), array_values($where));

        $stmt = $this->query($sql, $params);

        return $stmt->rowCount();
    }

    /**
     * Deletar registros
     * 
     * @param string $table Tabela
     * @param array $where Condição WHERE
     * @return int Linhas afetadas
     */
    public function delete($table, $where)
    {
        $whereClause = implode(' AND ', array_map(function ($key) {
            return "{$key} = ?";
        }, array_keys($where)));

        $sql = "DELETE FROM {$table} WHERE {$whereClause}";

        $stmt = $this->query($sql, array_values($where));

        return $stmt->rowCount();
    }

    /**
     * Iniciar transação
     * 
     * @return void
     */
    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    /**
     * Confirmar transação
     * 
     * @return void
     */
    public function commit()
    {
        $this->connection->commit();
    }

    /**
     * Reverter transação
     * 
     * @return void
     */
    public function rollback()
    {
        $this->connection->rollBack();
    }

    /**
     * Fechar conexão
     * 
     * @return void
     */
    public function close()
    {
        $this->connection = null;
        self::$instance = null;
    }
}
