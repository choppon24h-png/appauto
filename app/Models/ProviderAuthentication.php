<?php

/**
 * ============================================================================
 * APP AUTO - Model: ProviderAuthentication
 * ============================================================================
 */

namespace App\Models;

use Core\Model;

class ProviderAuthentication extends Model
{
    protected $table = 'autenticacao_fornecedor';
    protected $primaryKey = 'id';

    /**
     * Obter solicitações do cliente
     * 
     * @param int $clientId ID do cliente
     * @return array
     */
    public function getByClient($clientId)
    {
        $sql = "SELECT af.*, u.nome as fornecedor_nome, u.email as fornecedor_email 
                FROM {$this->table} af 
                INNER JOIN usuarios u ON af.fornecedor_id = u.id 
                WHERE af.cliente_id = ? 
                ORDER BY af.data_criacao DESC";
        
        return $this->query($sql, [$clientId]);
    }

    /**
     * Obter solicitações do fornecedor
     * 
     * @param int $providerId ID do fornecedor
     * @return array
     */
    public function getByProvider($providerId)
    {
        $sql = "SELECT af.*, u.nome as cliente_nome, u.email as cliente_email 
                FROM {$this->table} af 
                INNER JOIN usuarios u ON af.cliente_id = u.id 
                WHERE af.fornecedor_id = ? 
                ORDER BY af.data_criacao DESC";
        
        return $this->query($sql, [$providerId]);
    }

    /**
     * Obter solicitações pendentes do cliente
     * 
     * @param int $clientId ID do cliente
     * @return array
     */
    public function getPendingByClient($clientId)
    {
        $sql = "SELECT af.*, u.nome as fornecedor_nome, u.email as fornecedor_email 
                FROM {$this->table} af 
                INNER JOIN usuarios u ON af.fornecedor_id = u.id 
                WHERE af.cliente_id = ? AND af.status = 'pendente' 
                ORDER BY af.data_criacao DESC";
        
        return $this->query($sql, [$clientId]);
    }

    /**
     * Obter por token
     * 
     * @param string $token Token
     * @return array|null
     */
    public function findByToken($token)
    {
        return $this->findBy(['token' => $token]);
    }

    /**
     * Gerar token
     * 
     * @return string
     */
    public function generateToken()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Verificar se já existe autenticação
     * 
     * @param int $clientId ID do cliente
     * @param int $providerId ID do fornecedor
     * @return bool
     */
    public function exists($clientId, $providerId)
    {
        $result = $this->findBy(['cliente_id' => $clientId, 'fornecedor_id' => $providerId]);
        return $result !== null;
    }

    /**
     * Contar solicitações pendentes
     * 
     * @param int $clientId ID do cliente
     * @return int
     */
    public function countPending($clientId)
    {
        return $this->count(['cliente_id' => $clientId, 'status' => 'pendente']);
    }
}
