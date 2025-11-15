<?php
// project list
?>
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Projects</h2>
    <a class="btn btn-primary" href="/homebuilder/index.php?url=project/create">New Project</a>
  </div>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>Title</th>
        <th>Client</th>
        <th>Budget</th>
        <th>Progress</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($projects as $p): ?>
        <tr>
          <td><?=htmlspecialchars($p['project_id'])?></td>
          <td><?=htmlspecialchars($p['title'])?></td>
          <td><?=htmlspecialchars($p['client_name'] ?? 'N/A')?></td>
          <td><?=htmlspecialchars($p['budget'])?></td>
          <td><?=htmlspecialchars($p['progress'])?>%</td>
          <td><?=htmlspecialchars($p['status'])?></td>
          <td>
            <a class="btn btn-sm btn-secondary" href="/homebuilder/index.php?url=project/edit/<?=urlencode($p['project_id'])?>">Edit</a>
            <a class="btn btn-sm btn-danger" href="/homebuilder/index.php?url=project/delete/<?=urlencode($p['project_id'])?>" onclick="return confirm('Delete this project?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
