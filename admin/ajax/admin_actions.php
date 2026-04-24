<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); // 🔥 THIS IS THE FIX
header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/models/Story.php';   // MOST IMPORTANT
require_once __DIR__ . '/../../includes/models/Admin.php';
require_once __DIR__ . '/../../includes/models/Product.php';
require_once __DIR__ . '/../../includes/models/Category.php';
require_once __DIR__ . '/../../includes/models/Hero.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}



$action = $_POST['action'] ?? '';

switch($action) {
   case 'approve_review':
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            db()->prepare("UPDATE reviews SET is_approved = 1 WHERE id = ?")->execute([$id]);
            echo json_encode(['success' => true]);
        }
        break;

    case 'delete_review':
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            db()->prepare("DELETE FROM reviews WHERE id = ?")->execute([$id]);
            echo json_encode(['success' => true]);
        }
        break;

    case 'update_inquiry_status':
        $id = (int)($_GET['id'] ?? 0);
        $status = $_GET['status'] ?? '';
        if ($id && $status) {
            ContactInquiry::updateStatus($id, $status, $_SESSION['admin_id']);
            echo json_encode(['success' => true]);
        }
        break;

    case 'update_setting':
        $key = $_POST['key'] ?? '';
        $value = $_POST['value'] ?? '';
        if($key) {
            Setting::set($key, $value);
            echo json_encode(['success' => true]);
        }
        break;
        
    case 'save_hero':
        $data = [
            'badge' => $_POST['badge'] ?? '',
            'title' => $_POST['title'] ?? '',
            'subtitle' => $_POST['subtitle'] ?? '',
            'media_type' => $_POST['media_type'] ?? 'image',
            'button_text' => $_POST['button_text'] ?? 'SHOP NOW',
            'button_link' => $_POST['button_link'] ?? 'products.php',
            'display_order' => (int)($_POST['display_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Handle Image Upload
        if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] == 0) {
            $upload_dir = '../../uploads/banners/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $filename = time() . '_' . $_FILES['hero_image']['name'];
            move_uploaded_file($_FILES['hero_image']['tmp_name'], $upload_dir . $filename);
            $data['media_url'] = $filename;
        } elseif (isset($_POST['media_url'])) {
            $data['media_url'] = $_POST['media_url'];
        }

        // Handle Video Upload
        if (isset($_FILES['hero_video']) && $_FILES['hero_video']['error'] == 0) {
            $upload_dir = '../../uploads/banners/';
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
        echo json_encode(['success' => true]);
        break;

    case 'save_category':
        $id = $_POST['id'] ?? null;
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? '',
            'icon_class' => $_POST['icon_class'] ?? '',
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] == 0) {
            $upload_dir = '../../uploads/categories/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $filename = time() . '_' . $_FILES['image_upload']['name'];
            move_uploaded_file($_FILES['image_upload']['tmp_name'], $upload_dir . $filename);
            $data['image_url'] = $filename;
        }
        
        if (isset($_FILES['video_upload']) && $_FILES['video_upload']['error'] == 0) {
            $upload_dir = '../../uploads/categories/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $filename = time() . '_' . $_FILES['video_upload']['name'];
            move_uploaded_file($_FILES['video_upload']['tmp_name'], $upload_dir . $filename);
            $data['video_url'] = $filename;
        }

        if ($id) {
            Category::update((int)$id, $data);
        } else {
            Category::create($data);
        }
        echo json_encode(['success' => true]);
        break;

    case 'save_product':
        $id = $_POST['id'] ?? null;
        $data = [
            'category_id' => (int)$_POST['category_id'],
            'name' => $_POST['name'],
            'sku' => $_POST['sku'] ?? '',
            'price' => (float)$_POST['price'],
            'mrp' => (float)($_POST['mrp'] ?? $_POST['price']),
            'short_description' => $_POST['short_description'] ?? '',
            'long_description' => $_POST['long_description'] ?? '',
            'benefits' => $_POST['benefits'] ?? '',
            'ingredients' => $_POST['ingredients'] ?? '',
            'usage_instructions' => $_POST['usage_instructions'] ?? '',
            'testing_info' => $_POST['testing_info'] ?? '',
            'reward_coins' => (int)($_POST['reward_coins'] ?? 0),
            'stock_quantity' => (int)($_POST['stock_qty'] ?? $_POST['stock_quantity'] ?? 0),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_dir = '../../uploads/products/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $filename = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename);
            $data['image'] = $filename;
        }

        if ($id) {
            Product::update((int)$id, $data);
            $product_id = $id;
        } else {
            $product_id = Product::create($data);
        }

        // Handle Gallery Media
        if (isset($_FILES['gallery_media']) && !empty($_FILES['gallery_media']['name'][0])) {
            $upload_dir = '../../uploads/products/';
            foreach ($_FILES['gallery_media']['name'] as $key => $name) {
                if ($_FILES['gallery_media']['error'][$key] == 0) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    $filename = time() . '_' . $key . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $name);
                    if (move_uploaded_file($_FILES['gallery_media']['tmp_name'][$key], $upload_dir . $filename)) {
                        $type = strpos($_FILES['gallery_media']['type'][$key], 'video') !== false ? 'video' : 'image';
                        // Extension fallback
                        if ($type === 'image' && in_array($ext, ['mp4', 'webm', 'ogg', 'mov', 'avi'])) {
                            $type = 'video';
                        }
                        db()->prepare("INSERT INTO product_media (product_id, media_url, media_type) VALUES (?, ?, ?)")->execute([$product_id, $filename, $type]);
                    }
                }
            }
        }

        echo json_encode(['success' => true]);
        break;

    case 'delete_media':
        $id = (int)($_GET['delete_media'] ?? 0);
        if ($id) {
            db()->prepare("DELETE FROM product_media WHERE id = ?")->execute([$id]);
            echo json_encode(['success' => true]);
        }
        break;

    case 'update_order':
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        $tracking_id = $_POST['tracking_id'] ?? '';
        $courier_name = $_POST['courier_name'] ?? '';
        
        $stmt = db()->prepare("UPDATE orders SET order_status = ?, tracking_id = ?, courier_name = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$status, $tracking_id, $courier_name, $id]);
        
        // Push to Shiprocket if status is 'shipped'
        if ($status === 'shipped' && !empty(SHIPROCKET_API_TOKEN)) {
            require_once __DIR__ . '/../../includes/shiprocket_helper.php';
            $order = Order::getById($id);
            $items = Order::getItems($id);
            ShiprocketHelper::createOrder($order, $items);
        }
        
        echo json_encode(['success' => true]);
        break;

    case 'delete_product':
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            db()->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
            echo json_encode(['success' => true]);
        }
        break;

    case 'delete_category':
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            db()->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
            echo json_encode(['success' => true]);
        }
        break;

    case 'save_story':
        try {
            // Log incoming data for debugging (if needed)
            // error_log(print_r($_POST, true));
            // error_log(print_r($_FILES, true));

            $data = [
                'name' => $_POST['name'] ?? '',
                'story_text' => $_POST['story_text'] ?? '',
                'rating' => (int)($_POST['rating'] ?? 5),
                'image_path' => ''
            ];

            if (isset($_FILES['story_image']) && $_FILES['story_image']['error'] == 0) {
                $upload_dir = '../../uploads/stories/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $filename = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['story_image']['name']);

                if (!move_uploaded_file($_FILES['story_image']['tmp_name'], $upload_dir . $filename)) {
                    throw new Exception("Image upload failed to " . $upload_dir);
                }

                $data['image_path'] = $filename;
            }

            if (!$data['image_path']) {
                throw new Exception("Image is required");
            }

            Story::create($data);
            echo json_encode(['success' => true]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        break;
case 'delete_story':
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        Story::delete($id);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    }
    break;


    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>