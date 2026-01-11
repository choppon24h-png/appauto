<?php
namespace App\Controllers;
use Core\Controller;
use App\Models\User;
class ProviderClientController extends Controller{
protected $userModel;
public function __construct(){$this->userModel=new User();}
public function index(){
$this->requireAuth();$this->requireRole('fornecedor');
$user=$this->getAuthUser();$providerId=$user['fornecedor_id']??null;
if(!$providerId){$_SESSION['error']='Fornecedor não encontrado';header('Location: /logout');exit;}
$clients=$this->userModel->findClientsByProviderId($providerId);
$this->view('fornecedor/clientes/index',['user'=>$user,'clients'=>$clients]);}
public function create(){
$this->requireAuth();$this->requireRole('fornecedor');
$user=$this->getAuthUser();
$this->view('fornecedor/clientes/create',['user'=>$user]);}
public function store(){
$this->requireAuth();$this->requireRole('fornecedor');
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);return;}
if(!$this->validateCsrf()){$this->json(['sucesso'=>false,'mensagem'=>'Token CSRF inválido'],403);}
$user=$this->getAuthUser();$providerId=$user['fornecedor_id']??null;
$email=$this->sanitize($_POST['email']??'');
if(!$this->validateEmail($email)){$this->json(['sucesso'=>false,'mensagem'=>'Email inválido'],422);}
$existingUser=$this->userModel->findByEmail($email);
if(!$existingUser){$this->json(['sucesso'=>false,'mensagem'=>'Cliente não encontrado no sistema'],404);}
if($existingUser['tipo']!=='cliente'){$this->json(['sucesso'=>false,'mensagem'=>'Usuário não é um cliente'],422);}
try{
$this->userModel->linkClientToProvider($existingUser['id'],$providerId);
$this->json(['sucesso'=>true,'mensagem'=>'Cliente adicionado com sucesso']);}
catch(\Exception $e){error_log("Erro ao adicionar cliente: ".$e->getMessage());
$this->json(['sucesso'=>false,'mensagem'=>'Erro ao adicionar cliente'],500);}}
public function destroy($id){
$this->requireAuth();$this->requireRole('fornecedor');
if($_SERVER['REQUEST_METHOD']!=='DELETE'){http_response_code(405);return;}
if(!$this->validateCsrf()){$this->json(['sucesso'=>false,'mensagem'=>'Token CSRF inválido'],403);}
$user=$this->getAuthUser();$providerId=$user['fornecedor_id']??null;
try{
$this->userModel->unlinkClientFromProvider($id,$providerId);
$this->json(['sucesso'=>true,'mensagem'=>'Cliente removido com sucesso']);}
catch(\Exception $e){error_log("Erro ao remover cliente: ".$e->getMessage());
$this->json(['sucesso'=>false,'mensagem'=>'Erro ao remover cliente'],500);}}
}
