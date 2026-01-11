<?php

/**
 * ============================================================================
 * APP AUTO - Model: Provider
 * ============================================================================
 */

namespace App\Models;

use Core\Model;

class Provider extends Model
{
    protected $table = 'fornecedores';
    protected $primaryKey = 'id';

    /**
     * Obter fornecedor por usuário
     * 
     * @param int $userId ID do usuário
     * @return array|null
     */
    public function getByUser($userId)
    {
        return $this->findBy(['usuario_id' => $userId]);
    }

    /**
     * Obter fornecedores por segmento
     * 
     * @param string $segment Segmento
     * @return array
     */
    public function getBySegment($segment)
    {
        return $this->where(['segmento' => $segment]);
    }

    /**
     * Obter fornecedores ativos
     * 
     * @return array
     */
    public function getActive()
    {
        $sql = "SELECT f.* FROM {$this->table} f 
                INNER JOIN usuarios u ON f.usuario_id = u.id 
                WHERE u.status = 'ativo'";
        
        return $this->query($sql);
    }

    /**
     * Obter fornecedores por segmento e ativos
     * 
     * @param string $segment Segmento
     * @return array
     */
    public function getActiveBySegment($segment)
    {
        $sql = "SELECT f.* FROM {$this->table} f 
                INNER JOIN usuarios u ON f.usuario_id = u.id 
                WHERE f.segmento = ? AND u.status = 'ativo'";
        
        return $this->query($sql, [$segment]);
    }

    /**
     * Contar fornecedores
     * 
     * @return int
     */
    public function countActive()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} f 
                INNER JOIN usuarios u ON f.usuario_id = u.id 
                WHERE u.status = 'ativo'";
        
        $result = $this->queryOne($sql);
        return $result['count'] ?? 0;
    }
}
