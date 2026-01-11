<?php
namespace App\Controllers;
use Core\Controller;
use App\Models\User;
use App\Models\ServiceOrder;
use App\Models\Vehicle;
class AdminDashboardController extends Controller{
protected $userModel;protected $serviceOrderModel;protected $vehicleModel;
public function __construct(){$this->userModel=new User();$this->serviceOrderModel=new ServiceOrder();$this->vehicleModel=new Vehicle();}
public function index(){
$this->requireAuth();$this->requireRole('admin');
$user=$this->getAuthUser();
$stats=['totalUsers'=>100,'totalProviders'=>20,'totalClients'=>80,'totalVehicles'=>150,'totalOS'=>200,'totalRevenue'=>50000];
$this->view('admin/dashboard',['user'=>$user,'stats'=>$stats]);}}
