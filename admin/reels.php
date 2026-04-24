<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }

require_once '../includes/config.php';
require_once '../includes/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Video Reels';
$currentPage = 'reels';

$error = '';
$editReel = null;

/* ==============================
   DELETE REEL
============================== */
if (isset($_GET['delete_id'])) {

    $deleteId = (int)$_GET['delete_id'];

    $stmt = db()->prepare("SELECT video FROM reels WHERE id = ?");
    $stmt->execute([$deleteId]);
    $reel = $stmt->fetch();

    if ($reel) {
        $filePath = '../uploads/reels/' . $reel['video'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        db()->prepare("DELETE FROM reels WHERE id = ?")->execute([$deleteId]);
    }

    header("Location: reels.php");
    exit;
}

/* ==============================
   EDIT MODE LOAD
============================== */
if (isset($_GET['edit_id'])) {
    $editId = (int)$_GET['edit_id'];
    $stmt = db()->prepare("SELECT * FROM reels WHERE id = ?");
    $stmt->execute([$editId]);
    $editReel = $stmt->fetch();
}

/* ==============================
   ADD / UPDATE REEL
============================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $uploadDir = '../uploads/reels/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $videoFile = $_POST['existing_video'] ?? '';

        // If new video uploaded
        if (!empty($_FILES['video']['name'])) {

            $ext = pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
            $videoFile = uniqid('reel_', true) . '.' . $ext;
            move_uploaded_file($_FILES['video']['tmp_name'], $uploadDir . $videoFile);

            // delete old file if editing
            if (!empty($_POST['existing_video'])) {
                $oldPath = $uploadDir . $_POST['existing_video'];
                if (file_exists($oldPath)) unlink($oldPath);
            }
        }

        if (!empty($_POST['reel_id'])) {
            // UPDATE
            db()->prepare("UPDATE reels SET 
                title = ?, 
                video = ?, 
                buy_link = ?, 
                product_id = ?, 
                is_active = ?
                WHERE id = ?")
            ->execute([
                $_POST['title'],
                $videoFile,
                $_POST['buy_link'],
                $_POST['product_id'],
                isset($_POST['is_active']) ? 1 : 0,
                $_POST['reel_id']
            ]);

        } else {
            // INSERT
            db()->prepare("INSERT INTO reels 
                (title, video, buy_link, product_id, is_active)
                VALUES (?, ?, ?, ?, ?)")
            ->execute([
                $_POST['title'],
                $videoFile,
                $_POST['buy_link'],
                $_POST['product_id'],
                isset($_POST['is_active']) ? 1 : 0
            ]);
        }

        header("Location: reels.php");
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$reels = db()->query("
SELECT r.*, p.name as product_name 
FROM reels r 
LEFT JOIN products p ON r.product_id = p.id
ORDER BY r.id DESC
")->fetchAll();

$products = db()->query("SELECT id,name FROM products WHERE is_active=1 ORDER BY id DESC")->fetchAll();

require_once 'views/layouts/header.php';
?>

<?php if ($error): ?>
<div style="background:#f8d7da;padding:10px;border-radius:6px;color:#721c24;margin-bottom:15px;">
    <?php echo htmlspecialchars($error); ?>
</div>
<?php endif; ?>


<!-- ==============================
     ADD / EDIT REEL FORM
============================== -->
<div style="background:#fff;padding:20px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:25px;">
    <h2><?php echo $editReel ? 'Edit Reel' : 'Add New Reel'; ?></h2>

    <form method="POST" enctype="multipart/form-data">

        <input type="hidden" name="reel_id" value="<?php echo $editReel['id'] ?? ''; ?>">
        <input type="hidden" name="existing_video" value="<?php echo $editReel['video'] ?? ''; ?>">

        <div style="display:flex;gap:20px;margin-bottom:15px;">
            <div style="flex:1;">
                <label>Title</label>
                <input type="text" name="title"
                value="<?php echo htmlspecialchars($editReel['title'] ?? ''); ?>"
                style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
            </div>

            <div style="flex:1;">
                <label>Buy Now Link</label>
                <input type="text" name="buy_link"
                value="<?php echo htmlspecialchars($editReel['buy_link'] ?? ''); ?>"
                style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
            </div>
        </div>

        <div style="margin-bottom:15px;">
            <label>Select Product *</label>
            <select name="product_id" required
            style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
                <option value="">Select Product</option>
                <?php foreach($products as $p): ?>
                    <option value="<?php echo $p['id']; ?>"
                        <?php if(($editReel['product_id'] ?? '') == $p['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($p['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if(!empty($editReel['video'])): ?>
        <div style="margin-bottom:15px;">
            <video width="150" controls muted>
                <source src="../uploads/reels/<?php echo htmlspecialchars($editReel['video']); ?>">
            </video>
        </div>
        <?php endif; ?>

        <div style="margin-bottom:15px;">
            <label>Upload Video <?php echo $editReel ? '(Optional)' : '*'; ?></label>
            <input type="file" name="video"
            style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>

        <div style="margin-bottom:15px;">
            <label>
                <input type="checkbox" name="is_active"
                <?php if(($editReel['is_active'] ?? 1)) echo 'checked'; ?>>
                Active
            </label>
        </div>

        <button type="submit"
        style="background:#111;color:#fff;padding:8px 18px;border:none;border-radius:6px;cursor:pointer;">
            <?php echo $editReel ? 'Update Reel' : 'Add Reel'; ?>
        </button>

        <?php if($editReel): ?>
            <a href="reels.php" style="margin-left:10px;">Cancel</a>
        <?php endif; ?>

    </form>
</div>


<!-- ==============================
     ALL REELS
============================== -->
<div style="background:#fff;padding:20px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
    <h2>All Reels</h2>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px;">

        <?php foreach ($reels as $reel): ?>
            <div style="background:#fafafa;padding:12px;border-radius:8px;box-shadow:0 1px 6px rgba(0,0,0,0.05);">

                <video controls muted style="width:100%;border-radius:6px;margin-bottom:8px;">
                    <source src="../uploads/reels/<?php echo htmlspecialchars($reel['video']); ?>">
                </video>

                <strong><?php echo htmlspecialchars($reel['title']); ?></strong><br>
                <small>Product: <?php echo htmlspecialchars($reel['product_name'] ?? 'N/A'); ?></small><br>
                <span style="color:<?php echo $reel['is_active'] ? 'green' : 'red'; ?>">
                    <?php echo $reel['is_active'] ? 'Active' : 'Inactive'; ?>
                </span>

                <div style="margin-top:10px;display:flex;gap:5px;">
                    <a href="reels.php?edit_id=<?php echo $reel['id']; ?>"
                       style="background:#007bff;color:#fff;padding:5px 10px;border-radius:4px;text-decoration:none;">
                        Edit
                    </a>

                    <a href="reels.php?delete_id=<?php echo $reel['id']; ?>"
                       onclick="return confirm('Delete this reel?');"
                       style="background:#dc3545;color:#fff;padding:5px 10px;border-radius:4px;text-decoration:none;">
                        Delete
                    </a>
                </div>

            </div>
        <?php endforeach; ?>

    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>