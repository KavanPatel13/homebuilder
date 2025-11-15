<?php
?>
<div class="card">
  <div class="card-body">
    <h3>Admin Access Required</h3>
    <p>To view the projects list, please enter the admin password.</p>
    <form method="post" action="/homebuilder/index.php?url=project">
      <div class="mb-3">
        <label class="form-label">Admin Password</label>
        <input name="admin_unlock_password" type="password" class="form-control" required>
      </div>
      <button class="btn btn-primary">Unlock</button>
    </form>
  </div>
</div>
