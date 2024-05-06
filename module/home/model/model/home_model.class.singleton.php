<?php
    // include_once("module/home/model/BLL/home_bll.class.singleton.php");
    class home_model {

        private $bll;
        static $_instance;
        
        function __construct() {
            $this -> bll = home_bll::getInstance();
        }

        public static function getInstance() {
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function get_carousel_people() {
            return $this -> bll -> get_carousel_people_BLL();
        }

        public function get_categories() {
            return $this -> bll -> get_categories_BLL();
        }
        public function get_type() {
            return $this -> bll -> get_type_BLL();
        }
        public function get_operation() {
            return $this -> bll -> get_operation_BLL();
        }
        public function get_city() {
            return $this -> bll -> get_city_BLL();
        }

    }
?>