<div class="container-fluid">
    <div class="dashboard-header mb-3">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?=htmlspecialchars($user['name'])?> (<?=htmlspecialchars($user['email'])?>)</p>
    </div>


    <?php if (!empty($projects)): ?>
    <div class="card">
        <div class="card-body">
            <h4>Recent Projects</h4>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Client</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($projects, 0, 8) as $p): ?>
                    <tr>
                        <td><?=htmlspecialchars($p['project_id'])?></td>
                        <td><?=htmlspecialchars($p['title'])?></td>
                        <td><?=htmlspecialchars($p['client_name'] ?? 'N/A')?></td>
                        <td><?=htmlspecialchars($p['progress'])?>%</td>
                        <td><?=htmlspecialchars($p['status'])?></td>
                        <td>
                            <a class="btn btn-sm btn-secondary" href="/homebuilder/index.php?url=project/edit/<?=urlencode($p['project_id'])?>">Manage</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
