<?php
namespace App\Models;

use Core\Database;

class Metrial
{
	protected $pdo;

	public function __construct()
	{
		$this->pdo = Database::getConnection();
	}

	public function all()
	{
		$stmt = $this->pdo->query('SELECT * FROM materials ORDER BY created_at DESC');
		return $stmt->fetchAll();
	}

	public function find($id)
	{
		$stmt = $this->pdo->prepare('SELECT * FROM materials WHERE material_id = :id LIMIT 1');
		$stmt->execute(['id' => $id]);
		return $stmt->fetch();
	}

	public function create($data)
	{
		$stmt = $this->pdo->prepare('INSERT INTO materials (name, description, unit, price, stock, created_at) VALUES (:name, :description, :unit, :price, :stock, NOW())');
		return $stmt->execute([
			'name' => $data['name'] ?? null,
			'description' => $data['description'] ?? null,
			'unit' => $data['unit'] ?? null,
			'price' => $data['price'] ?? 0,
			'stock' => $data['stock'] ?? 0,
		]);
	}

	public function update($id, $data)
	{
		$data['material_id'] = $id;
		$stmt = $this->pdo->prepare('UPDATE materials SET name = :name, description = :description, unit = :unit, price = :price, stock = :stock WHERE material_id = :material_id');
		return $stmt->execute([
			'name' => $data['name'] ?? null,
			'description' => $data['description'] ?? null,
			'unit' => $data['unit'] ?? null,
			'price' => $data['price'] ?? 0,
			'stock' => $data['stock'] ?? 0,
			'material_id' => $id,
		]);
	}

	public function delete($id)
	{
		$stmt = $this->pdo->prepare('DELETE FROM materials WHERE material_id = :id');
		return $stmt->execute(['id' => $id]);
	}
}
