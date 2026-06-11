<?php 
include_once __DIR__ . '/database.php';
class xl_data{
    private $db;

    //lấy dữ liệu database
    public function __construct(){
        #Gọi class database để gọi hàm connect
        $this->db = new database();
    }

    public function read_item($sql, $params = []): array{
        #khởi động vào hàm connect 
        $db = $this->db->connect();
        if($db != null){ #nếu không lỗi hàm connect thì trả về danh sách của read = rỗng 
            try{
                $stmt = $db->prepare($sql); #kiểm tra cú pháp
                $stmt->execute($params);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result ? $result : [];
            }catch(PDOException $e){
                // Không echo lỗi ra màn hình để Controller tự catch và xử lý ngầm
                throw $e;
            }
        }
        return [];
    }

    public function execute_item($sql, $params = []): bool{
        $db = $this->db->connect();
        if($db != null){
            try{
                $stmt = $db->prepare($sql); #kiểm tra cú pháp
                return $stmt->execute($params);
            }catch(PDOException $e){
                // Không echo lỗi ra màn hình để Controller tự catch và xử lý ngầm
                throw $e;
            }
        }
        return false;
    }
}
?>
