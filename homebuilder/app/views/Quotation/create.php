<?php
?>
<div class="card">
  <div class="card-body">
    <h3>Submit Quotation for: <?=htmlspecialchars($project['title'])?></h3>
    <form method="post" action="/homebuilder/index.php?url=quotation/create/<?=urlencode($project['project_id'])?>">
      <div class="mb-3">
        <label class="form-label">Amount</label>
        <input name="amount" type="number" step="0.01" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Message</label>
        <textarea name="message" class="form-control"></textarea>
      </div>
      <button class="btn btn-primary">Submit Quotation</button>
    </form>
  </div>
</div>
