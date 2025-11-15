<?php
namespace App\Controller;

use Core\Controller;
use Core\Mailer;
use App\Models\Quotation;
use App\Models\Project;

class QuotationController extends Controller
{
    protected $quotationModel;
    protected $projectModel;

    public function __construct()
    {
        parent::__construct();
        $this->quotationModel = new Quotation();
        $this->projectModel = new Project();
    }

    public function create($projectId = null)
    {
        $this->authorize('builder');
        if (!$projectId) {
            $this->redirect('project');
        }
        $project = $this->projectModel->find($projectId);
        if (!$project) {
            $this->redirect('project');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'project_id' => $projectId,
                'builder_id' => $_SESSION['user']['user_id'],
                'amount' => $_POST['amount'] ?? 0,
                'message' => $_POST['message'] ?? null,
            ];
            $this->quotationModel->create($data);

            $mailer = new Mailer();
            $to = $project['email'] ?? null;
            if (!$to) {
                $to = $project['client_email'] ?? null;
            }
            if ($to) {
                $subject = "New quotation for your project: " . $project['title'];
                $msg = "A new quotation has been submitted by builder.\nAmount: " . $data['amount'] . "\nMessage:\n" . ($data['message'] ?? '') ;
                $mailer->send($to, $subject, $msg);
            }

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Quotation submitted.'];
            $this->redirect("project/show/{$projectId}");
        }

        $this->view('Quotation/create', ['project' => $project]);
    }

    public function project($projectId = null)
    {
        if (!$projectId) {
            $this->redirect('project');
        }
        $project = $this->projectModel->find($projectId);
        if (!$project) {
            $this->redirect('project');
        }
        $quotations = $this->quotationModel->forProject($projectId);
        $this->view('Quotation/index', ['project' => $project, 'quotations' => $quotations]);
    }

    public function accept($id = null)
    {
        $this->authorize('client');
        if (!$id) {
            $this->redirect('project');
        }
        $quote = $this->quotationModel->find($id);
        if (!$quote) {
            $this->redirect('project');
        }
        $project = $this->projectModel->find($quote['project_id']);
        if (!$project) {
            $this->redirect('project');
        }
        if (intval($project['client_id']) !== intval($_SESSION['user']['user_id'])) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Not allowed'];
            $this->redirect('project');
        }

        $this->quotationModel->update($id, ['status' => 'accepted']);
        $this->projectModel->acceptQuotation($project['project_id'], $id);

        $mailer = new Mailer();
        $builderEmail = $quote['builder_email'] ?? null;
        $clientEmail = $project['client_email'] ?? null;
        $cfg = require __DIR__ . '/../../Config/config.php';
        $adminEmail = $cfg['MAIL_FROM'] ?? null;

        if ($builderEmail) {
            $mailer->send($builderEmail, 'Your quotation was accepted', "Client accepted quotation for project: {$project['title']}");
        }
        if ($clientEmail) {
            $mailer->send($clientEmail, 'You accepted a quotation', "You accepted quotation #{$id} for project: {$project['title']}");
        }
        if ($adminEmail) {
            $mailer->send($adminEmail, 'Quotation accepted', "Quotation #{$id} was accepted for project: {$project['title']}");
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Quotation accepted.'];
        $this->redirect("project/details/{$project['project_id']}");
    }
}
