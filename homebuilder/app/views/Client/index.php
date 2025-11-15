<div class="container-fluid">
    <div class="dashboard-header mb-3">
        <h1>Client Dashboard</h1>
        <p>Welcome, <?=htmlspecialchars($user['name'])?> (<?=htmlspecialchars($user['email'])?>)</p>
    </div>


    <div class="mb-3 d-flex align-items-center">
        <a class="btn btn-outline-primary me-2" href="/homebuilder/index.php?url=project">Projects</a>
        <a class="btn btn-outline-secondary" href="/homebuilder/index.php?url=auth/logout">Logout</a>
    </div>

    <div class="card">
        <div class="card-body">
            <h4>My Requests</h4>
            <?php if (empty($projects)): ?>
                <p class="text-muted">You have not posted any project requests yet. Click <a href="/homebuilder/index.php?url=project/create">New Project</a> to post one.</p>
            <?php else: ?>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Budget</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $p): ?>
                        <tr>
                            <td><?=htmlspecialchars($p['project_id'])?></td>
                            <td><?=htmlspecialchars($p['title'])?></td>
                            <td><?=htmlspecialchars($p['budget'])?></td>
                            <td><?=htmlspecialchars($p['progress'])?>%</td>
                            <td><?=htmlspecialchars($p['status'])?></td>
                            <td>
                                <a class="btn btn-sm btn-outline-secondary" href="/homebuilder/index.php?url=project/details/<?=urlencode($p['project_id'])?>">Details</a>
                                <a class="btn btn-sm btn-primary" href="/homebuilder/index.php?url=project/edit/<?=urlencode($p['project_id'])?>">Edit</a>
                                <a class="btn btn-sm btn-danger" href="/homebuilder/index.php?url=project/delete/<?=urlencode($p['project_id'])?>" onclick="return confirm('Delete this project?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
