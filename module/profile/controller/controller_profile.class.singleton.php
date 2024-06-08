<?php
    class controller_profile{
        static $_instance;
        public static function getInstance(){ 
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function view(){
            common::load_view('top_page_profile.html', VIEW_PATH_PROFILE . 'profile.html');
        }
        public function profile_data(){
            echo json_encode(common::load_model('profile_model', 'profile_data',[$_POST['token'],$_POST['social']]));
        }
        public function profile_orders(){
            echo json_encode(common::load_model('profile_model', 'profile_orders',[$_POST['token'],$_POST['social']]));
        }
        public function order_detail(){
            echo json_encode(common::load_model('profile_model', 'order_detail',[$_POST['token'],$_POST['social'],$_POST['order_id']]));
        }
        public function like(){
            echo json_encode(common::load_model('profile_model', 'like',[$_POST['token'],$_POST['social'],$_POST['id_property'],$_POST['id']]));
        }
        public function change_pass(){
            echo json_encode(common::load_model('profile_model', 'change_pass',[$_POST['token'],$_POST['new_pass']]));
        }
    }
?>