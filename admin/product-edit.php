<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/models/Product.php';
require_once '../includes/models/Category.php';
require_once '../includes/models/ProductImage.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Edit Product';
$currentPage = 'products';

$id = $_GET['id'] ?? 0;
$product = Product::getById($id);

if (!$product) {
    header('Location: products.php');
    exit;
}

$categories = Category::getAll(true);
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = $_POST;
        $data['is_featured'] = isset($_POST['is_featured']) ? (int)$_POST['is_featured'] : 0;
        $data['is_active']   = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;
        $data['price']       = (float)($_POST['price'] ?? 0);
        $data['mrp']         = (float)($_POST['mrp'] ?? $data['price']);
        $data['stock_qty']   = (int)($_POST['stock_qty'] ?? 0);
        $data['stock_status'] = $_POST['stock_status'] ?? 'in_stock';
        $data['category_id'] = (int)($_POST['category_id'] ?? 0);
        
        // 🚀 SHIPROCKET DIMENSIONS - Capture from form
        $data['weight_kg'] = (float)($_POST['weight_kg'] ?? 0.5);
        $data['length_cm'] = (float)($_POST['length_cm'] ?? 15);
        $data['width_cm'] = (float)($_POST['width_cm'] ?? 10);
        $data['height_cm'] = (float)($_POST['height_cm'] ?? 8);

        // 🔥 AUTO STOCK CONTROL LOGIC
        if ($data['stock_status'] === 'out_of_stock') {
            $data['stock_qty'] = 0;
        }

        if ($data['stock_qty'] <= 0) {
            $data['stock_status'] = 'out_of_stock';
        } else {
            $data['stock_status'] = 'in_stock';
        }

        $uploadDir = '../uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!empty($_FILES['image']['name'])) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $img = uniqid('img_', true) . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $img);
            $data['image'] = $img;
        }

        if (!empty($_FILES['video']['name'])) {
            $ext = pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
            $vid = uniqid('vid_', true) . '.' . $ext;
            move_uploaded_file($_FILES['video']['tmp_name'], $uploadDir . $vid);
            $data['video'] = $vid;
        }

        Product::update($id, $data);

        if (!empty($_FILES['gallery_media']['name'][0])) {
            foreach ($_FILES['gallery_media']['name'] as $key => $name) {
                if ($_FILES['gallery_media']['error'][$key] !== 0) continue;
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $file = uniqid('gallery_', true) . '.' . $ext;
                move_uploaded_file($_FILES['gallery_media']['tmp_name'][$key], $uploadDir . $file);
                $type = (strpos($_FILES['gallery_media']['type'][$key], 'video') !== false) ? 'video' : 'image';
                db()->prepare("INSERT INTO product_media (product_id, media_url, media_type) VALUES (?, ?, ?)")->execute([$id, $file, $type]);
            }
        }

        $success = 'Product updated successfully!';
        $product = Product::getById($id);
    } catch (Exception $e) {
        $error = 'Failed to update product: ' . $e->getMessage();
    }
}

if (isset($_GET['delete_media'])) {
    try {
        $mediaId = (int)$_GET['delete_media'];
        db()->prepare("DELETE FROM product_media WHERE id = ?")->execute([$mediaId]);
        $success = 'Media deleted successfully!';
    } catch (Exception $e) {
        $error = 'Failed to delete media: ' . $e->getMessage();
    }
}

require_once 'views/layouts/header.php';
?>

