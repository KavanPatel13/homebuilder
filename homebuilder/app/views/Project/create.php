<div class="card">
  <div class="card-body">
    <h3>Create Project</h3>
    <form method="post" action="/homebuilder/index.php?url=project/create">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input name="title" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Budget</label>
        <input name="budget" type="number" step="0.01" class="form-control">
      </div>
      <div class="mb-3">
        <small class="text-muted">You are posting this request as the logged-in client.</small>
      </div>
      <button class="btn btn-success">Create</button>
    </form>
  </div>
</div>
