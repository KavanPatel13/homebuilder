<?php
namespace App\Controller;

use Core\Controller;
use App\Models\Project;

class ClientController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'client') {
            $this->redirect('auth');
        }
        $projectModel = new Project();
        $projects = $projectModel->forClient($_SESSION['user']['user_id']);
        $this->view('Client/index', ['user' => $_SESSION['user'], 'projects' => $projects]);
    }
}
