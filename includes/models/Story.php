<?php
class Story {
    public static function getAll() {
        $db = db();
        try {
            $stmt = $db->prepare("SELECT * FROM stories ORDER BY created_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Story::getAll error: " . $e->getMessage());
            return [];
        }
    }

    public static function create($data) {
        $db = db();
        try {
            $stmt = $db->prepare("INSERT INTO stories (name, story_text, rating, image_path) VALUES (?, ?, ?, ?)");
            return $stmt->execute([
                $data['name'],
                $data['story_text'],
                $data['rating'],
                $data['image_path']
            ]);
        } catch (Exception $e) {
            error_log("Story::create error: " . $e->getMessage());
            throw $e;
        }
    }

    public static function delete($id) {
        $db = db();
        try {
            $stmt = $db->prepare("DELETE FROM stories WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            error_log("Story::delete error: " . $e->getMessage());
            return false;
        }
    }
}
?>