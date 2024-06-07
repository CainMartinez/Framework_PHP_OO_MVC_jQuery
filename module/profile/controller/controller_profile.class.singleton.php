<?php
    class controller_profile{
        static $_instance;
        public static function getInstance(){ 
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        function view(){
            common::load_view('top_page_profile.html', VIEW_PATH_PROFILE . 'profile.html');
        }
        function profile_data(){
            echo json_encode(common::load_model('profile_model', 'profile_data',[$_POST['token'],$_POST['social']]));
        }
        function profile_orders(){
            echo json_encode(common::load_model('profile_model', 'profile_orders',[$_POST['token'],$_POST['social']]));
        }
        function order_detail(){
            echo json_encode(common::load_model('profile_model', 'order_detail',[$_POST['token'],$_POST['social'],$_POST['order_id']]));
        }
    }
?>