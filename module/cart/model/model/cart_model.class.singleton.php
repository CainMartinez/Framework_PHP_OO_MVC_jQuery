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
        function services(){
            return $this->bll->services_BLL();
        }
        function cart_user($args){
            return $this->bll->cart_user_BLL($args);
        }
        function cart_add($args){
            // error_log("entro al model",3,"debug.log");
            return $this->bll->cart_add_BLL($args);
        }
    }
?>