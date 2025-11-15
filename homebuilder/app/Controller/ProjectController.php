<?php
namespace App\Controller;

use Core\Controller;
use App\Models\Project;

class ProjectController extends Controller
{
    protected $projectModel;

    public function __construct()
    {
        parent::__construct();
        $this->projectModel = new Project();
    }

    public function index()
    {
        $user = $_SESSION['user'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_unlock_password'])) {
            $cfg = require __DIR__ . '/../../Config/config.php';
            $adminPassword = $cfg['ADMIN_VIEW_PASSWORD'] ?? 'Admin@123';
            $pw = $_POST['admin_unlock_password'] ?? '';
            if ($pw === $adminPassword) {
                $_SESSION['admin_unlocked_all'] = true;
                $this->redirect('project');
            }
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Incorrect admin password.'];
            $this->redirect('project');
            return;
        }

        if (($user && $user['role'] === 'admin') || !empty($_SESSION['admin_unlocked_all'])) {
            $projects = $this->projectModel->all();
            $this->view('Project/index', ['projects' => $projects]);
            return;
        }

        if ($user && $user['role'] === 'builder') {
            $cfg = require __DIR__ . '/../../Config/config.php';
            $threshold = $cfg['PUBLIC_PROGRESS_THRESHOLD'] ?? 70;
            $public = $this->projectModel->publicList($threshold);
            $assigned = $this->projectModel->forBuilder($user['user_id']);

            $map = [];
            foreach ($public as $p) {
                $map[$p['project_id']] = $p;
            }
            foreach ($assigned as $p) {
                $map[$p['project_id']] = $p;
            }
            $projects = array_values($map);
            $this->view('Project/index', ['projects' => $projects]);
            return;
        }

        $this->view('Project/unlock');
    }

