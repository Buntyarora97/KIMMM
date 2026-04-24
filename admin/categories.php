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

$pageTitle = 'Categories';
$currentPage = 'categories';
$success = '';
$error   = '';

// ===================== DELETE CATEGORY =====================
if (isset($_GET['delete'])) {
    try {
        Category::delete((int)$_GET['delete']);
        header('Location: categories.php?success=deleted');
        exit;
    } catch (Exception $e) {
        $error = 'Delete failed: ' . $e->getMessage();
    }
}

// ===================== ADD CATEGORY =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
    try {

        // ---- BASIC DATA ----
        $data = [
            'name'        => $_POST['name'],
            'description' => $_POST['description'] ?? '',
            'sort_order'  => (int)($_POST['sort_order'] ?? 0),
            'is_active'   => 1,
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

        Category::create($data);
        header('Location: categories.php?success=created');
        exit;

    } catch (Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

// ===================== FETCH LIST =====================
$categories = Category::getAll(false);

require_once __DIR__ . '/views/layouts/header.php';
?>

<div class="admin-header">
    <h1>Manage Categories</h1>
</div>

<?php if (isset($_GET['success']) && $_GET['success'] === 'created'): ?>
    <div class="badge badge-success" style="margin-bottom:20px;">Category created successfully</div>
<?php endif; ?>

<?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
    <div class="badge badge-success" style="margin-bottom:20px;">Category deleted successfully</div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="badge badge-danger" style="margin-bottom:20px;"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<!-- ===================== ADD FORM ===================== -->
<div class="admin-card">
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create">

        <div class="form-grid">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Icon Class (FontAwesome)</label>
                <input type="text" name="icon_class" class="form-control" value="fa-leaf">
            </div>

            <div class="form-group">
                <label>Category Image</label>
                <input type="file" name="image_upload" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label>Category Video</label>
                <input type="file" name="video_upload" class="form-control" accept="video/*">
            </div>

            <div class="form-group">
                <label>Category Icon (Upload)</label>
                <input type="file" name="icon_upload" class="form-control" accept="image/*,.svg">
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 15px;">
                <input type="checkbox" name="show_on_mobile_top_slider" id="show_on_mobile_top_slider">
                <label for="show_on_mobile_top_slider" style="margin: 0; font-weight: bold; color: #4A7C59;">Show in Mobile Top Slider (Kapiva Style)</label>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 15px;">
                <input type="checkbox" name="show_on_mobile_concern" id="show_on_mobile_concern" checked>
                <label for="show_on_mobile_concern" style="margin: 0;">Show in Mobile "Select Concern"</label>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 15px;">
                <input type="checkbox" name="show_on_desktop_concern" id="show_on_desktop_concern" checked>
                <label for="show_on_desktop_concern" style="margin: 0;">Show in Desktop "Select Concern"</label>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 15px;">
                <input type="checkbox" name="show_in_top_menu" id="show_in_top_menu" checked>
                <label for="show_in_top_menu" style="margin: 0;">Show in Main Navigation Menu</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top:20px;">
            Add Category
        </button>
    </form>
</div>

<!-- ===================== LIST ===================== -->
<div class="admin-card" style="padding:0; overflow:hidden;">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Icon</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?php echo htmlspecialchars($cat['name']); ?></td>
                <td><?php echo htmlspecialchars($cat['slug']); ?></td>
                <td>
                    <?php if (!empty($cat['icon_upload'])): ?>
                        <img src="../uploads/categories/<?php echo htmlspecialchars($cat['icon_upload']); ?>" width="30" height="30" style="object-fit: contain; border-radius: 50%;">
                    <?php else: ?>
                        <i class="fa <?php echo htmlspecialchars($cat['icon_class'] ?? 'fa-leaf'); ?>"></i>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="category-edit.php?id=<?php echo $cat['id']; ?>" style="margin-right:10px;">EDIT</a>
                    <a href="categories.php?delete=<?php echo $cat['id']; ?>"
                       style="color:#c62828;"
                       onclick="return confirm('Delete this category?');">
                        DELETE
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>
