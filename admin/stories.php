<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/views/layouts/header.php';
require_once __DIR__ . '/../includes/models/Story.php';

$stories = Story::getAll();
?>


<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* Admin specific overrides for Bootstrap */
    .main-content { padding: 0 !important; }
    .container-fluid { padding-top: 2rem; }
    .card { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .btn-primary { background-color: #4A7C59; border-color: #4A7C59; }
    .btn-primary:hover { background-color: #3d664a; border-color: #3d664a; }
    .table thead th { background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; color: #495057; font-weight: 600; }
    .modal-header { background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; }
</style>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Manage Stories</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStoryModal">
            <i class="fas fa-plus me-2"></i>Add New Story
        </button>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Image</th>
                            <th>Name</th>
                            <th>Rating</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stories)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3 d-block"></i>
                                    No stories found. Click "Add New Story" to create one.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($stories as $s): ?>
                            <tr>
                                <td class="ps-4">
                                    <img src="../uploads/stories/<?php echo htmlspecialchars($s['image_path']); ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #eee;">
                                </td>
                                <td class="fw-bold"><?php echo htmlspecialchars($s['name']); ?></td>
                                <td>
                                    <div class="text-warning">
                                        <?php for($i=0; $i<$s['rating']; $i++): ?>
                                            <i class="fas fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                                <td style="max-width: 300px;">
                                    <div class="text-truncate" title="<?php echo htmlspecialchars($s['story_text']); ?>">
                                        <?php echo htmlspecialchars($s['story_text']); ?>
                                    </div>
                                </td>
                                <td><?php echo date('d M Y', strtotime($s['created_at'])); ?></td>
                                <td class="pe-4 text-end">
                                    <button class="btn btn-outline-danger btn-sm delete-story" data-id="<?php echo $s['id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Story Modal -->
<div class="modal fade" id="addStoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Customer Story</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addStoryForm" enctype="multipart/form-data">
                <input type="hidden" name="action" value="save_story">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Customer Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Rahul Sharma">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rating</label>
                        <select name="rating" class="form-select">
                            <option value="5">5 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="2">2 Stars</option>
                            <option value="1">1 Star</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Story / Review</label>
                        <textarea name="story_text" class="form-control" rows="4" required placeholder="What did the customer say?"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Customer Image</label>
                        <input type="file" name="story_image" class="form-control" accept="image/*" required>
                        <div class="form-text mt-2">Recommended: Square image (1:1 aspect ratio)</div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="saveBtn">Save Story</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('addStoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('saveBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    
   const formData = new FormData(this);
formData.append("action", "save_story"); // <<< YE LINE ADD KARO

fetch('ajax/admin_actions.php', {
    method: 'POST',
    body: formData
})

    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error saving story');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred. Please check console.');
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});

document.querySelectorAll('.delete-story').forEach(btn => {
    btn.addEventListener('click', function() {
        if (confirm('Are you sure you want to delete this story?')) {
            const id = this.dataset.id;

            const fd = new FormData();
            fd.append("action", "delete_story");
            fd.append("id", id);

            fetch('ajax/admin_actions.php', {
                method: 'POST',
                body: fd
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error deleting story');
                }
            });
        }
    });
});

</script>

<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>