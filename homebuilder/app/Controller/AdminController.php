<?php
namespace App\Controller;

use Core\Controller;
use App\Models\Project;

class AdminController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('auth');
        }
        $projectModel = new Project();
        $projects = $projectModel->all();
        $this->view('Admin/index', ['user' => $_SESSION['user'], 'projects' => $projects]);
    }
}
