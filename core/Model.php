<?php

/**
 * ============================================================================
 * APP AUTO - Base Model
 * ============================================================================
 * 
 * Classe base para todos os modelos
 * 
 * @author APP AUTO Team
 * @version 1.0.0
 */

namespace Core;

use Core\Database;

class Model
{
    /**
     * Nome da tabela
     * @var string
     */
    protected $table;

    /**
     * Chave primária
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Banco de dados
     * @var Database
     */
    protected $db;

    /**
     * Construtor
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Obter todos os registros
     * 
     * @return array
     */
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->fetchAll($sql);
    }

    /**
     * Obter por ID
     * 
     * @param int $id ID
     * @return array|null
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->fetchOne($sql, [$id]);
    }

    /**
     * Obter por condição
     * 
     * @param array $where Condição
     * @return array|null
     */
    public function findBy($where)
    {
        $whereClause = implode(' AND ', array_map(function ($key) {
            return "{$key} = ?";
        }, array_keys($where)));

        $sql = "SELECT * FROM {$this->table} WHERE {$whereClause}";
        return $this->db->fetchOne($sql, array_values($where));
    }

    /**
     * Obter vários por condição
     * 
     * @param array $where Condição
     * @return array
     */
    public function where($where)
    {
        $whereClause = implode(' AND ', array_map(function ($key) {
            return "{$key} = ?";
        }, array_keys($where)));

        $sql = "SELECT * FROM {$this->table} WHERE {$whereClause}";
        return $this->db->fetchAll($sql, array_values($where));
    }

    /**
     * Criar registro
     * 
     * @param array $data Dados
     * @return int ID
     */
    public function create($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Atualizar registro
     * 
     * @param int $id ID
     * @param array $data Dados
     * @return int Linhas afetadas
     */
    public function update($id, $data)
    {
        return $this->db->update($this->table, $data, [$this->primaryKey => $id]);
    }

    /**
     * Deletar registro
     * 
     * @param int $id ID
     * @return int Linhas afetadas
     */
    public function delete($id)
    {
        return $this->db->delete($this->table, [$this->primaryKey => $id]);
    }

    /**
     * Contar registros
     * 
     * @param array $where Condição
     * @return int
     */
    public function count($where = [])
    {
        if (empty($where)) {
            $sql = "SELECT COUNT(*) as count FROM {$this->table}";
            $result = $this->db->fetchOne($sql);
        } else {
            $whereClause = implode(' AND ', array_map(function ($key) {
                return "{$key} = ?";
            }, array_keys($where)));

            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE {$whereClause}";
            $result = $this->db->fetchOne($sql, array_values($where));
        }

        return $result['count'] ?? 0;
    }

    /**
     * Executar query customizada
     * 
     * @param string $sql SQL
     * @param array $params Parâmetros
     * @return array
     */
    public function query($sql, $params = [])
    {
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Executar query que retorna um registro
     * 
     * @param string $sql SQL
     * @param array $params Parâmetros
     * @return array|null
     */
    public function queryOne($sql, $params = [])
    {
        return $this->db->fetchOne($sql, $params);
    }

    /**
     * Executar query sem retorno
     * 
     * @param string $sql SQL
     * @param array $params Parâmetros
     * @return int Linhas afetadas
     */
    public function execute($sql, $params = [])
    {
        $stmt = $this->db->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Obter último erro
     * 
     * @return string|null
     */
    public function getLastError()
    {
        return $this->lastError ?? null;
    }
}
