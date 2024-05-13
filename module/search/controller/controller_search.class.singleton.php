<?php
    class controller_search{
        static $_instance;

        public static function getInstance(){ 
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function dynamic_search_city(){
            echo json_encode(common::load_model('search_model', 'get_dynamic_city'));
        }
        public function dynamic_search_type(){
            echo json_encode(common::load_model('search_model', 'get_dynamic_type',[$_POST['id_city']]));
        }
        public function search_type(){
            echo json_encode(common::load_model('search_model', 'get_type'));
        }
        public function autocomplete(){
            echo json_encode(common::load_model('search_model', 'get_autocomplete',[$_POST['auto_complete_data']]));
        }
    }
?>