<?php

/**
 * ============================================================================
 * APP AUTO - Model: ServiceOrder
 * ============================================================================
 */

namespace App\Models;

use Core\Model;

class ServiceOrder extends Model
{
    protected $table = 'ordens_servico';
    protected $primaryKey = 'id';

    /**
     * Obter O.S do fornecedor
     * 
     * @param int $providerId ID do fornecedor
     * @return array
     */
    public function getByProvider($providerId)
    {
        $sql = "SELECT os.*, v.marca, v.modelo, v.placa, u.nome as cliente_nome 
                FROM {$this->table} os 
                INNER JOIN veiculos v ON os.veiculo_id = v.id 
                INNER JOIN usuarios u ON os.cliente_id = u.id 
                WHERE os.fornecedor_id = ? 
                ORDER BY os.data_criacao DESC";
        
        return $this->query($sql, [$providerId]);
    }

    /**
     * Obter O.S do cliente
     * 
     * @param int $clientId ID do cliente
     * @return array
     */
    public function getByClient($clientId)
    {
        $sql = "SELECT os.*, v.marca, v.modelo, v.placa, u.nome as fornecedor_nome 
                FROM {$this->table} os 
                INNER JOIN veiculos v ON os.veiculo_id = v.id 
                INNER JOIN usuarios u ON os.fornecedor_id = u.id 
                WHERE os.cliente_id = ? 
                ORDER BY os.data_criacao DESC";
        
        return $this->query($sql, [$clientId]);
    }

    /**
     * Obter O.S por status
     * 
     * @param int $providerId ID do fornecedor
     * @param string $status Status
     * @return array
     */
    public function getByStatus($providerId, $status)
    {
        $sql = "SELECT os.*, v.marca, v.modelo, v.placa, u.nome as cliente_nome 
                FROM {$this->table} os 
                INNER JOIN veiculos v ON os.veiculo_id = v.id 
                INNER JOIN usuarios u ON os.cliente_id = u.id 
                WHERE os.fornecedor_id = ? AND os.status = ? 
                ORDER BY os.data_criacao DESC";
        
        return $this->query($sql, [$providerId, $status]);
    }

    /**
     * Contar O.S do fornecedor
     * 
     * @param int $providerId ID do fornecedor
     * @return int
     */
    public function countByProvider($providerId)
    {
        return $this->count(['fornecedor_id' => $providerId]);
    }

    /**
     * Contar O.S por status
     * 
     * @param int $providerId ID do fornecedor
     * @param string $status Status
     * @return int
     */
    public function countByStatus($providerId, $status)
    {
        return $this->count(['fornecedor_id' => $providerId, 'status' => $status]);
    }
}
public function findByCertificateCode($code){
$stmt=$this->db->prepare("SELECT * FROM ordens_servico WHERE certificado_codigo=?");
$stmt->execute([$code]);
return $stmt->fetch(\PDO::FETCH_ASSOC)?:null;}
}
