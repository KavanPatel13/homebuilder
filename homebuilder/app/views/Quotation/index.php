<?php
?>
<div class="card">
  <div class="card-body">
    <h3>Quotations for: <?=htmlspecialchars($project['title'])?></h3>
    <?php if (empty($quotations)): ?>
      <p>No quotations yet.</p>
    <?php else: ?>
      <table class="table table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>Builder</th>
            <th>Amount</th>
            <th>Message</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($quotations as $q): ?>
            <tr>
              <td><?=htmlspecialchars($q['quotation_id'] ?? $q['quote_id'] ?? '')?></td>
              <td><?=htmlspecialchars($q['builder_name'] ?? '')?></td>
              <td><?=htmlspecialchars($q['amount'])?></td>
              <td><?=nl2br(htmlspecialchars($q['message']))?></td>
              <td><?=htmlspecialchars($q['status'] ?? 'pending')?></td>
              <td>
                <?php if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'client'): ?>
                  <a class="btn btn-sm btn-success" href="/homebuilder/index.php?url=quotation/accept/<?=urlencode($q['quotation_id'] ?? $q['quote_id'])?>" onclick="return confirm('Accept this quotation?')">Accept</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
