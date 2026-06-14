<?php
    include_once __DIR__ . '/xl_data.php';
    class UserAuth extends xl_data{
        ////Tìm user bằng Username hoặc Email (Dùng lúc Login hoặc Check trùng khi Register)
        public function getUser($username, $email){
            $sql = 'SELECT * FROM users WHERE username = ? OR email = ?'; 
            return $this->read_item($sql, [$username, $email]);
        }

        public function insertUser($fullname, $email, $username, $hashed_password){
            $sql = 'INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ?, ?)';
            return $this->execute_item($sql, [$fullname, $email, $username, $hashed_password]);
        }

        // Lấy thông tin user bằng username
        public function getUserByUsername($username){
            $sql = 'SELECT * FROM users WHERE username = ?'; 
            $result = $this->read_item($sql, [$username]);
            return !empty($result) ? $result[0] : null;
        }

        // Lấy thông tin user bằng ID
        public function getUserById(int $id){
            $sql = 'SELECT * FROM users WHERE id = ?'; 
            $result = $this->read_item($sql, [$id]);
            return !empty($result) ? $result[0] : null;
        }

        // Cập nhật thông tin cá nhân (fullname, email, address)
        public function updateProfile($username, $fullname, $email, $address = ''){
            $sql = 'UPDATE users SET fullname = ?, email = ?, address = ? WHERE username = ?';
            return $this->execute_item($sql, [$fullname, $email, $address, $username]);
        }

        // Cập nhật ảnh đại diện
        public function updateAvatar($username, $imageName){
            $sql = 'UPDATE users SET image = ? WHERE username = ?';
            return $this->execute_item($sql, [$imageName, $username]);
        }

        // Cập nhật mật khẩu
        public function updatePassword($username, $hashed_password){
            $sql = 'UPDATE users SET password = ? WHERE username = ?';
            return $this->execute_item($sql, [$hashed_password, $username]);
        }

        // Lấy danh sách lịch sử đơn hàng của người dùng
        public function getUserOrders(int $userId){ // Thay đổi tham số từ username sang userId
            // Try-catch đề phòng trường hợp bảng orders chưa tồn tại trong Database
            try {
                $sql = 'SELECT * FROM orders WHERE user_id = ? ORDER BY ordered_at DESC';
                return $this->read_item($sql, [$userId]);
            } catch (Exception $e) {
                return [];
            }
        }

        // Lấy thông tin user bằng email
        public function getUserByEmail($email){
            $sql = 'SELECT * FROM users WHERE email = ?'; 
            $result = $this->read_item($sql, [$email]);
            return !empty($result) ? $result[0] : null;
        }

        // Cập nhật mật khẩu bằng email
        public function updatePasswordByEmail($email, $hashed_password){
            $sql = 'UPDATE users SET password = ? WHERE email = ?';
            return $this->execute_item($sql, [$hashed_password, $email]);
        }

        // --- Password Reset Methods ---
        public function createPasswordResetToken(string $email, string $token): bool {
            $this->deletePasswordResetToken($email); // Xóa token cũ nếu có
            $sql = "INSERT INTO password_resets (email, token) VALUES (?, ?)";
            return $this->execute_item($sql, [$email, $token]);
        }

        public function getPasswordResetToken(string $token) {
            $sql = "SELECT * FROM password_resets WHERE token = ? AND created_at >= NOW() - INTERVAL 15 MINUTE";
            $result = $this->read_item($sql, [$token]);
            return !empty($result) ? $result[0] : null;
        }

        public function deletePasswordResetToken(string $email): bool {
            $sql = "DELETE FROM password_resets WHERE email = ?";
            return $this->execute_item($sql, [$email]);
        }
}