<?php
namespace App\Controllers;
use Core\Controller;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Models\Vehicle;
class ProviderServiceOrderController extends Controller{
protected $serviceOrderModel;protected $userModel;protected $vehicleModel;
public function __construct(){$this->serviceOrderModel=new ServiceOrder();$this->userModel=new User();$this->vehicleModel=new Vehicle();}
public function index(){
$this->requireAuth();$this->requireRole('fornecedor');
$user=$this->getAuthUser();$providerId=$user['fornecedor_id']??null;
$orders=$this->serviceOrderModel->findByProviderId($providerId);
$this->view('fornecedor/os/index',['user'=>$user,'orders'=>$orders]);}
public function create(){
$this->requireAuth();$this->requireRole('fornecedor');
$user=$this->getAuthUser();$providerId=$user['fornecedor_id']??null;
$clients=$this->userModel->findClientsByProviderId($providerId);
$vehicleId=$_GET['veiculo']??null;
$selectedVehicle=null;
if($vehicleId){$selectedVehicle=$this->vehicleModel->findById($vehicleId);}
$this->view('fornecedor/os/create',['user'=>$user,'clients'=>$clients,'selectedVehicle'=>$selectedVehicle]);}
public function store(){
$this->requireAuth();$this->requireRole('fornecedor');
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);return;}
if(!$this->validateCsrf()){$this->json(['sucesso'=>false,'mensagem'=>'Token CSRF inválido'],403);}
$user=$this->getAuthUser();$providerId=$user['fornecedor_id']??null;
$clienteId=intval($_POST['cliente_id']??0);
$veiculoId=intval($_POST['veiculo_id']??0);
$descricao=$this->sanitize($_POST['descricao']??'');
$valorMaoObra=floatval($_POST['valor_mao_obra']??0);
$valorPecas=floatval($_POST['valor_pecas']??0);
$status=$this->sanitize($_POST['status']??'pendente');
if(!$clienteId||!$veiculoId||empty($descricao)){$this->json(['sucesso'=>false,'mensagem'=>'Todos os campos obrigatórios devem ser preenchidos'],422);}
$isAuthorized=$this->vehicleModel->isClientAuthorizedForProvider($clienteId,$providerId);
if(!$isAuthorized){$this->json(['sucesso'=>false,'mensagem'=>'Cliente não autorizado'],403);}
$valorTotal=$valorMaoObra+$valorPecas;
try{
$osId=$this->serviceOrderModel->create([
'fornecedor_id'=>$providerId,
'cliente_id'=>$clienteId,
'veiculo_id'=>$veiculoId,
'descricao'=>$descricao,
'valor_mao_obra'=>$valorMaoObra,
'valor_pecas'=>$valorPecas,
'valor_total'=>$valorTotal,
'status'=>$status,
'data_abertura'=>date('Y-m-d H:i:s')
]);
$this->json(['sucesso'=>true,'mensagem'=>'O.S criada com sucesso','os_id'=>$osId]);}
catch(\Exception $e){error_log("Erro ao criar O.S: ".$e->getMessage());
$this->json(['sucesso'=>false,'mensagem'=>'Erro ao criar O.S'],500);}}
public function getVehiclesByClient($clientId){
$this->requireAuth();$this->requireRole('fornecedor');
$vehicles=$this->vehicleModel->findByUserId($clientId);
$this->json(['sucesso'=>true,'veiculos'=>$vehicles]);}
}

public function show($id){
$this->requireAuth();$this->requireRole('fornecedor');
$user=$this->getAuthUser();$providerId=$user['fornecedor_id']??null;
$os=$this->serviceOrderModel->findById($id);
if(!$os||$os['fornecedor_id']!=$providerId){$_SESSION['error']='O.S não encontrada';header('Location: /fornecedor/os');exit;}
$vehicle=$this->vehicleModel->findById($os['veiculo_id']);
$client=$this->userModel->findById($os['cliente_id']);
$this->view('fornecedor/os/show',['user'=>$user,'os'=>$os,'vehicle'=>$vehicle,'client'=>$client]);}
public function complete($id){
$this->requireAuth();$this->requireRole('fornecedor');
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);return;}
if(!$this->validateCsrf()){$this->json(['sucesso'=>false,'mensagem'=>'Token CSRF inválido'],403);}
$user=$this->getAuthUser();$providerId=$user['fornecedor_id']??null;
$os=$this->serviceOrderModel->findById($id);
if(!$os||$os['fornecedor_id']!=$providerId){$this->json(['sucesso'=>false,'mensagem'=>'O.S não encontrada'],404);}
$observacoes=$this->sanitize($_POST['observacoes']??'');
$certificadoCodigo='APPAUTO-'.strtoupper(uniqid());
try{
$this->serviceOrderModel->update($id,[
'status'=>'concluida',
'observacoes_finais'=>$observacoes,
'certificado_codigo'=>$certificadoCodigo,
'data_conclusao'=>date('Y-m-d H:i:s')
]);
$this->json(['sucesso'=>true,'mensagem'=>'O.S finalizada com sucesso','certificado'=>$certificadoCodigo]);}
catch(\Exception $e){error_log("Erro ao finalizar O.S: ".$e->getMessage());
$this->json(['sucesso'=>false,'mensagem'=>'Erro ao finalizar O.S'],500);}}
}