    public function show($id = null)
    {
        if (!$id) {
            $this->redirect('project');
        }
        $project = $this->projectModel->find($id);
        if (!$project) {
            $this->redirect('project');
        }

        $cfg = require __DIR__ . '/../../Config/config.php';
        $threshold = $cfg['PUBLIC_PROGRESS_THRESHOLD'] ?? 70;
        $adminPassword = $cfg['ADMIN_VIEW_PASSWORD'] ?? 'Admin@123';

        $isPublic = intval($project['progress']) >= intval($threshold);
        $user = $_SESSION['user'] ?? null;

        if ($user && $user['role'] === 'admin') {
            $this->view('Project/view', ['project' => $project, 'quotations' => []]);
            return;
        }

        if (!$isPublic) {
            $_SESSION['flash'] = ['type' => 'info', 'message' => 'This project is not public. Please log in to view.'];
            $this->redirect('auth');
        }

        if (!empty($_SESSION['unlocked_projects'][$id])) {
            $this->view('Project/view', ['project' => $project, 'quotations' => []]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pw = $_POST['admin_password'] ?? '';
            if ($pw === $adminPassword) {
                $_SESSION['unlocked_projects'][$id] = true;
                $this->view('Project/view', ['project' => $project, 'quotations' => []]);
                return;
            }
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Incorrect admin password.'];
            $this->redirect("project/show/{$id}");
        }

        $this->view('Project/locked', ['project' => $project]);
    }
    public function details($id = null)
    {
        $this->authorize('client');
        if (!$id) {
            $this->redirect('project');
        }
        $project = $this->projectModel->find($id);
        if (!$project) {
            $this->redirect('project');
        }
        $user = $_SESSION['user'];
        if (intval($project['client_id']) !== intval($user['user_id'])) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'You are not allowed to view this project.'];
            $this->redirect('project');
        }
        $this->view('Project/details', ['project' => $project]);
    }

    public function adminUpdate($id = null)
    {
        $this->authorize('admin');
        if (!$id) {
            $this->redirect('project');
        }
        $project = $this->projectModel->find($id);
        if (!$project) {
            $this->redirect('project');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'admin_status' => $_POST['admin_status'] ?? 'pending',
                'admin_message' => $_POST['admin_message'] ?? null,
                'estimated_days' => $_POST['estimated_days'] ?? null,
                'budget_issue' => $_POST['budget_issue'] ?? null,
            ];
            $this->projectModel->updateAdmin($id, $data);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Admin decision saved.'];
            $this->redirect("project/details/{$id}");
        }
        $this->view('Project/edit', ['project' => $project]);
    }

    public function create()
    {
        $this->authorize('client');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user']['user_id'];
            $data = [
                'client_id' => $user_id,
                'builder_id' => null,
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'budget' => $_POST['budget'] ?? 0,
                'progress' => 0,
                'status' => 'open'
            ];
            $this->projectModel->create($data);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Project request created.'];
            $this->redirect('project');
        }
        $this->view('Project/create');
    }

    public function edit($id = null)
    {
        if (!$id) {
            $this->redirect('project');
        }
        $project = $this->projectModel->find($id);
        if (!$project) {
            $this->redirect('project');
        }

        $this->authorize(['client', 'admin', 'builder']);
        $user = $_SESSION['user'];
        if ($user['role'] === 'client' && intval($project['client_id']) !== intval($user['user_id'])) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'You are not allowed to edit this project.'];
            $this->redirect('project');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($user['role'] === 'client') {
                $data = [
                    'client_id' => $project['client_id'],
                    'builder_id' => $project['builder_id'],
                    'title' => $_POST['title'] ?? $project['title'],
                    'description' => $_POST['description'] ?? $project['description'],
                    'budget' => $_POST['budget'] ?? $project['budget'],
                    'progress' => $project['progress'],
                    'status' => $project['status']
                ];
            } else {
                if ($user['role'] === 'admin') {
                    $data = [
                        'client_id' => $project['client_id'],
                        'builder_id' => $_POST['builder_id'] ?? $project['builder_id'],
                        'title' => $project['title'],
                        'description' => $project['description'],
                        'budget' => $project['budget'],
                        'progress' => $_POST['progress'] ?? $project['progress'],
                        'status' => $_POST['status'] ?? $project['status']
                    ];
                    $adminData = [
                        'admin_status' => $_POST['admin_status'] ?? ($project['admin_status'] ?? 'pending'),
                        'admin_message' => $_POST['admin_message'] ?? ($project['admin_message'] ?? null),
                        'estimated_days' => $_POST['estimated_days'] ?? ($project['estimated_days'] ?? null),
                        'budget_issue' => $_POST['budget_issue'] ?? ($project['budget_issue'] ?? null),
                    ];
                } else {
                    $data = [
                        'client_id' => $project['client_id'],
                        'builder_id' => $_POST['builder_id'] ?? $project['builder_id'],
                        'title' => $_POST['title'] ?? $project['title'],
                        'description' => $_POST['description'] ?? $project['description'],
                        'budget' => $_POST['budget'] ?? $project['budget'],
                        'progress' => $_POST['progress'] ?? $project['progress'],
                        'status' => $_POST['status'] ?? $project['status']
                    ];
                }
            }

            $this->projectModel->update($id, $data);
            if (!empty($adminData) && $user['role'] === 'admin') {
                $this->projectModel->updateAdmin($id, $adminData);
            }
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Project updated.'];
            $this->redirect('project');
        }

        $this->view('Project/edit', ['project' => $project]);
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->redirect('project');
        }
        $project = $this->projectModel->find($id);
        if (!$project) {
            $this->redirect('project');
        }

        $this->authorize(['client', 'admin']);
        $user = $_SESSION['user'];
        if ($user['role'] === 'client' && intval($project['client_id']) !== intval($user['user_id'])) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'You are not allowed to delete this project.'];
            $this->redirect('project');
        }

        $this->projectModel->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Project deleted.'];
        $this->redirect('project');
    }
}

