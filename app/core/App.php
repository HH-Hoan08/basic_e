<?php
class App {
    protected $controller = 'HomeController'; //Controller mặc định if not in controller.
    protected $method = 'index'; //hàm của controller
    protected $params = []; //thuộc tính để cung cấp cho hàm

    //Hãy xem xét một URL ví dụ: http://localhost/basic_e/product/detail/123/size-M
    // $controller sẽ được xác định là ProductController.
    // $method sẽ được xác định là detail.
    // $params sẽ là ['123', 'size-M'].   
     
    public function __construct() {
        $url = $this->parseUrl();

        $controllerName = ucfirst($url[0]) . 'Controller';

        if(file_exists(ROOT_PATH . '/app/controllers/' . $controllerName . '.php')) {
            $this->controller = $controllerName;
            unset($url[0]);
        }

        require_once ROOT_PATH . '/app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        if(isset($url[1])) {
            if(method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if(isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        if(isset($_GET['page'])) {
            $page = $_GET['page'];
            if ($page === 'login') return ['Auth', 'login'];
            if ($page === 'register') return ['Auth', 'register'];
            if ($page === 'logout') return ['Auth', 'logout'];
            if ($page === 'shop-single') return ['Shop', 'single'];
            // Thêm route cho chức năng quên mật khẩu
            if ($page === 'forgot-password') return ['Password', 'forgot'];
            if ($page === 'reset-password') return ['Password', 'reset'];
            
            return [str_replace('-', '', $page)]; 
        }
        return ['Home'];
    }
}
