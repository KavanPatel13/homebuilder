<?php
namespace App\Controller;

use Core\Controller;
use Core\Database;

class AuthController extends Controller
{
    public function index()
    {
        $this->view('Auth/login');
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $this->view('Auth/login', ['error' => 'Email and password are required']);
            return;
        }

        $stmt = Database::getConnection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'user_id' => $user['user_id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ];

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Login successful. Welcome back, ' . $user['name'] . '!'];

            if ($user['role'] === 'admin') {
                $this->redirect('admin');
            } elseif ($user['role'] === 'builder') {
                $this->redirect('builder');
            } else {
                $this->redirect('client');
            }
            return;
        }

        $this->view('Auth/login', ['error' => 'Invalid credentials']);
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'client';

            if (!$name || !$email || !$password) {
                $this->view('Auth/register', ['error' => 'All fields are required']);
                return;
            }

            $pdo = Database::getConnection();
            $stmt = $pdo->prepare('SELECT user_id FROM users WHERE email = :email');
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                $this->view('Auth/register', ['error' => 'Email already registered']);
                return;
            }

            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role, status) VALUES (:name, :email, :password, :role, :status)');
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $hash,
                'role' => $role,
                'status' => 'active'
            ]);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Registration successful. Please login.'];
            $this->redirect('auth');
        } else {
            $this->view('Auth/register');
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('auth');
    }
}
