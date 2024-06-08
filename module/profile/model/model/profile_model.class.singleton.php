<?php
    class profile_model{
        private $bll;
        static $_instance;
        function __construct(){
            $this->bll = profile_bll::getInstance();
        }
        public static function getInstance(){
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function profile_data($args){
            return $this->bll->profile_data_BLL($args);
        }
        public function profile_orders($args){
            return $this->bll->profile_orders_BLL($args);
        }
        public function order_detail($args){
            return $this->bll->order_detail_BLL($args);
        }
        public function like($args){
            return $this->bll->like_BLL($args);
        }
    }
?>