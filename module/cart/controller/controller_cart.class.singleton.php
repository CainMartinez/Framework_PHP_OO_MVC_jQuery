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
            common::load_view('top_page.html', VIEW_PATH_CART . 'cart.html');
        }
    }
?>