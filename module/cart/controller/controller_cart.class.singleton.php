<?php
    class controller_cart{
        static $_instance;
        public static function getInstance(){ 
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function view(){
            common::load_view('top_page_cart.html', VIEW_PATH_CART . 'cart.html');
        }
        public function services(){
            echo json_encode(common::load_model('cart_model', 'services'));
        }
        public function cart_user(){
            echo json_encode(common::load_model('cart_model', 'cart_user', [$_POST['token'],$_POST['social']]));
        }
        public function cart_add(){
            echo json_encode(common::load_model('cart_model', 'cart_add', [$_POST['service'], $_POST['token'], $_POST['social']]));
        }
        public function cart_add_service(){
            echo json_encode(common::load_model('cart_model', 'cart_add_service', [$_POST['service'],$_POST['price'], $_POST['token'], $_POST['social']]));
        }
    }
?>