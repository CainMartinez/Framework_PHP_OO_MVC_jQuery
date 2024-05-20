<?php
    class login_model{
        private $bll;
        static $_instance;

        function __construct(){
            $this->bll = login_bll::getInstance();
        }

        public static function getInstance(){
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function get_register($args){
            return $this->bll->get_register_BLL($args);
        }
        public function get_verify($token){
            return $this->bll->get_verify_BLL($token);
        }
        public function get_recover($email){
            return $this->bll->get_recover_BLL($email);
        }
    }
?>