<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/models/Hero.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Hero Management';
require_once 'views/layouts/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    
    // Handle Hero Image Upload
    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] == 0) {
        $upload_dir = '../uploads/banners/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $filename = time() . '_' . $_FILES['hero_image']['name'];
        move_uploaded_file($_FILES['hero_image']['tmp_name'], $upload_dir . $filename);
        $data['media_url'] = $filename;
    }

    // Handle Hero Video Upload
    if (isset($_FILES['hero_video']) && $_FILES['hero_video']['error'] == 0) {
        $upload_dir = '../uploads/banners/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $filename = time() . '_' . $_FILES['hero_video']['name'];
        move_uploaded_file($_FILES['hero_video']['tmp_name'], $upload_dir . $filename);
        $data['video_url'] = $filename;
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        Hero::update((int)$_POST['id'], $data);
    } else {
        Hero::create($data);
    }
    $message = "Hero slide saved successfully!";
}

$heroSlides = Hero::getAll();
?>
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h2>Hero Section Management</h2>
        <button class="btn btn-primary" onclick="document.getElementById('addHeroForm').style.display='block'">Add New Slide</button>
    </div>
    <div class="card-body">
        <?php if(isset($message)): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>
        
        <div id="addHeroForm" style="display:none; margin-bottom: 30px; background: #f8f9fa; padding: 20px; border-radius: 10px;">
            <form method="POST" enctype="multipart/form-data">
                <div class="grid" style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <input type="text" name="badge" placeholder="Badge (e.g. 100% Natural)" class="form-control">
                    <input type="text" name="title" placeholder="Title" class="form-control" required>
                    <textarea name="subtitle" placeholder="Subtitle" class="form-control" style="grid-column: span 2"></textarea>
                    
                    <div class="form-group">
                        <label>Media Type</label>
                        <select name="media_type" class="form-control" onchange="toggleHeroMedia(this.value)">
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="heroImageUpload">
                        <label>Upload Image</label>
                        <input type="file" name="hero_image" class="form-control" accept="image/*">
                    </div>
                    
                    <div class="form-group" id="heroVideoUpload" style="display:none;">
                        <label>Upload Video</label>
                        <input type="file" name="hero_video" class="form-control" accept="video/*">
                    </div>

                    <input type="text" name="button_text" value="SHOP NOW" class="form-control">
                    <input type="text" name="button_link" value="products.php" class="form-control">
                </div>
                <button type="submit" class="btn btn-success" style="margin-top: 20px;">Save Slide</button>
            </form>
        </div>

        <script>
        function toggleHeroMedia(type) {
            document.getElementById('heroImageUpload').style.display = type === 'image' ? 'block' : 'none';
            document.getElementById('heroVideoUpload').style.display = type === 'video' ? 'block' : 'none';
        }
        </script>

        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Badge</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($heroSlides as $slide): ?>
                <tr>
                    <td><?php echo $slide['title']; ?></td>
                    <td><?php echo $slide['badge']; ?></td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-info">Edit</button>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once 'views/layouts/footer.php'; ?>