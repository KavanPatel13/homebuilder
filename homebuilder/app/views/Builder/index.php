<div class="container-fluid">
    <div class="dashboard-header mb-3">
        <h1>Builder Dashboard</h1>
        <p>Welcome, <?=htmlspecialchars($user['name'])?> (<?=htmlspecialchars($user['email'])?>)</p>
    </div>

    <div class="stats-grid mb-3">
        <div class="stat">
            <div class="value">--</div>
            <div class="label">My Projects</div>
        </div>
        <div class="stat">
            <div class="value">--</div>
            <div class="label">Pending Quotes</div>
        </div>
        <div class="stat">
            <div class="value">--</div>
            <div class="label">Materials</div>
        </div>
    </div>

    <div class="mb-3">
        <a class="btn btn-outline-primary me-2" href="/homebuilder/index.php?url=project">Projects</a>
        <a class="btn btn-outline-secondary" href="/homebuilder/index.php?url=auth/logout">Logout</a>
    </div>
</div>
