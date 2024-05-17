<?php
	class login_bll {
		private $dao;
		private $db;
		static $_instance;

		function __construct() {
			$this -> dao = login_dao::getInstance();
			$this -> db = db::getInstance();
		}

		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		public function get_register_BLL($args) {
			// return $args;
			$hashed_pass = password_hash($args[1], PASSWORD_DEFAULT,['cost' => 12]);
			// return $hashed_pass;
			$hashavatar = md5(strtolower(trim($args[2]))); 
			$avatar = "https://i.pravatar.cc/500?u=$hashavatar";
			$token_email = common::generate_Token_secure(20);

			if (!empty($this -> dao -> select_user($this->db, $args[0], $args[2]))) {
				return 'error';
            } else {
				$this -> dao -> register($this->db, $args[0], $hashed_pass, $args[2], $avatar, $token_email);
				$message = [ 
					'type' => 'validate', 
					'token' => $token_email, 
					'toEmail' =>  $args[2]
				];
				$email = json_decode(mail::send_email($message), true);
				if (!empty($email)) {
					return;  
				}
			}
		}
		public function get_verify_BLL($token) {
			if (!empty($this -> dao -> select_verify_email($this->db, $token))) {
				$this -> dao -> update_verify_email($this->db, $token);
				return 'verify';
			} else {
				return 'fail';
			}
		}
	}
?>