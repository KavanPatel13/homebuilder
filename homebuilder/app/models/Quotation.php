<?php
namespace App\Models;

use Core\Database;

class Quotation
{
	protected $pdo;

	public function __construct()
	{
		$this->pdo = Database::getConnection();
	}

	public function all()
	{
		$stmt = $this->pdo->query('SELECT q.*, b.name as builder_name FROM quotations q LEFT JOIN users b ON q.builder_id = b.user_id ORDER BY q.created_at DESC');
		return $stmt->fetchAll();
	}

	public function forProject($projectId)
	{
		$stmt = $this->pdo->prepare('SELECT q.*, b.name as builder_name FROM quotations q LEFT JOIN users b ON q.builder_id = b.user_id WHERE q.project_id = :pid ORDER BY q.created_at DESC');
		$stmt->execute(['pid' => $projectId]);
		return $stmt->fetchAll();
	}

	public function find($id)
	{
		$stmt = $this->pdo->prepare('SELECT q.*, b.email as builder_email FROM quotations q LEFT JOIN users b ON q.builder_id = b.user_id WHERE q.quotation_id = :id LIMIT 1');
		$stmt->execute(['id' => $id]);
		return $stmt->fetch();
	}

	public function create($data)
	{
		$stmt = $this->pdo->prepare('INSERT INTO quotations (project_id, builder_id, amount, message, created_at) VALUES (:project_id, :builder_id, :amount, :message, NOW())');
		return $stmt->execute([
			'project_id' => $data['project_id'],
			'builder_id' => $data['builder_id'] ?? null,
			'amount' => $data['amount'] ?? 0,
			'message' => $data['message'] ?? null,
		]);
	}

	public function update($id, $data)
	{
		$data['quotation_id'] = $id;
		$stmt = $this->pdo->prepare('UPDATE quotations SET amount = :amount, message = :message, status = :status WHERE quotation_id = :quotation_id');
		return $stmt->execute([
			'amount' => $data['amount'] ?? 0,
			'message' => $data['message'] ?? null,
			'status' => $data['status'] ?? 'pending',
			'quotation_id' => $id,
		]);
	}

	public function delete($id)
	{
		$stmt = $this->pdo->prepare('DELETE FROM quotations WHERE quotation_id = :id');
		return $stmt->execute(['id' => $id]);
	}
}
