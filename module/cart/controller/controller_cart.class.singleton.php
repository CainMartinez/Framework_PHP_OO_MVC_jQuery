<?php
    class controller_cart{
        static $_instance;
        public static function getInstance(){ 
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        function view(){
            common::load_view('top_page_cart.html', VIEW_PATH_CART . 'cart.html');
        }
        function services(){
            echo json_encode(common::load_model('cart_model', 'services'));
        }
        function cart_user(){
            echo json_encode(common::load_model('cart_model', 'cart_user', [$_POST['token'],$_POST['social']]));
        }
        function cart_add(){
            // error_log("entro al controller",3,"debug.log");
            echo json_encode(common::load_model('cart_model', 'cart_add', [$_POST['id'], $_POST['token'], $_POST['social']]));
        }
    }
?>