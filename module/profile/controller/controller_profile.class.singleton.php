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
    }
?>