<?php
class database{
    private $user; 
    private $host_name;
    private $pass;
    private $name_db;
    private $is_conn = null; //biến dùng để kiểm tra thử đã kết nối vào db chưa
    
    public function __construct(){
        // Ưu tiên đọc từ $_SERVER (nơi Apache đưa biến SetEnv vào), sau đó dự phòng bằng getenv()
        $this->user = $_SERVER['USER'] ?? getenv('USER') ?: '?';
        $this->host_name = $_SERVER['HOST_NAME'] ?? getenv('HOST_NAME') ?: '?';
        $this->pass = $_SERVER['PASS'] ?? getenv('PASS') ?: '';
        $this->name_db = $_SERVER['NAME_DB'] ?? getenv('NAME_DB') ?: '?';
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
