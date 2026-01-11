<?php
namespace App\Controllers;
use Core\Controller;
use App\Models\ServiceOrder;
use App\Models\Vehicle;
use App\Models\User;
class CertificateController extends Controller{
protected $serviceOrderModel;protected $vehicleModel;protected $userModel;
public function __construct(){$this->serviceOrderModel=new ServiceOrder();$this->vehicleModel=new Vehicle();$this->userModel=new User();}
public function download($code){
$os=$this->serviceOrderModel->findByCertificateCode($code);
if(!$os){$_SESSION['error']='Certificado não encontrado';header('Location: /');exit;}
$vehicle=$this->vehicleModel->findById($os['veiculo_id']);
$client=$this->userModel->findById($os['cliente_id']);
$provider=$this->userModel->findById($os['fornecedor_id']);
$this->generatePDF($os,$vehicle,$client,$provider);}
public function validate($code=null){
if(!$code&&isset($_GET['code'])){$code=$_GET['code'];}
$os=null;$vehicle=null;$client=null;$provider=null;$valid=false;
if($code){
$os=$this->serviceOrderModel->findByCertificateCode($code);
if($os){
$vehicle=$this->vehicleModel->findById($os['veiculo_id']);
$client=$this->userModel->findById($os['cliente_id']);
$provider=$this->userModel->findById($os['fornecedor_id']);
$valid=true;}}
$this->view('public/validate',['code'=>$code,'os'=>$os,'vehicle'=>$vehicle,'client'=>$client,'provider'=>$provider,'valid'=>$valid]);}
private function generatePDF($os,$vehicle,$client,$provider){
require_once __DIR__.'/../../vendor/fpdf/fpdf.php';
$pdf=new \FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',24);
$pdf->SetTextColor(102,126,234);
$pdf->Cell(0,20,'CERTIFICADO APP AUTO',0,1,'C');
$pdf->Ln(5);
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(0,10,'Este documento certifica que o servico automotivo foi realizado',0,1,'C');
$pdf->Cell(0,10,'e registrado no sistema APP AUTO',0,1,'C');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'CODIGO DO CERTIFICADO',0,1,'C');
$pdf->SetFont('Arial','',16);
$pdf->SetTextColor(102,126,234);
$pdf->Cell(0,10,$os['certificado_codigo'],0,1,'C');
$pdf->SetTextColor(0,0,0);
$pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,8,'Ordem de Servico:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,'#'.str_pad($os['id'],6,'0',STR_PAD_LEFT),0,1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,8,'Veiculo:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,$vehicle['marca'].' '.$vehicle['modelo'].' - '.$vehicle['placa'],0,1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,8,'Cliente:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,$client['nome'],0,1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,8,'Fornecedor:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,$provider['nome'],0,1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,8,'Data Conclusao:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,date('d/m/Y H:i',strtotime($os['data_conclusao'])),0,1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,8,'Valor Total:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,'R$ '.number_format($os['valor_total'],2,',','.'),0,1);
$pdf->Ln(10);
$pdf->SetFont('Arial','I',10);
$pdf->Cell(0,8,'Validacao: https://erp.appauto.com.br/certificado/'.$os['certificado_codigo'],0,1,'C');
$pdf->Ln(10);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(128,128,128);
$pdf->Cell(0,8,'Documento gerado automaticamente pelo APP AUTO',0,1,'C');
$pdf->Cell(0,8,date('d/m/Y H:i:s'),0,1,'C');
$pdf->Output('D','Certificado_'.$os['certificado_codigo'].'.pdf');}
}
