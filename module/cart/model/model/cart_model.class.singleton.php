<?php
    class cart_model{
        private $bll;
        static $_instance;

        function __construct(){
            $this->bll = cart_bll::getInstance();
        }
        public static function getInstance(){
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function services(){
            return $this->bll->services_BLL();
        }
        public function cart_user($args){
            return $this->bll->cart_user_BLL($args);
        }
        public function cart_add($args){
            return $this->bll->cart_add_BLL($args);
        }
        public function cart_add_service($args){
            return $this->bll->cart_add_service_BLL($args);
        }
        public function cart_delete($args){
            return $this->bll->cart_delete_BLL($args);
        }
        public function cart_plus($args){
            return $this->bll->cart_plus_BLL($args);
        }
        public function cart_minus($args){
            return $this->bll->cart_minus_BLL($args);
        }
    }
?>