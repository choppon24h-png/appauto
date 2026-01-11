<?php
namespace App\Controllers;
use Core\Controller;
use App\Models\Vehicle;
use App\Models\Maintenance;
use App\Models\Wallet;
class ProviderVehicleController extends Controller{
protected $vehicleModel;protected $maintenanceModel;protected $walletModel;
public function __construct(){$this->vehicleModel=new Vehicle();$this->maintenanceModel=new Maintenance();$this->walletModel=new Wallet();}
public function index(){
$this->requireAuth();$this->requireRole('fornecedor');
$user=$this->getAuthUser();
$this->view('fornecedor/veiculos/index',['user'=>$user]);}
public function search(){
$this->requireAuth();$this->requireRole('fornecedor');
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);return;}
$user=$this->getAuthUser();$providerId=$user['fornecedor_id']??null;
$placa=$this->sanitize($_POST['placa']??'');
if(empty($placa)){$this->json(['sucesso'=>false,'mensagem'=>'Placa é obrigatória'],422);}
$vehicle=$this->vehicleModel->findByPlate($placa);
if(!$vehicle){$this->json(['sucesso'=>false,'mensagem'=>'Veículo não encontrado'],404);}
$isAuthorized=$this->vehicleModel->isClientAuthorizedForProvider($vehicle['usuario_id'],$providerId);
if(!$isAuthorized){$this->json(['sucesso'=>false,'mensagem'=>'Cliente não autorizado'],403);}
$this->json(['sucesso'=>true,'veiculo'=>$vehicle]);}
public function show($id){
$this->requireAuth();$this->requireRole('fornecedor');
$user=$this->getAuthUser();$providerId=$user['fornecedor_id']??null;
$vehicle=$this->vehicleModel->findById($id);
if(!$vehicle){$_SESSION['error']='Veículo não encontrado';header('Location: /fornecedor/veiculos');exit;}
$isAuthorized=$this->vehicleModel->isClientAuthorizedForProvider($vehicle['usuario_id'],$providerId);
if(!$isAuthorized){$_SESSION['error']='Cliente não autorizado';header('Location: /fornecedor/veiculos');exit;}
$maintenances=$this->maintenanceModel->findByVehicleId($id);
$documents=$this->walletModel->findByVehicleId($id);
$this->view('fornecedor/veiculos/show',['user'=>$user,'vehicle'=>$vehicle,'maintenances'=>$maintenances,'documents'=>$documents]);}
}
