<?php
    class controller_login{
        static $_instance;
        public static function getInstance(){ 
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        function view(){
            common::load_view('top_page_login.html', VIEW_PATH_LOGIN . 'login.html');
        }
        function register() {
            echo json_encode(common::load_model('login_model', 'get_register', [$_POST['usernameRegister'], $_POST['passwordRegister'], $_POST['emailRegister']]));
        }
        function verify(){
            echo json_encode(common::load_model('login_model', 'get_verify', $_POST['token_email']));
        }
        function recover(){
            echo json_encode(common::load_model('login_model', 'get_recover', $_POST['email']));
        }
    }
?>
