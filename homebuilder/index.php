<?php
require_once __DIR__ . '/Core/Database.php';
require_once __DIR__ . '/Core/Controller.php';
require_once __DIR__ . '/Core/App.php';

spl_autoload_register(function ($class) {
        $prefix = 'App\\';
        $base_dir = __DIR__ . '/app/';
        if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
                return;
        }
        $relative = substr($class, strlen($prefix));
        $file = $base_dir . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($file)) {
                require $file;
        }
});

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['user'] ?? null;

if (!empty($_GET['app']) && $_GET['app'] === '1') {
    $_SESSION['app_mode'] = true;
}
if (!empty($_GET['url'])) {
    $_SESSION['app_mode'] = true;
}

$showApp = $user || !empty($_SESSION['app_mode']);

ob_start();
if ($showApp) {
    $app = new \Core\App();
}
$content = ob_get_clean();

?><!doctype html>
<html>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Home Builder Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php $cfg = require __DIR__ . '/Config/config.php'; $baseUrl = rtrim($cfg['BASE_URL'], '/'); ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl) ?>/assets/css/dashboard.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        .landing-page {
            background: linear-gradient(135deg, #0052CC 0%, #005FEB 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .landing-header {
            padding: 30px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .landing-header .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: bold;
            color: #0052CC;
            text-decoration: none;
            font-size: 18px;
        }
        
        .landing-header .logo svg {
            width: 32px;
            height: 32px;
        }
        
        .landing-header nav {
            display: flex;
            gap: 30px;
        }
        
        .landing-header nav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .landing-header nav a:hover {
            color: #0052CC;
        }
        
        .landing-hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            padding: 60px 20px;
        }
        
        .landing-hero h1 {
            font-size: 56px;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }
        
        .landing-hero p {
            font-size: 24px;
            margin-bottom: 50px;
            opacity: 0.95;
        }
        
        .get-started-btn {
            background: white;
            color: #0052CC;
            padding: 16px 48px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .get-started-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            color: #0052CC;
            text-decoration: none;
        }
        
        .landing-features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 60px 50px;
            background: white;
        }
        
        .feature-card {
            text-align: center;
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: #E6F0FF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }
        
        .feature-card h3 {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.6;
        }
        
        .landing-footer {
            background: #0052CC;
            color: white;
            text-align: center;
            padding: 30px;
            margin-top: auto;
        }
        
        /* App Layout Styles */
        .app-layout {
            display: flex;
            min-height: 100vh;
        }
        
        .app-left {
            width: 320px;
            padding: 20px;
            background: #f4f4f4;
            overflow: auto;
        }
        
        .app-right {
            flex: 1;
            padding: 20px;
            overflow: auto;
        }
        
        .card { background: #fff; padding: 15px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 12px; }
        h2 { margin-top: 0; }
        
        @media (max-width: 1024px) {
            .landing-features {
                grid-template-columns: 1fr;
                padding: 40px 20px;
            }
            .landing-header {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php if (!$showApp): ?>
    <!-- Landing Page -->
    <div class="landing-page">
        <header class="landing-header">
            <a href="#" class="logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Home Builder<br><small style="font-size: 12px; opacity: 0.8;">Management System</small>
            </a>
            <!-- <nav>
                <a href="#features">Features</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </nav> -->
        </header>
        
        <div class="landing-hero">
            <h1>Home Builder Management System</h1>
            <!-- <p>A real-time home builder management website.</p> -->
            <a href="/homebuilder/index.php?app=1" class="get-started-btn">Get Started</a>
        </div>
        
        <section class="landing-features" id="features">
            <div class="feature-card">
                <div class="feature-icon">⏱️</div>
                <h3>Real-time Updates</h3>
                <p>Loading project progress...</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📁</div>
                <h3>Manage Projects</h3>
                <p>Easily add and manage construction projects.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👤</div>
                <h3>User Management</h3>
                <p>Manage user roles and permissions</p>
            </div>
        </section>
        
        <footer class="landing-footer" id="contact">
            <p>&copy; 2025 Home Builder Management System | Designed by Kavan,Yash and Dhyey</p>
        </footer>
    </div>
    
    <?php else: ?>
    <!-- App Layout -->
    <div class="app-layout">
        <aside class="app-left">
            <div class="card">
                <h2>Actions</h2>
                <ul class="list-unstyled mb-0">
                    <?php
                    if (!$user):
                        ?>
                        <li><a class="d-block py-2" href="/homebuilder/index.php?url=auth">Login</a></li>
                        <li><a class="d-block py-2" href="/homebuilder/index.php?url=auth/register">Register</a></li>
                        <li><a class="d-block py-2" href="/homebuilder/index.php?url=project">Projects</a></li>
                    <?php else: ?>
                        <li class="mb-2"><strong>Welcome, <?= htmlspecialchars($user['name'] ?? $user['email']) ?></strong></li>
                        <?php
                            if ($user['role'] === 'client') {
                                echo '<li><a class="d-block py-2" href="/homebuilder/index.php?url=client">My Requests</a></li>';
                            } elseif ($user['role'] === 'builder') {
                                echo '<li><a class="d-block py-2" href="/homebuilder/index.php?url=builder">Projects</a></li>';
                            } else {
                                echo '<li><a class="d-block py-2" href="/homebuilder/index.php?url=project">Projects</a></li>';
                            }
                        ?>
                        <li><a class="d-block py-2" href="/homebuilder/index.php?url=auth/logout">Logout</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="card">
                <p>Click an action to load it in the right pane.</p>
            </div>
        </aside>
        <main class="app-right">
            <?php
            if (!empty($_SESSION['flash'])) {
                $f = $_SESSION['flash'];
                $type = ($f['type'] ?? 'info');
                $class = 'alert-' . ($type === 'success' ? 'success' : ($type === 'error' ? 'danger' : 'info'));
                echo '<div class="alert ' . $class . '" role="alert">' . htmlspecialchars($f['message']) . '</div>';
                unset($_SESSION['flash']);
            }
            ?>
            <?= $content ?>
        </main>
    </div>
    <?php endif; ?>
</body>
</html>

