<?php
$pageTitle = 'Video Popups';
require_once __DIR__ . '/views/layouts/header.php';

$db = db();
$popups = $db->query("SELECT * FROM video_popups ORDER BY created_at DESC")->fetchAll();
?>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Manage Video Popups</h2>
        <button class="btn btn-primary" onclick="document.getElementById('addPopupModal').style.display='block'">Add New Popup</button>
    </div>
    <div class="card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Video</th>
                    <th>Buy Link</th>
                    <th>Views</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($popups as $p): ?>
                <tr>
                    <td>
                        <video width="100" muted>
                            <source src="../<?php echo $p['video_url']; ?>" type="video/mp4">
                        </video>
                    </td>
                    <td><?php echo htmlspecialchars($p['buy_link']); ?></td>
                    <td><?php echo $p['view_count']; ?></td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" <?php echo $p['is_active'] ? 'checked' : ''; ?> onchange="toggleStatus(<?php echo $p['id']; ?>, this.checked)">
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="deletePopup(<?php echo $p['id']; ?>)">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="addPopupModal" class="modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
    <div class="modal-content" style="background:#fff; margin:10% auto; padding:20px; width:50%; border-radius:8px;">
        <h3>Add Video Popup</h3>
        <form action="ajax/popup_actions.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_popup">
            <div class="form-group" style="margin-bottom:15px;">
                <label>Video File</label>
                <input type="file" name="video" accept="video/*" required class="form-control" style="width:100%; padding:8px;">
            </div>
            <div class="form-group" style="margin-bottom:15px;">
                <label>Buy Now Link (Product URL)</label>
                <input type="text" name="buy_link" class="form-control" placeholder="https://..." style="width:100%; padding:8px;">
            </div>
            <div class="form-group" style="margin-bottom:15px;">
                <label>
                    <input type="checkbox" name="is_active" checked> Active
                </label>
            </div>
            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-primary">Save Popup</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addPopupModal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleStatus(id, status) {
    fetch('ajax/popup_actions.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=toggle_status&id=${id}&status=${status ? 1 : 0}`
    });
}

function deletePopup(id) {
    if (confirm('Are you sure?')) {
        fetch('ajax/popup_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=delete_popup&id=${id}`
        }).then(() => location.reload());
    }
}
</script>

<style>
.switch { position: relative; display: inline-block; width: 40px; height: 20px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 20px; }
.slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 2px; bottom: 2px; background-color: white; transition: .4s; border-radius: 50%; }
input:checked + .slider { background-color: #76a33a; }
input:checked + .slider:before { transform: translateX(20px); }
</style>

<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>
