<?php
namespace App\Models;

use Core\Database;

class Project
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function all()
    {
        $stmt = $this->pdo->query('SELECT p.*, u.name as client_name FROM projects p JOIN users u ON p.client_id = u.user_id ORDER BY p.created_at DESC');
        return $stmt->fetchAll();
    }

    public function publicList($threshold = 70)
    {
        $stmt = $this->pdo->prepare('SELECT p.*, u.name as client_name FROM projects p JOIN users u ON p.client_id = u.user_id WHERE p.progress >= :threshold ORDER BY p.created_at DESC');
        $stmt->execute(['threshold' => $threshold]);
        return $stmt->fetchAll();
    }

    public function forClient($clientId)
    {
        $stmt = $this->pdo->prepare('SELECT p.*, u.name as client_name FROM projects p JOIN users u ON p.client_id = u.user_id WHERE p.client_id = :cid ORDER BY p.created_at DESC');
        $stmt->execute(['cid' => $clientId]);
        return $stmt->fetchAll();
    }

    public function forBuilder($builderId)
    {
        $stmt = $this->pdo->prepare('SELECT p.*, u.name as client_name FROM projects p JOIN users u ON p.client_id = u.user_id WHERE p.builder_id = :bid ORDER BY p.created_at DESC');
        $stmt->execute(['bid' => $builderId]);
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare('SELECT p.*, u.name as client_name, u.email as client_email FROM projects p JOIN users u ON p.client_id = u.user_id WHERE p.project_id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO projects (client_id, builder_id, title, description, budget, progress, status) VALUES (:client_id, :builder_id, :title, :description, :budget, :progress, :status)');
        $res = $stmt->execute($data);
        if ($res) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function update($id, $data)
    {
        $data['project_id'] = $id;
        $stmt = $this->pdo->prepare('UPDATE projects SET client_id = :client_id, builder_id = :builder_id, title = :title, description = :description, budget = :budget, progress = :progress, status = :status, updated_at = NOW() WHERE project_id = :project_id');
        return $stmt->execute($data);
    }

    public function updateAdmin($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE projects SET admin_status = :admin_status, admin_message = :admin_message, estimated_days = :estimated_days, budget_issue = :budget_issue, updated_at = NOW() WHERE project_id = :project_id');
        return $stmt->execute([
            'admin_status' => $data['admin_status'] ?? 'pending',
            'admin_message' => $data['admin_message'] ?? null,
            'estimated_days' => $data['estimated_days'] ?? null,
            'budget_issue' => $data['budget_issue'] ?? null,
            'project_id' => $id,
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM projects WHERE project_id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function acceptQuotation($projectId, $quotationId)
    {
        $stmt = $this->pdo->prepare('UPDATE projects SET accepted_quotation_id = :qid, status = :status, updated_at = NOW() WHERE project_id = :pid');
        return $stmt->execute(['qid' => $quotationId, 'status' => 'assigned', 'pid' => $projectId]);
    }
}
