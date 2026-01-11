<?php

/**
 * ============================================================================
 * APP AUTO - Rotas Web
 * ============================================================================
 */

$router = new \Core\Router();

// ============================================================================
// ROTAS PÚBLICAS
// ============================================================================

// Login
$router->get('/login', 'AuthController', 'showLogin');
$router->post('/login', 'AuthController', 'login');

// Registro
$router->get('/register', 'AuthController', 'showRegister');
$router->post('/register', 'AuthController', 'register');

// Recuperar Senha
$router->get('/forgot-password', 'AuthController', 'showForgotPassword');
$router->post('/forgot-password', 'AuthController', 'forgotPassword');

// ============================================================================
// ROTAS DE CLIENTE
// ============================================================================

// Dashboard Cliente
$router->get('/cliente/dashboard', 'ClientDashboardController', 'index');
$router->get('/cliente/dashboard/chart-data', 'ClientDashboardController', 'chartData');

// Veículos
$router->get('/cliente/veiculos', 'ClientVehicleController', 'index');
$router->get('/cliente/veiculos/novo', 'ClientVehicleController', 'create');
$router->post('/cliente/veiculos', 'ClientVehicleController', 'store');
$router->get('/cliente/veiculos/{id}/editar', 'ClientVehicleController', 'edit');
$router->put('/cliente/veiculos/{id}', 'ClientVehicleController', 'update');
$router->delete('/cliente/veiculos/{id}', 'ClientVehicleController', 'destroy');

// Carteira (Documentos)
$router->get('/cliente/carteira', 'ClientWalletController', 'index');
$router->post('/cliente/carteira/upload', 'ClientWalletController', 'upload');
$router->get('/cliente/carteira/{id}/download', 'ClientWalletController', 'download');
$router->get('/cliente/carteira/{id}/view', 'ClientWalletController', 'view');
$router->delete('/cliente/carteira/{id}', 'ClientWalletController', 'destroy');

// Manutenção
$router->get('/cliente/manutencao', 'ClientMaintenanceController', 'index');
$router->get('/cliente/manutencao/novo', 'ClientMaintenanceController', 'create');
$router->post('/cliente/manutencao', 'ClientMaintenanceController', 'store');
$router->get('/cliente/manutencao/{id}', 'ClientMaintenanceController', 'show');
$router->get('/cliente/manutencao/{id}/editar', 'ClientMaintenanceController', 'edit');
$router->put('/cliente/manutencao/{id}', 'ClientMaintenanceController', 'update');
$router->delete('/cliente/manutencao/{id}', 'ClientMaintenanceController', 'destroy');

// Autenticação de Fornecedor
$router->get('/cliente/autenticacao', 'ClientAuthenticationController', 'index');
$router->get('/cliente/autenticacao/gerar', 'ClientAuthenticationController', 'create');
$router->post('/cliente/autenticacao/gerar', 'ClientAuthenticationController', 'generate');
$router->post('/cliente/autenticacao/aprovar/{id}', 'ClientAuthenticationController', 'approve');
$router->post('/cliente/autenticacao/negar/{id}', 'ClientAuthenticationController', 'deny');
$router->post('/cliente/autenticacao/revogar/{id}', 'ClientAuthenticationController', 'revoke');
$router->delete('/cliente/autenticacao/{id}', 'ClientAuthenticationController', 'destroy');

// Ordens de Serviço
$router->get('/cliente/os', 'ClientServiceOrderController', 'index');
$router->get('/cliente/os/{id}', 'ClientServiceOrderController', 'show');
$router->post('/cliente/os/{id}/avaliar', 'ClientServiceOrderController', 'rate');

// ============================================================================
// ROTAS DE FORNECEDOR
// ============================================================================

// Dashboard Fornecedor
$router->get('/fornecedor/dashboard', 'ProviderDashboardController', 'index');
$router->get('/fornecedor/dashboard/chart-data', 'ProviderDashboardController', 'chartData');

// Clientes
$router->get('/fornecedor/clientes', 'ProviderClientController', 'index');
$router->get('/fornecedor/clientes/novo', 'ProviderClientController', 'create');
$router->post('/fornecedor/clientes', 'ProviderClientController', 'store');
$router->get('/fornecedor/clientes/{id}', 'ProviderClientController', 'show');
$router->get('/fornecedor/clientes/{id}/editar', 'ProviderClientController', 'edit');
$router->put('/fornecedor/clientes/{id}', 'ProviderClientController', 'update');

// Veículos
$router->get('/fornecedor/veiculos', 'ProviderVehicleController', 'index');
$router->post('/fornecedor/veiculos/buscar', 'ProviderVehicleController', 'search');
$router->get('/fornecedor/veiculos/{id}', 'ProviderVehicleController', 'show');

// Ordens de Serviço
$router->get('/fornecedor/os', 'ProviderServiceOrderController', 'index');
$router->get('/fornecedor/os/criar', 'ProviderServiceOrderController', 'create');
$router->post('/fornecedor/os', 'ProviderServiceOrderController', 'store');
$router->get('/fornecedor/clientes/{id}/veiculos', 'ProviderServiceOrderController', 'getVehiclesByClient');
$router->get('/fornecedor/os/{id}', 'ProviderServiceOrderController', 'show');
$router->post('/fornecedor/os/{id}/finalizar', 'ProviderServiceOrderController', 'complete');
$router->post('/fornecedor/os/{id}/reagendar', 'ProviderServiceOrderController', 'reschedule');
$router->post('/fornecedor/os/{id}/retirada', 'ProviderServiceOrderController', 'pickup');

// ============================================================================
// ROTAS DE ADMIN
// ============================================================================

// Dashboard Admin
$router->get('/admin/dashboard', 'AdminController', 'dashboard');

// Usuários
$router->get('/admin/usuarios', 'AdminUserController', 'index');
$router->get('/admin/usuarios/{id}', 'AdminUserController', 'show');
$router->put('/admin/usuarios/{id}', 'AdminUserController', 'update');
$router->delete('/admin/usuarios/{id}', 'AdminUserController', 'destroy');

// Fornecedores
$router->get('/admin/fornecedores', 'AdminProviderController', 'index');
$router->get('/admin/fornecedores/{id}', 'AdminProviderController', 'show');
$router->put('/admin/fornecedores/{id}', 'AdminProviderController', 'update');
$router->delete('/admin/fornecedores/{id}', 'AdminProviderController', 'destroy');

// ============================================================================
// ROTAS DE LOGOUT E PERFIL
// ============================================================================

$router->post('/logout', 'AuthController', 'logout');
$router->get('/perfil', 'ProfileController', 'show');
$router->put('/perfil', 'ProfileController', 'update');

// ============================================================================
// ROTA PADRÃO
// ============================================================================

$router->get('/', 'HomeController', 'index');

// Despachar requisição
$router->dispatch();
