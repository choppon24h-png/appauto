<?php

/**
 * ============================================================================
 * APP AUTO - Controller: ProfileController
 * ============================================================================
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->requireAuth();
    }

    /**
     * Mostrar perfil
     */
    public function show()
    {
        $user = $this->getAuthUser();
        $userData = $this->userModel->find($user['id']);

        $this->view('profile/show', ['user' => $userData]);
    }

    /**
     * Atualizar perfil
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        if (!$this->validateCsrf()) {
            $this->json(['sucesso' => false, 'mensagem' => 'Token CSRF inválido'], 403);
        }

        $user = $this->getAuthUser();

        $nome = $this->sanitize($_POST['nome'] ?? '');
        $email = $this->sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        $errors = $this->validate(
            ['nome' => $nome, 'email' => $email],
            ['nome' => 'required|min:3|max:100', 'email' => 'required|email']
        );

        if (!empty($errors)) {
            $this->json(['sucesso' => false, 'erros' => $errors], 422);
        }

        // Verificar se email já existe (exceto o do usuário)
        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser && $existingUser['id'] !== $user['id']) {
            $this->json(['sucesso' => false, 'mensagem' => 'Email já cadastrado'], 409);
        }

        $data = [
            'nome' => $nome,
            'email' => $email,
        ];

        // Se informou nova senha
        if (!empty($password)) {
            if ($password !== $password_confirm) {
                $this->json(['sucesso' => false, 'mensagem' => 'Senhas não conferem'], 422);
            }

            if (strlen($password) < 6) {
                $this->json(['sucesso' => false, 'mensagem' => 'Senha deve ter no mínimo 6 caracteres'], 422);
            }

            $data['senha'] = $this->userModel->hashPassword($password);
        }

        try {
            $this->userModel->update($user['id'], $data);

            // Atualizar sessão
            $_SESSION['user']['nome'] = $nome;
            $_SESSION['user']['email'] = $email;

            $this->json(['sucesso' => true, 'mensagem' => 'Perfil atualizado com sucesso']);

        } catch (\Exception $e) {
            error_log("Erro ao atualizar perfil: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao atualizar perfil'], 500);
        }
    }
}
