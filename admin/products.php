<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/models/Product.php';
require_once __DIR__ . '/../includes/models/Category.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$currentPage = 'products';
$pageTitle = 'Manage Products';
$products = Product::getAll();
$categories = Category::getAll();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {

    $data = [
        'category_id' => $_POST['category_id'],
        'name' => $_POST['name'],
        'sku' => $_POST['sku'] ?? '',
        'price' => $_POST['price'],
        'mrp' => $_POST['mrp'],
        'stock_qty' => $_POST['stock_quantity'],
        'short_description' => $_POST['short_description'] ?? '',
        'long_description' => $_POST['long_description'] ?? '',
        'benefits' => $_POST['benefits'] ?? '',
        'usage_instructions' => $_POST['usage_instructions'] ?? '',
        'testing_info' => $_POST['testing_info'] ?? '',
        'reward_coins' => $_POST['reward_coins'] ?? 0,
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'is_active' => 1
    ];

    $product_id = Product::create($data);

    if ($product_id) {

        $dir = '../uploads/products/';
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        /* ===== MAIN IMAGE ===== */
        if (!empty($_FILES['image_upload']['name'])) {
            $img = time().'_'.$_FILES['image_upload']['name'];
            move_uploaded_file($_FILES['image_upload']['tmp_name'], $dir.$img);
            db()->prepare("UPDATE products SET image=? WHERE id=?")
                ->execute([$img, $product_id]);
        }

        /* ===== MAIN VIDEO ===== */
        if (!empty($_FILES['video_upload']['name'])) {
            $vid = time().'_'.$_FILES['video_upload']['name'];
            move_uploaded_file($_FILES['video_upload']['tmp_name'], $dir.$vid);
            db()->prepare("UPDATE products SET video=? WHERE id=?")
                ->execute([$vid, $product_id]);
        }

        /* ===== GALLERY (IMAGE + VIDEO) ===== */
        if (!empty($_FILES['gallery_media']['name'][0])) {
            foreach ($_FILES['gallery_media']['name'] as $i => $name) {
                if ($_FILES['gallery_media']['error'][$i] == 0) {
                    $file = time().'_'.$name;
                    move_uploaded_file($_FILES['gallery_media']['tmp_name'][$i], $dir.$file);

                    $type = (strpos($_FILES['gallery_media']['type'][$i], 'video') !== false)
                        ? 'video' : 'image';

                    db()->prepare("
                        INSERT INTO product_media (product_id, media_url, media_type)
                        VALUES (?, ?, ?)
                    ")->execute([$product_id, $file, $type]);
                }
            }
        }

        $success = 'Product added successfully';
    } else {
        $error = 'Failed to add product';
    }
}

require_once __DIR__ . '/views/layouts/header.php';
?>

<div class="admin-header">
    <h1>Manage Products</h1>
</div>

<?php if ($success): ?><div class="badge badge-success" style="margin-bottom: 20px; display: block;"><?php echo $success; ?></div><?php endif; ?>
<?php if ($error): ?><div class="badge badge-danger" style="margin-bottom: 20px; display: block;"><?php echo $error; ?></div><?php endif; ?>

<div class="admin-card">
    <h3>Add New Product</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="add_product" value="1">

        <div class="form-grid">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" class="form-control" placeholder="Product Name" required>
            </div>

            <div class="form-group">
                <label>SKU</label>
                <input type="text" name="sku" class="form-control" placeholder="SKU">
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>

            <div class="form-group">
                <label>MRP</label>
                <input type="number" step="0.01" name="mrp" class="form-control">
            </div>

            <div class="form-group">
                <label>Offer Price (₹)</label>
                <input type="number" step="0.01" name="offer_price" class="form-control">
            </div>

            <div class="form-group">
                <label>Offer Label</label>
                <input type="text" name="offer_label" class="form-control" placeholder="e.g. 10% OFF">
            </div>

            <div class="form-group">
                <label>Stock Quantity</label>
                <input type="number" name="stock_quantity" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Main Product Image</label>
                <input type="file" name="image_upload" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label>Main Product Video</label>
                <input type="file" name="video_upload" class="form-control" accept="video/*">
            </div>

            <div class="form-group">
                <label>Product Gallery (Multiple)</label>
                <input type="file" name="gallery_media[]" class="form-control" accept="image/*,video/*" multiple>
            </div>

            <div class="form-group">
                <label>Reward Coins</label>
                <input type="number" name="reward_coins" class="form-control" value="0">
            </div>

            <div class="form-group" style="justify-content:center;">
                <label style="display:flex;gap:10px;">
                    <input type="checkbox" name="is_featured"> Featured Product
                </label>
            </div>
        </div>

        <div class="form-group" style="margin-top:20px;">
            <label>Short Description</label>
            <textarea name="short_description" class="form-control"></textarea>
        </div>

        <div class="form-group" style="margin-top:20px;">
            <label>Long Description</label>
            <textarea name="long_description" class="form-control" style="height:100px;"></textarea>
        </div>

        <div class="form-group" style="margin-top:20px;">
            <label>Key Benefits (comma separated)</label>
            <textarea name="benefits" class="form-control"></textarea>
        </div>

        <div class="form-group" style="margin-top:20px;">
            <label>Usage Instructions</label>
            <textarea name="usage_instructions" class="form-control"></textarea>
        </div>

        <div class="form-group" style="margin-top:20px;">
            <label>Testing / Certification Info</label>
            <textarea name="testing_info" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top:20px;">
            Add Product
        </button>
    </form>
</div>


<div class="admin-card" style="padding: 0; overflow: hidden;">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($p['name']); ?></strong></td>
                <td><?php echo htmlspecialchars($p['category_name'] ?? 'Uncategorized'); ?></td>
                <td>₹<?php echo number_format($p['price'], 2); ?></td>
                <td><?php echo $p['stock_quantity']; ?></td>
                <td>
                    <span class="badge <?php echo $p['is_active'] ? 'badge-success' : 'badge-danger'; ?>">
                        <?php echo $p['is_active'] ? 'ACTIVE' : 'INACTIVE'; ?>
                    </span>
                </td>
                <td>
                    <a href="product-edit.php?id=<?php echo $p['id']; ?>" style="color: var(--primary-gold); text-decoration: none; font-weight: 600; margin-right: 15px;">EDIT</a>
                    <a href="product-edit.php?id=<?php echo $p['id']; ?>#gallery" style="color: #f57c00; text-decoration: none; font-weight: 600; margin-right: 15px;">MEDIA</a>
                    <a href="#" onclick="deleteProduct(<?php echo $p['id']; ?>)" style="color: #c62828; text-decoration: none; font-weight: 600;">DELETE</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        const formData = new FormData();
        formData.append('action', 'delete_product');
        formData.append('id', id);
        
        fetch('ajax/admin_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to delete product');
            }
        });
    }
}
</script>

<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>