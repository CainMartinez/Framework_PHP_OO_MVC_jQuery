<?php
    class search_model{
        private $bll;
        static $_instance;

        function __construct(){
            $this->bll = search_bll::getInstance();
        }

        public static function getInstance(){
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function get_dynamic_city(){
            return $this -> bll -> get_dynamic_city_BLL();
        }

        public function get_dynamic_type($array){
            return $this -> bll -> get_dynamic_type_BLL($array);
        }

        public function get_type(){
            return $this -> bll -> get_type_BLL();
        }

        public function get_autocomplete($array){
            return $this -> bll -> get_autocomplete_BLL($array);
        }
    }
?>