<!-- Bootstrap CSS -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: #f5f6fa;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'Segoe UI', sans-serif;
    }

    .login-card {
        width: 400px;
        padding: 30px;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        animation: fadeIn 0.4s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .login-card h3 {
        font-weight: 600;
        margin-bottom: 20px;
    }

    .btn-primary {
        padding: 10px;
        font-size: 16px;
        font-weight: 500;
        border-radius: 8px;
    }
</style> -->

<div class="login-card">
    <h3 class="text-center">Login</h3>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars('/homebuilder/index.php?url=auth/login') ?>">
        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input class="form-control" type="email" name="email" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <input class="form-control" type="password" name="password" required>
        </div>

        <div class="d-grid">
            <button class="btn btn-primary" type="submit">Login</button>
        </div>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
