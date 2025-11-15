<?php
?>
<div class="card">
  <div class="card-body">
    <h3><?=htmlspecialchars($project['title'])?></h3>
    <p><?=nl2br(htmlspecialchars($project['description']))?></p>
    <p><strong>Budget:</strong> <?=htmlspecialchars($project['budget'])?></p>
    <p><strong>Progress:</strong> <?=htmlspecialchars($project['progress'])?>%</p>
    <p><strong>Status:</strong> <?=htmlspecialchars($project['status'])?></p>

    <hr>
    <h5>Quotations</h5>
    <?php if (empty($quotations)): ?>
      <p>No quotations yet.</p>
    <?php else: ?>
      <ul class="list-group mb-3">
        <?php foreach ($quotations as $q): ?>
          <li class="list-group-item">
            <div><strong>Amount:</strong> <?=htmlspecialchars($q['amount'])?></div>
            <div><strong>By:</strong> <?=htmlspecialchars($q['builder_name'] ?? 'Builder')?></div>
            <div><?=nl2br(htmlspecialchars($q['message']))?></div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <?php if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'builder'): ?>
      <a class="btn btn-primary" href="/homebuilder/index.php?url=quotation/create/<?=urlencode($project['project_id'])?>">Submit Quotation</a>
    <?php endif; ?>
  </div>
</div>
