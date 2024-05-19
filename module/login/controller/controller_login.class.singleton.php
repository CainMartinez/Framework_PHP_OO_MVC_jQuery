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
            try {
                // error_log("Register function called", 3, "debug.log");
        
                if (!isset($_POST['usernameRegister']) || !isset($_POST['passwordRegister']) || !isset($_POST['emailRegister'])) {
                    throw new Exception('Missing POST parameters');
                }
        
                $username = $_POST['usernameRegister'];
                $password = $_POST['passwordRegister'];
                $email = $_POST['emailRegister'];
        
                // error_log("POST parameters: username=$username, password=$password, email=$email", 3, "debug.log");
        
                $response = common::load_model('login_model', 'get_register', [$username, $password, $email]);
        
                // error_log("Response: " . json_encode($response), 3, "debug.log");
                echo json_encode($response);
            } catch (Exception $e) {
                // error_log("Exception: " . "Entra en el catch de controller_register", 3, "debug.log");
                echo json_encode(['status' => 'error', 'message' => "Error in register controller: " . $e->getMessage()]);
            }
        }
        function verify(){
            // $token=$_POST['token_email'];
            // error_log("POST parameters: $token", 3, "debug.log");
            echo json_encode(common::load_model('login_model', 'get_verify', $_POST['token_email']));
        }
    }
?>
