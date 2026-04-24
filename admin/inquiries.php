<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/models/ContactInquiry.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Contact Inquiries';
$currentPage = 'inquiries';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'])) {
        try {
            ContactInquiry::updateStatus($_POST['inquiry_id'], $_POST['status'], $_SESSION['admin_id']);
            $success = 'Status updated successfully!';
        } catch (Exception $e) {
            $error = 'Failed to update status: ' . $e->getMessage();
        }
    } elseif (isset($_POST['delete'])) {
        try {
            ContactInquiry::delete($_POST['inquiry_id']);
            $success = 'Inquiry deleted successfully!';
        } catch (Exception $e) {
            $error = 'Failed to delete inquiry: ' . $e->getMessage();
        }
    }
}

$statusFilter = $_GET['status'] ?? null;
$inquiries = ContactInquiry::getAll($statusFilter);

require_once __DIR__ . '/views/layouts/header.php';
?>

<?php if ($success): ?>
<div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Contact Inquiries (<?php echo count($inquiries); ?>)</h2>
        <div style="display: flex; gap: 10px;">
            <a href="inquiries.php" class="btn btn-<?php echo !$statusFilter ? 'primary' : 'secondary'; ?> btn-sm">All</a>
            <a href="inquiries.php?status=new" class="btn btn-<?php echo $statusFilter === 'new' ? 'primary' : 'secondary'; ?> btn-sm">New</a>
            <a href="inquiries.php?status=in_progress" class="btn btn-<?php echo $statusFilter === 'in_progress' ? 'primary' : 'secondary'; ?> btn-sm">In Progress</a>
            <a href="inquiries.php?status=resolved" class="btn btn-<?php echo $statusFilter === 'resolved' ? 'primary' : 'secondary'; ?> btn-sm">Resolved</a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($inquiries)): ?>
        <div class="empty-state">
            <i class="fas fa-envelope"></i>
            <p>No inquiries found.</p>
        </div>
        <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inquiries as $inquiry): ?>
                <tr>
                    <td>#<?php echo $inquiry['id']; ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($inquiry['name']); ?></strong>
                        <?php if ($inquiry['phone']): ?>
                        <br><small><?php echo htmlspecialchars($inquiry['phone']); ?></small>
                        <?php endif; ?>
                    </td>
                    <td><a href="mailto:<?php echo htmlspecialchars($inquiry['email']); ?>"><?php echo htmlspecialchars($inquiry['email']); ?></a></td>
                    <td><?php echo htmlspecialchars($inquiry['subject'] ?: 'No Subject'); ?></td>
                    <td>
                        <form method="POST" action="" style="display: inline;">
                            <input type="hidden" name="inquiry_id" value="<?php echo $inquiry['id']; ?>">
                            <input type="hidden" name="update_status" value="1">
                            <select name="status" class="status-select" onchange="this.form.submit()">
                                <option value="new" <?php echo $inquiry['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                                <option value="in_progress" <?php echo $inquiry['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                <option value="resolved" <?php echo $inquiry['status'] === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                <option value="closed" <?php echo $inquiry['status'] === 'closed' ? 'selected' : ''; ?>>Closed</option>
                            </select>
                        </form>
                    </td>
                    <td><?php echo date('d M Y', strtotime($inquiry['created_at'])); ?></td>
                    <td class="actions-cell">
                        <a href="inquiry-view.php?id=<?php echo $inquiry['id']; ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form method="POST" action="" style="display: inline;" onsubmit="return confirmDelete('Delete this inquiry?')">
                            <input type="hidden" name="inquiry_id" value="<?php echo $inquiry['id']; ?>">
                            <input type="hidden" name="delete" value="1">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
