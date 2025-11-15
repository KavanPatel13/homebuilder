<?php
namespace App\Controller;

use Core\Controller;

class BuilderController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'builder') {
            $this->redirect('auth');
        }
        $this->view('Builder/index', ['user' => $_SESSION['user']]);
    }
}
