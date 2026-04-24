<?php
session_start();
require_once '../includes/database.php';
require_once '../includes/models/ContactInquiry.php';

$pageTitle = 'View Inquiry';
$currentPage = 'inquiries';

$id = $_GET['id'] ?? 0;
$inquiry = ContactInquiry::getById($id);

if (!$inquiry) {
    header('Location: inquiries.php');
    exit;
}

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    ContactInquiry::updateStatus($id, $_POST['status'], $_SESSION['admin_id']);
    $success = 'Status updated successfully!';
    $inquiry = ContactInquiry::getById($id);
}

require_once 'views/layouts/header.php';
?>

<?php if ($success): ?>
<div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Inquiry #<?php echo $inquiry['id']; ?></h2>
        <a href="inquiries.php" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <div class="order-details">
            <div class="order-info-card">
                <h4><i class="fas fa-user"></i> Contact Information</h4>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($inquiry['name']); ?></p>
                <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($inquiry['email']); ?>"><?php echo htmlspecialchars($inquiry['email']); ?></a></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($inquiry['phone'] ?: 'N/A'); ?></p>
                <p><strong>Date:</strong> <?php echo date('d M Y, h:i A', strtotime($inquiry['created_at'])); ?></p>
            </div>
            
            <div class="order-info-card">
                <h4><i class="fas fa-info-circle"></i> Status</h4>
                <form method="POST" action="">
                    <input type="hidden" name="update_status" value="1">
                    <select name="status" class="form-control" style="margin-bottom: 10px;">
                        <option value="new" <?php echo $inquiry['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                        <option value="in_progress" <?php echo $inquiry['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="resolved" <?php echo $inquiry['status'] === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                        <option value="closed" <?php echo $inquiry['status'] === 'closed' ? 'selected' : ''; ?>>Closed</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </form>
                <?php if ($inquiry['handled_by_name']): ?>
                <p style="margin-top: 10px;"><strong>Handled by:</strong> <?php echo htmlspecialchars($inquiry['handled_by_name']); ?></p>
                <p><strong>Handled at:</strong> <?php echo date('d M Y, h:i A', strtotime($inquiry['handled_at'])); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div style="margin-top: 30px;">
            <h4><i class="fas fa-envelope"></i> Message</h4>
            <div class="order-info-card">
                <p><strong>Subject:</strong> <?php echo htmlspecialchars($inquiry['subject'] ?: 'No Subject'); ?></p>
                <hr style="margin: 15px 0;">
                <p style="white-space: pre-wrap;"><?php echo htmlspecialchars($inquiry['message']); ?></p>
            </div>
        </div>
        
        <div style="margin-top: 20px;">
            <a href="mailto:<?php echo htmlspecialchars($inquiry['email']); ?>?subject=Re: <?php echo htmlspecialchars($inquiry['subject'] ?: 'Your Inquiry'); ?>" class="btn btn-primary">
                <i class="fas fa-reply"></i> Reply via Email
            </a>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
