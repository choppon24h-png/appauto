<?php

/**
 * ============================================================================
 * APP AUTO - Controller: ClientVehicleController
 * ============================================================================
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Vehicle;

class ClientVehicleController extends Controller
{
    protected $vehicleModel;

    public function __construct()
    {
        $this->vehicleModel = new Vehicle();
    }

    /**
     * Listar veículos do cliente
     */
    public function index()
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar veículos do usuário
        $vehicles = $this->vehicleModel->findByUserId($userId);

        $this->view('cliente/veiculos/index', [
            'user' => $user,
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Mostrar formulário de novo veículo
     */
    public function create()
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();

        // Gerar CSRF token
        $this->generateCsrfToken();

        $this->view('cliente/veiculos/create', [
            'user' => $user,
        ]);
    }

    /**
     * Salvar novo veículo
     */
    public function store()
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        // Validar CSRF
        if (!$this->validateCsrf()) {
            $this->json(['sucesso' => false, 'mensagem' => 'Token CSRF inválido'], 403);
        }

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Sanitizar entrada
        $marca = $this->sanitize($_POST['marca'] ?? '');
        $modelo = $this->sanitize($_POST['modelo'] ?? '');
        $ano = $this->sanitize($_POST['ano'] ?? '');
        $placa = $this->sanitize($_POST['placa'] ?? '');
        $cor = $this->sanitize($_POST['cor'] ?? '');
        $combustivel = $this->sanitize($_POST['combustivel'] ?? '');
        $km_atual = $this->sanitize($_POST['km_atual'] ?? 0);
        $renavam = $this->sanitize($_POST['renavam'] ?? '');
        $chassi = $this->sanitize($_POST['chassi'] ?? '');

        // Validar entrada
        $errors = $this->validate(
            [
                'marca' => $marca,
                'modelo' => $modelo,
                'ano' => $ano,
                'placa' => $placa,
            ],
            [
                'marca' => 'required|min:2',
                'modelo' => 'required|min:2',
                'ano' => 'required',
                'placa' => 'required|min:7',
            ]
        );

        if (!empty($errors)) {
            $this->json(['sucesso' => false, 'erros' => $errors], 422);
        }

        // Validar placa única
        $existingVehicle = $this->vehicleModel->findByPlaca($placa);
        if ($existingVehicle) {
            $this->json(['sucesso' => false, 'mensagem' => 'Placa já cadastrada'], 409);
        }

        // Criar veículo
        try {
            $vehicleId = $this->vehicleModel->create([
                'usuario_id' => $userId,
                'marca' => $marca,
                'modelo' => $modelo,
                'ano' => $ano,
                'placa' => strtoupper($placa),
                'cor' => $cor,
                'combustivel' => $combustivel,
                'km_atual' => $km_atual,
                'renavam' => $renavam,
                'chassi' => $chassi,
                'status' => 'ativo',
                'data_criacao' => date('Y-m-d H:i:s'),
            ]);

            $this->json([
                'sucesso' => true,
                'mensagem' => 'Veículo cadastrado com sucesso',
                'redirect' => '/cliente/veiculos',
                'vehicle_id' => $vehicleId,
            ]);

        } catch (\Exception $e) {
            error_log("Erro ao cadastrar veículo: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar veículo'], 500);
        }
    }

    /**
     * Mostrar detalhes do veículo
     */
    public function show($id)
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar veículo
        $vehicle = $this->vehicleModel->findById($id);

        if (!$vehicle || $vehicle['usuario_id'] != $userId) {
            http_response_code(404);
            echo "Veículo não encontrado";
            return;
        }

        $this->view('cliente/veiculos/show', [
            'user' => $user,
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit($id)
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar veículo
        $vehicle = $this->vehicleModel->findById($id);

        if (!$vehicle || $vehicle['usuario_id'] != $userId) {
            http_response_code(404);
            echo "Veículo não encontrado";
            return;
        }

        // Gerar CSRF token
        $this->generateCsrfToken();

        $this->view('cliente/veiculos/edit', [
            'user' => $user,
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * Atualizar veículo
     */
    public function update($id)
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        // Validar CSRF
        if (!$this->validateCsrf()) {
            $this->json(['sucesso' => false, 'mensagem' => 'Token CSRF inválido'], 403);
        }

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar veículo
        $vehicle = $this->vehicleModel->findById($id);

        if (!$vehicle || $vehicle['usuario_id'] != $userId) {
            $this->json(['sucesso' => false, 'mensagem' => 'Veículo não encontrado'], 404);
        }

        // Sanitizar entrada
        $marca = $this->sanitize($_POST['marca'] ?? '');
        $modelo = $this->sanitize($_POST['modelo'] ?? '');
        $ano = $this->sanitize($_POST['ano'] ?? '');
        $placa = $this->sanitize($_POST['placa'] ?? '');
        $cor = $this->sanitize($_POST['cor'] ?? '');
        $combustivel = $this->sanitize($_POST['combustivel'] ?? '');
        $km_atual = $this->sanitize($_POST['km_atual'] ?? 0);
        $renavam = $this->sanitize($_POST['renavam'] ?? '');
        $chassi = $this->sanitize($_POST['chassi'] ?? '');
        $status = $this->sanitize($_POST['status'] ?? 'ativo');

        // Validar entrada
        $errors = $this->validate(
            [
                'marca' => $marca,
                'modelo' => $modelo,
                'ano' => $ano,
                'placa' => $placa,
            ],
            [
                'marca' => 'required|min:2',
                'modelo' => 'required|min:2',
                'ano' => 'required',
                'placa' => 'required|min:7',
            ]
        );

        if (!empty($errors)) {
            $this->json(['sucesso' => false, 'erros' => $errors], 422);
        }

        // Validar placa única (exceto o próprio veículo)
        $existingVehicle = $this->vehicleModel->findByPlaca($placa);
        if ($existingVehicle && $existingVehicle['id'] != $id) {
            $this->json(['sucesso' => false, 'mensagem' => 'Placa já cadastrada'], 409);
        }

        // Atualizar veículo
        try {
            $this->vehicleModel->update($id, [
                'marca' => $marca,
                'modelo' => $modelo,
                'ano' => $ano,
                'placa' => strtoupper($placa),
                'cor' => $cor,
                'combustivel' => $combustivel,
                'km_atual' => $km_atual,
                'renavam' => $renavam,
                'chassi' => $chassi,
                'status' => $status,
                'data_atualizacao' => date('Y-m-d H:i:s'),
            ]);

            $this->json([
                'sucesso' => true,
                'mensagem' => 'Veículo atualizado com sucesso',
                'redirect' => '/cliente/veiculos',
            ]);

        } catch (\Exception $e) {
            error_log("Erro ao atualizar veículo: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao atualizar veículo'], 500);
        }
    }

    /**
     * Deletar veículo
     */
    public function destroy($id)
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        // Validar CSRF
        if (!$this->validateCsrf()) {
            $this->json(['sucesso' => false, 'mensagem' => 'Token CSRF inválido'], 403);
        }

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar veículo
        $vehicle = $this->vehicleModel->findById($id);

        if (!$vehicle || $vehicle['usuario_id'] != $userId) {
            $this->json(['sucesso' => false, 'mensagem' => 'Veículo não encontrado'], 404);
        }

        // Deletar veículo
        try {
            $this->vehicleModel->delete($id);

            $this->json([
                'sucesso' => true,
                'mensagem' => 'Veículo excluído com sucesso',
            ]);

        } catch (\Exception $e) {
            error_log("Erro ao deletar veículo: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao excluir veículo'], 500);
        }
    }
}
