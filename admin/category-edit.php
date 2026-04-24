<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/models/Category.php';

// ===================== AUTH =====================
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$pageTitle   = 'Edit Category';
$currentPage = 'categories';

$id = (int)($_GET['id'] ?? 0);
$category = Category::getById($id);

if (!$category) {
    header('Location: categories.php');
    exit;
}

$success = '';
$error   = '';

// ===================== UPDATE CATEGORY =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        // ---- BASIC DATA ----
        $data = [
            'name'        => $_POST['name'],
            'description' => $_POST['description'] ?? '',
            'sort_order'  => (int)($_POST['sort_order'] ?? 0),
            'is_active'   => (int)($_POST['is_active'] ?? 1),
            'icon'        => $_POST['icon_class'] ?? 'fa-leaf',
            'show_on_mobile_top_slider' => isset($_POST['show_on_mobile_top_slider']) ? 1 : 0,
            'show_on_mobile_concern'    => isset($_POST['show_on_mobile_concern']) ? 1 : 0,
            'show_on_desktop_concern'   => isset($_POST['show_on_desktop_concern']) ? 1 : 0,
            'show_in_top_menu'          => isset($_POST['show_in_top_menu']) ? 1 : 0
        ];

        $uploadDir = '../uploads/categories/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // ---- IMAGE UPLOAD ----
        if (!empty($_FILES['image_upload']['name'])) {
            $ext  = pathinfo($_FILES['image_upload']['name'], PATHINFO_EXTENSION);
            $file = uniqid('cat_img_', true) . '.' . $ext;
            move_uploaded_file($_FILES['image_upload']['tmp_name'], $uploadDir . $file);
            $data['image'] = $file;
        }

        // ---- VIDEO UPLOAD ----
        if (!empty($_FILES['video_upload']['name'])) {
            $ext  = pathinfo($_FILES['video_upload']['name'], PATHINFO_EXTENSION);
            $file = uniqid('cat_vid_', true) . '.' . $ext;
            move_uploaded_file($_FILES['video_upload']['tmp_name'], $uploadDir . $file);
            $data['video'] = $file;
        }

        // ---- ICON UPLOAD (OPTIONAL, OVERRIDES icon_class) ----
        if (!empty($_FILES['icon_upload']['name'])) {
            $ext  = pathinfo($_FILES['icon_upload']['name'], PATHINFO_EXTENSION);
            $file = uniqid('cat_icon_', true) . '.' . $ext;
            move_uploaded_file($_FILES['icon_upload']['tmp_name'], $uploadDir . $file);
            $data['icon_upload'] = $file;
        }

        Category::update($id, $data);

        $success  = 'Category updated successfully!';
        $category = Category::getById($id);

    } catch (Exception $e) {
        $error = 'Failed to update category: ' . $e->getMessage();
    }
}

require_once __DIR__ . '/views/layouts/header.php';
?>

<div class="admin-header">
    <h1>Edit Category: <?php echo htmlspecialchars($category['name']); ?></h1>
    <a href="categories.php" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<?php if ($success): ?>
    <div class="badge badge-success" style="margin-bottom:20px;"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="badge badge-danger" style="margin-bottom:20px;"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="admin-card">
    <form method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="name" class="form-control"
                       value="<?php echo htmlspecialchars($category['name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Icon Class (FontAwesome)</label>
                <input type="text" name="icon_class" class="form-control"
                       value="<?php echo htmlspecialchars($category['icon_class'] ?? $category['icon']); ?>">
            </div>

            <div class="form-group">
                <label>Category Image</label>
                <input type="file" name="image_upload" class="form-control" accept="image/*">
                <?php if (!empty($category['image'])): ?>
                    <div style="margin-top:10px;">
                        <img src="../uploads/categories/<?php echo htmlspecialchars($category['image']); ?>"
                             style="max-width:100px; border-radius: 5px; border: 1px solid #ddd;">
                        <p style="font-size: 0.75rem; color: #666; margin-top: 5px;">Current Image</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Category Icon (Upload)</label>
                <input type="file" name="icon_upload" class="form-control" accept="image/*,.svg">
                <?php if (!empty($category['icon_upload'])): ?>
                    <div style="margin-top:10px;">
                        <img src="../uploads/categories/<?php echo htmlspecialchars($category['icon_upload']); ?>"
                             style="max-width:50px; border-radius: 50%; border: 1px solid #ddd;">
                        <p style="font-size: 0.75rem; color: #666; margin-top: 5px;">Current Custom Icon</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Category Video</label>
                <input type="file" name="video_upload" class="form-control" accept="video/*">
                <?php if (!empty($category['video'])): ?>
                    <div style="margin-top:10px;">
                        <video width="200" controls style="border-radius: 5px;">
                            <source src="../uploads/categories/<?php echo htmlspecialchars($category['video']); ?>">
                        </video>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" class="form-control"
                       value="<?php echo (int)$category['sort_order']; ?>">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" <?php echo $category['is_active'] ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo !$category['is_active'] ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 15px;">
                <input type="checkbox" name="show_on_mobile_top_slider" id="show_on_mobile_top_slider" <?php echo ($category['show_on_mobile_top_slider'] ?? 0) ? 'checked' : ''; ?>>
                <label for="show_on_mobile_top_slider" style="margin: 0; font-weight: bold; color: #4A7C59;">Show in Mobile Top Slider (Kapiva Style)</label>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 15px;">
                <input type="checkbox" name="show_on_mobile_concern" id="show_on_mobile_concern" <?php echo ($category['show_on_mobile_concern'] ?? 1) ? 'checked' : ''; ?>>
                <label for="show_on_mobile_concern" style="margin: 0;">Show in Mobile "Select Concern"</label>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 15px;">
                <input type="checkbox" name="show_on_desktop_concern" id="show_on_desktop_concern" <?php echo ($category['show_on_desktop_concern'] ?? 1) ? 'checked' : ''; ?>>
                <label for="show_on_desktop_concern" style="margin: 0;">Show in Desktop "Select Concern"</label>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 15px;">
                <input type="checkbox" name="show_in_top_menu" id="show_in_top_menu" <?php echo ($category['show_in_top_menu'] ?? 1) ? 'checked' : ''; ?>>
                <label for="show_in_top_menu" style="margin: 0;">Show in Main Navigation Menu</label>
            </div>
        </div>

        <div class="form-group" style="margin-top:20px;">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"><?php
                echo htmlspecialchars($category['description']);
            ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top:20px;">
            <i class="fas fa-save"></i> Update Category
        </button>
    </form>
</div>

<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>
