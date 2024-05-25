<?php
    class controller_auth{
        static $_instance;
        public static function getInstance(){ 
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        function view(){
            common::load_view('top_page_auth.html', VIEW_PATH_AUTH . 'auth.html');
        }
        function register() {
            echo json_encode(common::load_model('auth_model', 'get_register', [$_POST['usernameRegister'], $_POST['passwordRegister'], $_POST['emailRegister']]));
        }
        function verify(){
            echo json_encode(common::load_model('auth_model', 'get_verify', $_POST['token_email']));
        }
        function recover(){
            echo json_encode(common::load_model('auth_model', 'get_recover', $_POST['email']));
        }
        function recover_view(){
            common::load_view('top_page_auth.html', VIEW_PATH_AUTH . 'recover_view.html');
        }
        function verify_token(){
            echo json_encode(common::load_model('auth_model', 'get_verify_token', $_POST['token_email']));
        }
        function new_password(){
            echo json_encode(common::load_model('auth_model', 'get_new_password', [$_POST['password'], $_POST['token_email']]));
        }
        function login(){
            echo json_encode(common::load_model('auth_model', 'get_login', [$_POST['username'], $_POST['password']]));
        }
        function data_user(){
            echo json_encode(common::load_model('auth_model', 'get_data_user', $_POST['access_token']));
        }
        function logout(){
            echo json_encode(common::load_model('auth_model', 'get_logout'));
        }
        function control_user(){
            echo json_encode(common::load_model('auth_model', 'get_control_user', [$_POST['access_token'], $_POST['refresh_token']]));
        }
        function activity(){
            echo json_encode(common::load_model('auth_model', 'get_activity'));
        }
        function refresh_cookies(){
            echo json_encode(common::load_model('auth_model', 'get_refresh_cookies'));
        }
        function otp(){
            echo json_encode(common::load_model('auth_model', 'get_otp',[$_POST['otp_code']]));
        }
    }
?>
