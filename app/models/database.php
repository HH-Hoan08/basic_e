<?php
class database{
    private $user; 
    private $host_name;
    private $pass;
    private $name_db;
    private $is_conn = null; //biến dùng để kiểm tra thử đã kết nối vào db chưa
    
    public function __construct(){
        $this->user = getenv('USER') ?: 'root';
        $this->host_name = getenv('HOST_NAME') ?: 'localhost';
        $this->pass = getenv('PASS') !== false ? getenv('PASS') : '';
        $this->name_db = getenv('NAME_DB') ?: 'basic_shop';
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
