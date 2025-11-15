<div class="card">
  <div class="card-body">
    <h3>Edit Project #<?=htmlspecialchars($project['project_id'])?></h3>
    <form method="post" action="/homebuilder/index.php?url=project/edit/<?=urlencode($project['project_id'])?>">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <?php if (!empty(
            
            $_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
          <input class="form-control" value="<?=htmlspecialchars($project['title'])?>" readonly>
        <?php else: ?>
          <input name="title" class="form-control" value="<?=htmlspecialchars($project['title'])?>" required>
        <?php endif; ?>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <?php if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
          <textarea class="form-control" readonly><?=htmlspecialchars($project['description'])?></textarea>
        <?php else: ?>
          <textarea name="description" class="form-control"><?=htmlspecialchars($project['description'])?></textarea>
        <?php endif; ?>
      </div>
      <div class="mb-3">
        <label class="form-label">Budget</label>
        <?php if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
          <input class="form-control" value="<?=htmlspecialchars($project['budget'])?>" readonly>
        <?php else: ?>
          <input name="budget" type="number" step="0.01" class="form-control" value="<?=htmlspecialchars($project['budget'])?>">
        <?php endif; ?>
      </div>
      <div class="mb-3">
        <label class="form-label">Progress (%)</label>
        <input name="progress" type="number" min="0" max="100" class="form-control" value="<?=htmlspecialchars($project['progress'])?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Status</label>
        <input name="status" class="form-control" value="<?=htmlspecialchars($project['status'])?>">
      </div>
      <?php if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
      <hr>
      <h5>Admin Decision</h5>
      <div class="mb-3">
        <label class="form-label">Admin Status</label>
        <select name="admin_status" class="form-control">
          <option value="pending" <?= (isset($project['admin_status']) && $project['admin_status'] === 'pending') ? 'selected' : '' ?>>Pending</option>
          <option value="accepted" <?= (isset($project['admin_status']) && $project['admin_status'] === 'accepted') ? 'selected' : '' ?>>Accepted</option>
          <option value="rejected" <?= (isset($project['admin_status']) && $project['admin_status'] === 'rejected') ? 'selected' : '' ?>>Rejected</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Estimated Days</label>
        <input name="estimated_days" type="number" class="form-control" value="<?=htmlspecialchars($project['estimated_days'] ?? '')?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Budget Issue (if any)</label>
        <input name="budget_issue" class="form-control" value="<?=htmlspecialchars($project['budget_issue'] ?? '')?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Admin Message</label>
        <textarea name="admin_message" class="form-control"><?=htmlspecialchars($project['admin_message'] ?? '')?></textarea>
      </div>
      <?php endif; ?>
      <button class="btn btn-primary">Save</button>
    </form>
  </div>
</div>
