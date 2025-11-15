<?php
?>
<div class="card">
  <div class="card-body">
    <h3>Protected Project: <?=htmlspecialchars($project['title'])?></h3>
    <p>This project's details are protected. Enter the admin password to view details.</p>
  <form method="post" action="/homebuilder/index.php?url=project/show/<?=urlencode($project['project_id'])?>">
      <div class="mb-3">
        <label class="form-label">Admin Password</label>
        <input name="admin_password" type="password" class="form-control" required>
      </div>
      <button class="btn btn-primary">Unlock</button>
    </form>
  </div>
</div>
