<?php

/**
 * ============================================================================
 * APP AUTO - Controller: AuthController
 * ============================================================================
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Provider;

class AuthController extends Controller
{
    protected $userModel;
    protected $providerModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->providerModel = new Provider();
    }

    /**
     * Mostrar página de login
     */
    public function showLogin()
    {
        if ($this->isAuthenticated()) {
            $user = $this->getAuthUser();
            if ($user['role'] === 'cliente') {
                $this->redirect('/cliente/dashboard');
            } elseif ($user['role'] === 'fornecedor') {
                $this->redirect('/fornecedor/dashboard');
            } elseif ($user['role'] === 'admin') {
                $this->redirect('/admin/dashboard');
            }
        }

        $this->view('auth/login');
    }

    /**
     * Fazer login
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        // Validar CSRF
        if (!$this->validateCsrf()) {
            $this->json(['sucesso' => false, 'mensagem' => 'Token CSRF inválido'], 403);
        }

        // Validar entrada
        $email = $this->sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $errors = $this->validate(
            ['email' => $email, 'password' => $password],
            ['email' => 'required|email', 'password' => 'required|min:6']
        );

        if (!empty($errors)) {
            $this->json(['sucesso' => false, 'erros' => $errors], 422);
        }

        // Buscar usuário
        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['senha'])) {
            $this->json(['sucesso' => false, 'mensagem' => 'Email ou senha inválidos'], 401);
        }

        if ($user['status'] !== 'ativo') {
            $this->json(['sucesso' => false, 'mensagem' => 'Usuário inativo'], 403);
        }

        // Criar sessão
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nome' => $user['nome'],
            'email' => $user['email'],
            'role' => $user['role'],
            'cpf_cnpj' => $user['cpf_cnpj'],
        ];

        // Redirecionar conforme role
        $redirect = '/';
        if ($user['role'] === 'cliente') {
            $redirect = '/cliente/dashboard';
        } elseif ($user['role'] === 'fornecedor') {
            $redirect = '/fornecedor/dashboard';
        } elseif ($user['role'] === 'admin') {
            $redirect = '/admin/dashboard';
        }

        $this->json(['sucesso' => true, 'mensagem' => 'Login realizado com sucesso', 'redirect' => $redirect]);
    }

    /**
     * Mostrar página de registro
     */
    public function showRegister()
    {
        if ($this->isAuthenticated()) {
            $this->redirect('/');
        }

        $config = require CONFIG_PATH . '/app.php';
        $segments = $config['segments'];

        $this->view('auth/register', ['segments' => $segments]);
    }

    /**
     * Registrar novo usuário
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        // Validar CSRF
        if (!$this->validateCsrf()) {
            $this->json(['sucesso' => false, 'mensagem' => 'Token CSRF inválido'], 403);
        }

        // Sanitizar entrada
        $nome = $this->sanitize($_POST['nome'] ?? '');
        $email = $this->sanitize($_POST['email'] ?? '');
        $cpf_cnpj = $this->sanitize($_POST['cpf_cnpj'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $role = $this->sanitize($_POST['role'] ?? '');

        // Validar entrada
        $errors = $this->validate(
            [
                'nome' => $nome,
                'email' => $email,
                'cpf_cnpj' => $cpf_cnpj,
                'password' => $password,
                'password_confirm' => $password_confirm,
                'role' => $role
            ],
            [
                'nome' => 'required|min:3|max:100',
                'email' => 'required|email',
                'cpf_cnpj' => 'required|min:11',
                'password' => 'required|min:6',
                'password_confirm' => 'required',
                'role' => 'required'
            ]
        );

        if (!empty($errors)) {
            $this->json(['sucesso' => false, 'erros' => $errors], 422);
        }

        // Validar senhas
        if ($password !== $password_confirm) {
            $this->json(['sucesso' => false, 'mensagem' => 'Senhas não conferem'], 422);
        }

        // Validar role
        if (!in_array($role, ['cliente', 'fornecedor'])) {
            $this->json(['sucesso' => false, 'mensagem' => 'Tipo de usuário inválido'], 422);
        }

        // Verificar se email já existe
        if ($this->userModel->findByEmail($email)) {
            $this->json(['sucesso' => false, 'mensagem' => 'Email já cadastrado'], 409);
        }

        // Verificar se CPF/CNPJ já existe
        if ($this->userModel->findByCpfCnpj($cpf_cnpj)) {
            $this->json(['sucesso' => false, 'mensagem' => 'CPF/CNPJ já cadastrado'], 409);
        }

        // Criar usuário
        try {
            $userId = $this->userModel->create([
                'nome' => $nome,
                'email' => $email,
                'cpf_cnpj' => $cpf_cnpj,
                'senha' => $this->userModel->hashPassword($password),
                'role' => $role,
                'status' => 'ativo',
                'data_criacao' => date('Y-m-d H:i:s'),
            ]);

            // Se for fornecedor, criar registro de fornecedor
            if ($role === 'fornecedor') {
                $segment = $this->sanitize($_POST['segmento'] ?? 'outro');
                $this->providerModel->create([
                    'usuario_id' => $userId,
                    'segmento' => $segment,
                    'status' => 'pendente',
                    'data_criacao' => date('Y-m-d H:i:s'),
                ]);
            }

            $this->json(['sucesso' => true, 'mensagem' => 'Usuário registrado com sucesso', 'redirect' => '/login']);

        } catch (\Exception $e) {
            error_log("Erro ao registrar usuário: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao registrar usuário'], 500);
        }
    }

    /**
     * Fazer logout
     */
    public function logout()
    {
        session_destroy();
        $this->redirect('/login');
    }

    /**
     * Mostrar página de recuperar senha
     */
    public function showForgotPassword()
    {
        $this->view('auth/forgot-password');
    }

    /**
     * Processar recuperar senha
     */
    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $email = $this->sanitize($_POST['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['sucesso' => false, 'mensagem' => 'Email inválido'], 422);
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            // Não revelar se email existe
            $this->json(['sucesso' => true, 'mensagem' => 'Se o email existe, você receberá um link para recuperar a senha']);
        }

        // TODO: Implementar envio de email com link de recuperação

        $this->json(['sucesso' => true, 'mensagem' => 'Link de recuperação enviado para seu email']);
    }
}
