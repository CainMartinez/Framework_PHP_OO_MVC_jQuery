<?php
    class login_dao{
        static $_instance;

        private function __construct() {
        }

        public static function getInstance() {
            if(!(self::$_instance instanceof self)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function register($db, $username_reg, $hashed_pass, $email_reg, $avatar, $token_email) {
            try {
                $sql = "INSERT INTO users (username, password, email, avatar, type_user, active, token)
                        VALUES ('$username_reg', '$hashed_pass', '$email_reg', '$avatar', 'client', 0, '$token_email')";
                // error_log("SQL for register: " . $sql, 3, "debug.log");
                $stmt = $db->ejecutar($sql);
                return $stmt;
            } catch (Exception $e) {
                // error_log("Exception in register DAO: " . $e->getMessage(), 3, "debug.log");
                throw $e;
            }
        }
        
        public function select_user($db, $username, $email) {
            try {
                $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
                // error_log("SQL for select_user: " . $sql, 3, "debug.log");
                $stmt = $db->ejecutar($sql);
                $result = $db->listar($stmt);
                // error_log("select_user result: " . json_encode($result), 3, "debug.log");
                return $result;
            } catch (Exception $e) {
                // error_log("Exception in select_user DAO: " . $e->getMessage(), 3, "debug.log");
                throw $e;
            }
        }
        
        
        
        public function select_social_login($db, $id){

			$sql = "SELECT * FROM users WHERE id='$id'";
            $stmt = $db->ejecutar($sql);

            return $db->listar($stmt);
        }

        public function insert_social_login($db, $id, $username, $email, $avatar){

            $sql ="INSERT INTO users (id, username, password, email, type_user, avatar, token, active)     
                VALUES ('$id', '$username', '', '$email', 'client', '$avatar', '', 1)";

            return $stmt = $db->ejecutar($sql);
        }

        public function select_verify_email($db, $token_email){

			$sql = "SELECT token FROM users WHERE token = '$token_email'";

            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        } 

        public function update_verify_email($db, $token_email){

            $sql = "UPDATE users SET active = 1, token= '' WHERE token = '$token_email'";

            $stmt = $db->ejecutar($sql);
            return "update";
        }

        public function select_recover_password($db, $email){
			$sql = "SELECT `email` FROM `users` WHERE email = '$email' AND password NOT LIKE ('')";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }

        public function update_recover_password($db, $email, $token_email){
			$sql = "UPDATE `users` SET `token`= '$token_email' WHERE `email` = '$email'";
            $stmt = $db->ejecutar($sql);
            return "ok";
        }

        public function update_new_passwoord($db, $token_email, $password){
            $sql = "UPDATE `users` SET `password`= '$password', `token`= '' WHERE `token` = '$token_email'";
            $stmt = $db->ejecutar($sql);
            return "ok";
        }

        public function select_data_user($db, $username){

			$sql = "SELECT id, username, password, email, type_user, avatar, token, active FROM users WHERE username = '$username'";
            
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }

    }

?>