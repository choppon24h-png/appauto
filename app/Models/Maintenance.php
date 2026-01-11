<?php

/**
 * ============================================================================
 * APP AUTO - Model: Maintenance
 * ============================================================================
 */

namespace App\Models;

use Core\Model;

class Maintenance extends Model
{
    protected $table = 'manutencoes';
    protected $primaryKey = 'id';

    /**
     * Obter manutenções do veículo
     * 
     * @param int $vehicleId ID do veículo
     * @return array
     */
    public function getByVehicle($vehicleId)
    {
        return $this->where(['veiculo_id' => $vehicleId]);
    }

    /**
     * Obter manutenções do usuário
     * 
     * @param int $userId ID do usuário
     * @return array
     */
    public function getByUser($userId)
    {
        $sql = "SELECT m.* FROM {$this->table} m 
                INNER JOIN veiculos v ON m.veiculo_id = v.id 
                WHERE v.usuario_id = ? 
                ORDER BY m.data_manutencao DESC";
        
        return $this->query($sql, [$userId]);
    }

    /**
     * Obter manutenções pendentes
     * 
     * @param int $userId ID do usuário
     * @return array
     */
    public function getPending($userId)
    {
        $sql = "SELECT m.* FROM {$this->table} m 
                INNER JOIN veiculos v ON m.veiculo_id = v.id 
                WHERE v.usuario_id = ? AND m.status = 'pendente' 
                ORDER BY m.data_manutencao ASC";
        
        return $this->query($sql, [$userId]);
    }

    /**
     * Obter manutenções por tipo
     * 
     * @param int $vehicleId ID do veículo
     * @param string $type Tipo
     * @return array
     */
    public function getByType($vehicleId, $type)
    {
        return $this->where(['veiculo_id' => $vehicleId, 'tipo' => $type]);
    }

    /**
     * Contar manutenções do usuário
     * 
     * @param int $userId ID do usuário
     * @return int
     */
    public function countByUser($userId)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} m 
                INNER JOIN veiculos v ON m.veiculo_id = v.id 
                WHERE v.usuario_id = ?";
        
        $result = $this->queryOne($sql, [$userId]);
        return $result['count'] ?? 0;
    }
}
