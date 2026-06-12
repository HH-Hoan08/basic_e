<?php
class database{
    private $user; 
    private $host_name;
    private $pass;
    private $name_db;
    private $is_conn = null; //biến dùng để kiểm tra thử đã kết nối vào db chưa
    
    public function __construct(){
        // Sử dụng thông tin kết nối trực tiếp để đảm bảo ổn định.
        // Đây là cấu hình mặc định cho XAMPP.
        $this->host_name = 'localhost';
        $this->name_db = 'basic_shop';
        $this->user = 'root';
        $this->pass = '';
    }
    
    public function connect(){
        try{
            $this->is_conn = new PDO("mysql:host=$this->host_name;dbname=$this->name_db", $this->user, $this->pass);
            $this->is_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            echo "Lỗi kết nối cụ thể là: " . $e->getMessage() . "<br>";
            throw $e;
        }
        return $this->is_conn;
    }
}
?>
