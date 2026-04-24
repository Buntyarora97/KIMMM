<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/models/Product.php';
require_once '../includes/models/Category.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Add Product';
$currentPage = 'products';
$categories = Category::getAll(true);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = [
            'name' => $_POST['name'],
            'sku' => $_POST['sku'] ?? '',
            'category_id' => $_POST['category_id'],
            'price' => $_POST['price'],
            'mrp' => $_POST['mrp'] ?? null,
            'offer_price' => $_POST['offer_price'] ?? null,
            'offer_label' => $_POST['offer_label'] ?? null,
            'stock_qty' => $_POST['stock_qty'] ?? 0,
            'stock_status' => $_POST['stock_status'] ?? 'in_stock',
            'short_description' => $_POST['short_description'] ?? '',
            'long_description' => $_POST['long_description'] ?? '',
            'benefits' => $_POST['benefits'] ?? '',
            'ingredients' => $_POST['ingredients'] ?? '',
            'usage_instructions' => $_POST['usage_instructions'] ?? '',
            'testing_info' => $_POST['testing_info'] ?? '',
            'reward_coins' => $_POST['reward_coins'] ?? 0,
            'is_featured' => $_POST['is_featured'] ?? 0,
            'is_active' => $_POST['is_active'] ?? 1,
            'image' => '',
            'video' => ''
        ];

        if (!empty($_FILES['image']['name'])) {
            $dir = '../uploads/products/';
            if (!is_dir($dir)) mkdir($dir, 0777, true);
            $file = time().'_'.basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $dir.$file);
            $data['image'] = $file;
        }

        if (!empty($_FILES['video']['name'])) {
            $dir = '../uploads/products/';
            if (!is_dir($dir)) mkdir($dir, 0777, true);
            $file = time().'_'.basename($_FILES['video']['name']);
            move_uploaded_file($_FILES['video']['tmp_name'], $dir.$file);
            $data['video'] = $file;
        }

        Product::create($data);
        header('Location: products.php?success=1');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
require_once 'views/layouts/header.php';
?>

<?php if ($error): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Add New Product</h2>
        <a href="products.php" class="btn btn-secondary btn-sm">Back</a>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label>Product Name *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>SKU</label>
                    <input type="text" name="sku" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock_qty" class="form-control" value="100">
                </div>
                <div class="form-group">
                    <label>Stock Status</label>
                    <select name="stock_status" class="form-control">
                        <option value="in_stock">In Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Selling Price (₹)</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>MRP (₹)</label>
                    <input type="number" name="mrp" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Offer Price</label>
                    <input type="number" name="offer_price" class="form-control">
                </div>
                <div class="form-group">
                    <label>Offer Label</label>
                    <input type="text" name="offer_label" class="form-control" placeholder="10% OFF">
                </div>
            </div>
            <div class="form-group">
                <label>Reward Coins</label>
                <input type="number" name="reward_coins" class="form-control">
            </div>
            <div class="form-group">
                <label>Short Description</label>
                <textarea name="short_description" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>Long Description</label>
                <textarea name="long_description" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>Benefits</label>
                <textarea name="benefits" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>Ingredients</label>
                <textarea name="ingredients" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>Usage Instructions</label>
                <textarea name="usage_instructions" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>Testing Info</label>
                <textarea name="testing_info" class="form-control"></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Product Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="form-group">
                    <label>Product Video</label>
                    <input type="file" name="video" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Featured</label>
                    <select name="is_featured" class="form-control">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>
</div>
<?php require_once 'views/layouts/footer.php'; ?>
