<?php

/**
 * ============================================================================
 * APP AUTO - Controller: HomeController
 * ============================================================================
 */

namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    /**
     * Página inicial
     */
    public function index()
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

        $this->redirect('/login');
    }
}
