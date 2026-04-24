<?php
require_once __DIR__ . '/../database.php';

class ContactInquiry {
    public static function getAll($status = null) {
        $sql = "SELECT ci.*, a.username as handled_by_name FROM contact_inquiries ci LEFT JOIN admins a ON ci.handled_by = a.id";
        $params = [];
        
        if ($status) {
            $sql .= " WHERE ci.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY ci.created_at DESC";
        
        try {
            $stmt = db()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll() ?: [];
        } catch (Exception $e) {
            error_log("ContactInquiry::getAll error: " . $e->getMessage());
            return [];
        }
    }

    public static function getById($id) {
        $stmt = db()->prepare("SELECT ci.*, a.username as handled_by_name FROM contact_inquiries ci LEFT JOIN admins a ON ci.handled_by = a.id WHERE ci.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $stmt = db()->prepare("INSERT INTO contact_inquiries (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'] ?? '',
            $data['subject'] ?? '',
            $data['message']
        ]);
        return db()->lastInsertId();
    }

    public static function updateStatus($id, $status, $adminId = null) {
        $stmt = db()->prepare("UPDATE contact_inquiries SET status = ?, handled_by = ?, handled_at = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$status, $adminId, $id]);
    }

    public static function delete($id) {
        $stmt = db()->prepare("DELETE FROM contact_inquiries WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getNewCount() {
        return db()->query("SELECT COUNT(*) FROM contact_inquiries WHERE status = 'new'")->fetchColumn();
    }
}
