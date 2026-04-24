<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/models/Review.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Product Reviews';
$currentPage = 'reviews';
try {
    $reviews = Review::getAll();
} catch (Exception $e) {
    $reviews = [];
}

require_once __DIR__ . '/views/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Customer Reviews</h2>
    </div>
    <div class="card-body">
        <?php if (empty($reviews)): ?>
        <p class="text-center">No reviews found.</p>
        <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                <tr>
                    <td><?php echo htmlspecialchars($review['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($review['user_name']); ?></td>
                    <td>
                        <div class="rating-stars">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($review['comment']); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $review['status'] === 'approved' ? 'success' : 'warning'; ?>">
                            <?php echo ucfirst($review['status']); ?>
                        </span>
                    </td>
                    <td><?php echo date('d M Y', strtotime($review['created_at'])); ?></td>
                    <td>
                        <button class="btn btn-sm btn-info">Approve</button>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>