<?php if ($success): ?>
<div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Edit Product: <?php echo htmlspecialchars($product['name']); ?></h2>
        <a href="products.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Product Name *</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="sku">SKU</label>
                    <input type="text" id="sku" name="sku" class="form-control" value="<?php echo htmlspecialchars($product['sku'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="category_id">Category *</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $product['category_id'] == $cat['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="stock_qty">Stock Quantity</label>
                    <input type="number" id="stock_qty" name="stock_qty" class="form-control" value="<?php echo $product['stock_qty']; ?>">
                </div>
                <div class="form-group">
                    <label>Stock Status</label>
                    <select name="stock_status" class="form-control">
                        <option value="in_stock" <?php echo ($product['stock_status'] ?? 'in_stock') == 'in_stock' ? 'selected' : ''; ?>>In Stock</option>
                        <option value="out_of_stock" <?php echo ($product['stock_status'] ?? 'in_stock') == 'out_of_stock' ? 'selected' : ''; ?>>Out of Stock</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="price">Selling Price (₹) *</label>
                    <input type="number" id="price" name="price" class="form-control" step="0.01" value="<?php echo $product['price']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="mrp">MRP (₹)</label>
                    <input type="number" id="mrp" name="mrp" class="form-control" step="0.01" value="<?php echo $product['mrp']; ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="offer_price">Offer Price (₹)</label>
                    <input type="number" id="offer_price" name="offer_price" class="form-control" step="0.01" value="<?php echo $product['offer_price'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="offer_label">Offer Label (e.g. 20% OFF)</label>
                    <input type="text" id="offer_label" name="offer_label" class="form-control" value="<?php echo htmlspecialchars($product['offer_label'] ?? ''); ?>">
                </div>
            </div>
            
            <!-- 🚀 SHIPROCKET DIMENSIONS SECTION -->
            <div style="border: 2px solid #007bff; background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 10px;">
                <h4 style="color: #007bff; margin-top: 0;">
                    <i class="fas fa-shipping-fast"></i> Shiprocket Shipping Dimensions
                </h4>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="weight_kg">Weight (kg) *</label>
                        <input type="number" id="weight_kg" name="weight_kg" class="form-control" step="0.001" min="0.001" 
                               value="<?php echo $product['weight_kg'] ?? 0.5; ?>" required>
                        <small style="color: #666;">Example: 0.5 for 500g, 1.2 for 1.2kg</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="length_cm">Length (cm) *</label>
                        <input type="number" id="length_cm" name="length_cm" class="form-control" step="0.1" min="1" 
                               value="<?php echo $product['length_cm'] ?? 15; ?>" required>
                        <small style="color: #666;">Package length in cm</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="width_cm">Width (cm) *</label>
                        <input type="number" id="width_cm" name="width_cm" class="form-control" step="0.1" min="1" 
                               value="<?php echo $product['width_cm'] ?? 10; ?>" required>
                        <small style="color: #666;">Package width in cm</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="height_cm">Height (cm) *</label>
                        <input type="number" id="height_cm" name="height_cm" class="form-control" step="0.1" min="1" 
                               value="<?php echo $product['height_cm'] ?? 8; ?>" required>
                        <small style="color: #666;">Package height in cm</small>
                    </div>
                </div>
                
                <!-- Volumetric Weight Display -->
                <div style="background: #e9ecef; padding: 15px; border-radius: 8px; margin-top: 15px;">
                    <h5 style="margin-top: 0; color: #495057;">📦 Shipping Weight Calculation</h5>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <div>
                            <strong>Dead Weight:</strong> <span id="display_weight"><?php echo $product['weight_kg'] ?? 0.5; ?></span> kg
                        </div>
                        <div>
                            <strong>Volumetric Weight:</strong> <span id="display_volumetric">
                                <?php 
                                $l = $product['length_cm'] ?? 15;
                                $w = $product['width_cm'] ?? 10;
                                $h = $product['height_cm'] ?? 8;
                                echo round(($l * $w * $h) / 5000, 3);
                                ?>
                            </span> kg
                        </div>
                        <div style="color: #007bff; font-weight: bold;">
                            <strong>Chargeable Weight:</strong> <span id="display_chargeable">
                                <?php 
                                $dead = $product['weight_kg'] ?? 0.5;
                                $vol = ($l * $w * $h) / 5000;
                                echo round(max($dead, $vol), 3);
                                ?>
                            </span> kg
                        </div>
                    </div>
                    <small style="color: #6c757d; display: block; margin-top: 10px;">
                        * Shiprocket charges based on whichever is higher: actual weight or volumetric weight (L×W×H/5000)
                    </small>
                </div>
            </div>
            
            <div class="form-group">
                <label for="short_description">Short Description</label>
                <textarea id="short_description" name="short_description" class="form-control" rows="2"><?php echo htmlspecialchars($product['short_description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="long_description">Long Description</label>
                <textarea id="long_description" name="long_description" class="form-control" rows="5"><?php echo htmlspecialchars($product['long_description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="benefits">Benefits (comma separated)</label>
                <input type="text" id="benefits" name="benefits" class="form-control" value="<?php echo htmlspecialchars($product['benefits'] ?? ''); ?>">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Main Product Image</label>
                    <?php if ($product['image']): ?>
                    <div style="margin-bottom: 10px;">
                        <img src="../uploads/products/<?php echo htmlspecialchars($product['image']); ?>" alt="" style="max-height: 100px; border-radius: 8px;">
                    </div>
                    <?php endif; ?>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Main Product Video</label>
                    <?php if (isset($product['video']) && $product['video']): ?>
                    <div style="margin-bottom: 10px;">
                        <video style="max-height: 100px; border-radius: 8px;" muted controls><source src="../uploads/products/<?php echo htmlspecialchars($product['video']); ?>"></video>
                    </div>
                    <?php endif; ?>
                    <input type="file" id="video" name="video" class="form-control" accept="video/*">
                </div>
            </div>

            <div class="form-group">
                <label>Product Gallery (Multiple Images & Videos)</label>
                <?php 
                $stmt = db()->prepare("SELECT * FROM product_media WHERE product_id = ? ORDER BY sort_order ASC");
                $stmt->execute([$id]);
                $galleryMedia = $stmt->fetchAll();
                if (!empty($galleryMedia)): ?>
                <div style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 8px;">
                    <h4 style="margin-top: 0;">Current Gallery</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px;">
                        <?php foreach ($galleryMedia as $media): ?>
                        <div style="position: relative; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                            <?php if ($media['media_type'] === 'video'): ?>
                                <video style="width: 100%; height: 150px; object-fit: cover;" muted><source src="../uploads/products/<?php echo htmlspecialchars($media['media_url']); ?>"></video>
                            <?php else: ?>
                                <img src="../uploads/products/<?php echo htmlspecialchars($media['media_url']); ?>" alt="" style="width: 100%; height: 150px; object-fit: cover;">
                            <?php endif; ?>
                            <a href="?id=<?php echo $id; ?>&delete_media=<?php echo $media['id']; ?>" class="btn btn-danger btn-sm" style="position: absolute; top: 5px; right: 5px;" onclick="return confirm('Delete this media?');"><i class="fas fa-trash"></i></a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <input type="file" name="gallery_media[]" class="form-control" accept="image/*,video/*" multiple>
                <small style="color: #666;">Select multiple images or videos to add to gallery</small>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="is_featured">Featured Product</label>
                    <select id="is_featured" name="is_featured" class="form-control">
                        <option value="0" <?php echo !$product['is_featured'] ? 'selected' : ''; ?>>No</option>
                        <option value="1" <?php echo $product['is_featured'] ? 'selected' : ''; ?>>Yes</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="is_active">Status</label>
                    <select id="is_active" name="is_active" class="form-control">
                        <option value="1" <?php echo $product['is_active'] ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?php echo !$product['is_active'] ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Product</button>
        </form>
    </div>
</div>

<!-- 🚀 JavaScript for Real-time Volumetric Weight Calculation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const weightInput = document.getElementById('weight_kg');
    const lengthInput = document.getElementById('length_cm');
    const widthInput = document.getElementById('width_cm');
    const heightInput = document.getElementById('height_cm');
    
    const displayWeight = document.getElementById('display_weight');
    const displayVolumetric = document.getElementById('display_volumetric');
    const displayChargeable = document.getElementById('display_chargeable');
    
    function calculateShippingWeight() {
        const weight = parseFloat(weightInput.value) || 0;
        const length = parseFloat(lengthInput.value) || 0;
        const width = parseFloat(widthInput.value) || 0;
        const height = parseFloat(heightInput.value) || 0;
        
        // Volumetric weight formula: (L × W × H) / 5000
        const volumetric = (length * width * height) / 5000;
        const chargeable = Math.max(weight, volumetric);
        
        displayWeight.textContent = weight.toFixed(3);
        displayVolumetric.textContent = volumetric.toFixed(3);
        displayChargeable.textContent = chargeable.toFixed(3);
    }
    
    // Add event listeners
    [weightInput, lengthInput, widthInput, heightInput].forEach(input => {
        input.addEventListener('input', calculateShippingWeight);
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>