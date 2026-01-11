<?php

/**
 * ============================================================================
 * APP AUTO - Model: Wallet
 * ============================================================================
 */

namespace App\Models;

use Core\Model;

class Wallet extends Model
{
    protected $table = 'carteira';
    protected $primaryKey = 'id';

    /**
     * Obter documentos do veículo
     * 
     * @param int $vehicleId ID do veículo
     * @return array
     */
    public function getByVehicle($vehicleId)
    {
        return $this->where(['veiculo_id' => $vehicleId]);
    }

    /**
     * Obter documentos do usuário
     * 
     * @param int $userId ID do usuário
     * @return array
     */
    public function getByUser($userId)
    {
        $sql = "SELECT c.* FROM {$this->table} c 
                INNER JOIN veiculos v ON c.veiculo_id = v.id 
                WHERE v.usuario_id = ? 
                ORDER BY c.data_upload DESC";
        
        return $this->query($sql, [$userId]);
    }

    /**
     * Obter documentos por tipo
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
     * Contar documentos do veículo
     * 
     * @param int $vehicleId ID do veículo
     * @return int
     */
    public function countByVehicle($vehicleId)
    {
        return $this->count(['veiculo_id' => $vehicleId]);
    }

    /**
     * Contar documentos do usuário
     * 
     * @param int $userId ID do usuário
     * @return int
     */
    public function countByUser($userId)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} c 
                INNER JOIN veiculos v ON c.veiculo_id = v.id 
                WHERE v.usuario_id = ?";
        
        $result = $this->queryOne($sql, [$userId]);
        return $result['count'] ?? 0;
    }
}
