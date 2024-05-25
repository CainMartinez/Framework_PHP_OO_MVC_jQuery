<?php
    class auth_dao{
        static $_instance;

        private function __construct() {
        }
        public static function getInstance() {
            if(!(self::$_instance instanceof self)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function register($db, $username_reg, $hashed_pass, $email_reg, $avatar) {
            try {
                $sql = "INSERT INTO users (username, password, email, avatar, type_user, active, count_login, phone, token)
                        VALUES ('$username_reg', '$hashed_pass', '$email_reg', '$avatar', 'client', 0, 0, '', '')";
                // error_log("SQL for register: " . $sql, 3, "debug.log");
                $stmt = $db->ejecutar($sql);
                // error_log("SQL execution result: " . json_encode($stmt), 3, "debug.log");
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
                // error_log("SQL execution result: " . json_encode($stmt), 3, "debug.log");
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
            $sql ="INSERT INTO users (id, username, password, email, type_user, avatar, active, count_login , phone)     
                VALUES ('$id', '$username', '', '$email', 'client', '$avatar', 1)";
            return $stmt = $db->ejecutar($sql);
        }
        public function select_verify_email($db, $token_email){
            $sql = "SELECT email FROM users WHERE email = '$token_email'";
            // error_log("SQL for select_verify_email: " . $sql, 3, "debug.log");
            $stmt = $db->ejecutar($sql);
            $result = $db->listar($stmt);
            if (!empty($result)) {
                $this->update_verify_email($db, $token_email);
            }
            return $result;
        }
        public function update_verify_email($db, $token_email){

            $sql = "UPDATE users SET active = 1 WHERE email = '$token_email'";
            // error_log("SQL for update_verify_email: " . $sql, 3, "debug.log");

            $stmt = $db->ejecutar($sql);
            return "update";
        }
        public function select_recover_password($db, $email){
			$sql = "SELECT `email` FROM `users` WHERE email = '$email' AND password NOT LIKE ('')";
            // error_log("SQL for select_recover_password: " . $sql, 3, "debug.log");
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }
        public function update_new_password($db, $token_email, $password){
            $sql = "UPDATE `users` SET `password`= '$password' WHERE `email` = '$token_email'";
            error_log("SQL for update_new_password: " . $sql, 3, "debug.log");
            $stmt = $db->ejecutar($sql);
            return "ok";
        }
        public function select_data_user($db, $username){
			$sql = "SELECT * FROM users WHERE username = '$username'";
            error_log("SQL for select_data_user: " . $sql, 3, "debug.log");
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }
        public function increment_count($db, $email){
            $sql = "UPDATE users SET count_login = count_login + 1 WHERE email = '$email'";
            // error_log("SQL for increment_count: " . $sql, 3, "debug.log");
            $stmt = $db->ejecutar($sql);
            return "update";
        }
        public function reset_count($db, $email){
            $sql = "UPDATE users SET count_login = 0 WHERE email = '$email'";
            // error_log("SQL for reset_count: " . $sql, 3, "debug.log");
            $stmt = $db->ejecutar($sql);
            return "update";
        }
        public function insert_token_opt($db,$email,$token){
            $sql = "UPDATE users SET token = '$token' WHERE email = '$email'";
            // error_log("SQL for insert_token_opt: " . $sql, 3, "debug.log");
            $stmt = $db->ejecutar($sql);
            return 'update';
        }
        public function select_token_opt($db,$token){
            $sql = "SELECT * FROM users WHERE token = '$token'";
            error_log("SQL for select_token_opt: " . $sql, 3, "debug.log");
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }
        public function delete_token_opt($db,$token){
            $sql = "UPDATE users SET token = '' WHERE token = '$token'";
            error_log("SQL for update_token_opt: " . $sql, 3, "debug.log");
            $stmt = $db->ejecutar($sql);
            return 'update';
        }
        public function update_active_true($db,$email){
            $sql = "UPDATE users SET active = 1 WHERE email = '$email'";
            error_log("SQL for update_active: " . $sql, 3, "debug.log");
            $stmt = $db->ejecutar($sql);
            return 'update';
        }
        public function update_active_false($db,$email){
            $sql = "UPDATE users SET active = 1 WHERE email = '$email'";
            error_log("SQL for update_active: " . $sql, 3, "debug.log");
            $stmt = $db->ejecutar($sql);
            return 'update';
        }
    }
?>