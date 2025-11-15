
 <div class="collapse" id="registerForm">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>
    <form method="post" action="<?= htmlspecialchars('/homebuilder/index.php?url=auth/register') ?>">
        <div class="mb-2">
            <label class="form-label">Name</label>
            <input class="form-control" type="text" name="name" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" name="email" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Password</label>
            <input class="form-control" type="password" name="password" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Role</label>
            <select class="form-select" name="role">
                <option value="client">Client</option>
                <option value="builder">Builder</option>
                <!-- <option value="admin">Admin</option> -->
            </select>
        </div>
        <div class="d-grid">
            <button class="btn btn-success" type="submit">Register</button>
        </div>
    </form>
    <div class="mt-2">
        <button class="btn btn-link p-0" data-bs-toggle="collapse" data-bs-target="#registerForm">Close</button>
    </div>
</div>

<?php
if ((isset($_GET['url']) && stripos($_GET['url'], 'auth/register') !== false) && empty($_POST)) {
    echo '<script>document.addEventListener("DOMContentLoaded", function(){ var el = document.getElementById("registerForm"); if (el) { var bs = bootstrap.Collapse.getOrCreateInstance(el); bs.show(); }});</script>';
}
?>
<!-- Bootstrap CSS -->

