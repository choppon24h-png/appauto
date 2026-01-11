<?php

/**
 * ============================================================================
 * APP AUTO - Model: User
 * ============================================================================
 */

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';

    /**
     * Obter usuário por email
     * 
     * @param string $email Email
     * @return array|null
     */
    public function findByEmail($email)
    {
        return $this->findBy(['email' => $email]);
    }

    /**
     * Obter usuário por CPF/CNPJ
     * 
     * @param string $cpf_cnpj CPF ou CNPJ
     * @return array|null
     */
    public function findByCpfCnpj($cpf_cnpj)
    {
        return $this->findBy(['cpf_cnpj' => $cpf_cnpj]);
    }

    /**
     * Verificar senha
     * 
     * @param string $password Senha
     * @param string $hash Hash
     * @return bool
     */
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Hash de senha
     * 
     * @param string $password Senha
     * @return string
     */
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Obter clientes
     * 
     * @return array
     */
    public function getClients()
    {
        return $this->where(['role' => 'cliente']);
    }

    /**
     * Obter fornecedores
     * 
     * @return array
     */
    public function getProviders()
    {
        return $this->where(['role' => 'fornecedor']);
    }

    /**
     * Obter usuários ativos
     * 
     * @return array
     */
    public function getActive()
    {
        return $this->where(['status' => 'ativo']);
    }
}
