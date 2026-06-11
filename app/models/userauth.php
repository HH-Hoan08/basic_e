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

        // Cập nhật thông tin cá nhân (fullname, email)
        public function updateProfile($username, $fullname, $email){
            $sql = 'UPDATE users SET fullname = ?, email = ? WHERE username = ?';
            return $this->execute_item($sql, [$fullname, $email, $username]);
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
        public function getUserOrders($username){
            // Try-catch đề phòng trường hợp bảng orders chưa tồn tại trong Database
            try {
                $sql = 'SELECT * FROM orders WHERE username = ? ORDER BY created_at DESC';
                return $this->read_item($sql, [$username]);
            } catch (Exception $e) {
                return []; 
            }
        }
    }
?>
