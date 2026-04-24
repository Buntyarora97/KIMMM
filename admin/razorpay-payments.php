<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/config.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Razorpay Payments';
require_once 'views/layouts/header.php';
?>
<div class="card">
    <div class="card-header">
        <h2>Razorpay Payment History</h2>
    </div>
    <div class="card-body">
        <div class="alert alert-info">All successful payments processed via Razorpay are listed below.</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Order #</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="5" class="text-center">Razorpay integration is ready. History will appear after transactions.</td></tr>
            </tbody>
        </table>
    </div>
</div>
<?php require_once 'views/layouts/footer.php'; ?>