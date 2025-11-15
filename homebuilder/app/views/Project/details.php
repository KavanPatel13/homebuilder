<?php
?>
<div class="card">
  <div class="card-body">
    <h3><?=htmlspecialchars($project['title'])?></h3>
    <p><?=nl2br(htmlspecialchars($project['description']))?></p>
    <dl class="row">
      <dt class="col-sm-3">Budget</dt>
      <dd class="col-sm-9"><?=htmlspecialchars($project['budget'])?></dd>

      <dt class="col-sm-3">Progress</dt>
      <dd class="col-sm-9"><?=htmlspecialchars($project['progress'])?>%</dd>

      <dt class="col-sm-3">Status</dt>
      <dd class="col-sm-9"><?=htmlspecialchars($project['status'])?></dd>

      <dt class="col-sm-3">Admin Decision</dt>
      <dd class="col-sm-9"><?=htmlspecialchars($project['admin_status'] ?? 'pending')?></dd>

      <dt class="col-sm-3">Admin Note</dt>
      <dd class="col-sm-9"><?=nl2br(htmlspecialchars($project['admin_message'] ?? ''))?></dd>

      <dt class="col-sm-3">Estimated Days</dt>
      <dd class="col-sm-9"><?=htmlspecialchars($project['estimated_days'] ?? 'N/A')?></dd>

      <dt class="col-sm-3">Budget Issue</dt>
      <dd class="col-sm-9"><?=htmlspecialchars($project['budget_issue'] ?? '')?></dd>
    </dl>

    <?php if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
      <a class="btn btn-primary" href="/homebuilder/index.php?url=project/edit/<?=urlencode($project['project_id'])?>">Manage</a>
    <?php endif; ?>
  </div>
</div>
