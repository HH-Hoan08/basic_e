<?php
class database{
    private $user; 
    private $host_name;
    private $pass;
    private $name_db;
    private $is_conn = null; 
    
    public function __construct() {
        // $this->host_name = 'sql206.infinityfree.com';
        // $this->name_db = 'if0_42202669_basic_shop';
        // $this->user = 'if0_42202669';
        // $this->pass = 'Basic123456789';
        $this->host_name = 'localhost';
        $this->name_db = 'basic_shop'; 
        $this->user = 'root';
        $this->pass = '';
    }
    
    public function connect(){
        try {
            // Đưa thẳng charset=utf8mb4 vào chuỗi DSN này (Chuẩn và an toàn nhất)
            $dsn = "mysql:host={$this->host_name};dbname={$this->name_db};charset=utf8mb4";
            
            $this->is_conn = new PDO($dsn, $this->user, $this->pass);
            $this->is_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // XÓA dòng $this->conn->exec("set names utf8mb4"); cũ đi vì đã có charset ở DSN phía trên
            // Nếu bạn vẫn thích dùng cách cũ thì phải viết đúng tên biến là: 
            // $this->is_conn->exec("set names utf8mb4");

        }catch(PDOException $e){
            echo "Lỗi kết nối cụ thể là: " . $e->getMessage() . "<br>";
            throw $e;
        }
        return $this->is_conn;
    }
}
?>