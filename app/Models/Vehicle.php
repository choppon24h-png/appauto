<?php

/**
 * ============================================================================
 * APP AUTO - Model: Vehicle
 * ============================================================================
 */

namespace App\Models;

use Core\Model;

class Vehicle extends Model
{
    protected $table = 'veiculos';
    protected $primaryKey = 'id';

    /**
     * Obter veículos do usuário
     * 
     * @param int $userId ID do usuário
     * @return array
     */
    public function getByUser($userId)
    {
        return $this->where(['usuario_id' => $userId]);
    }

    /**
     * Obter veículo por placa
     * 
     * @param string $placa Placa
     * @return array|null
     */
    public function findByPlate($placa)
    {
        return $this->findBy(['placa' => $placa]);
    }

    /**
     * Obter veículo por placa e token
     * 
     * @param string $placa Placa
     * @param string $token Token
     * @return array|null
     */
    public function findByPlateAndToken($placa, $token)
    {
        $sql = "SELECT v.* FROM {$this->table} v 
                INNER JOIN autenticacao_fornecedor af ON v.usuario_id = af.cliente_id 
                WHERE v.placa = ? AND af.token = ? AND af.status = 'aprovado'";
        
        return $this->queryOne($sql, [$placa, $token]);
    }

    /**
     * Obter veículos ativos
     * 
     * @param int $userId ID do usuário
     * @return array
     */
    public function getActive($userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE usuario_id = ? AND status = 'ativo'";
        return $this->query($sql, [$userId]);
    }

    /**
     * Contar veículos do usuário
     * 
     * @param int $userId ID do usuário
     * @return int
     */
    public function countByUser($userId)
    {
        return $this->count(['usuario_id' => $userId]);
    }
}
