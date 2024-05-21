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
			try {
				// error_log("get_register_BLL called with args: " . json_encode($args), 3, "debug.log");
		
				$username = $args[0];
				$password = $args[1];
				$email = $args[2];
		
				// error_log("Parameters extracted: username=$username, password=$password, email=$email", 3, "debug.log");
		
				$hashed_pass = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
				$hashavatar = md5(strtolower(trim($email))); 
				$avatar = "https://i.pravatar.cc/500?u=$hashavatar";
				$token_email = middleware::create_email_token($email);
		
				// error_log("Generated hash and token: hashed_pass=$hashed_pass, avatar=$avatar, token_email=$token_email", 3, "debug.log");
		
				$user_exists = $this->dao->select_user($this->db, $username, $email);
				// error_log("select_user result: " . json_encode($user_exists), 3, "debug.log");
		
				if (!empty($user_exists)) {
					// error_log("User already exists", 3, "debug.log");
					return ['status' => 'error', 'message' => 'User already exists'];
				} else {
					$this->dao->register($this->db, $username, $hashed_pass, $email, $avatar, $token_email);
					// error_log("User registered in database", 3, "debug.log");
		
					$message = [ 
						'type' => 'validate', 
						'token' => $token_email, 
						'toEmail' => $email
					];
					$email_sent = json_decode(mail::send_email($message), true);
					// error_log("Email sent response: " . json_encode($email_sent), 3, "debug.log");
		
					if (!empty($email_sent)) {
						return ['status' => 'success'];
					} else {
						// error_log("Email sending failed", 3, "debug.log");
						return ['status' => 'error', 'message' => 'Email sending failed'];
					}
				}
			} catch (Exception $e) {
				// error_log("Exception in get_register_BLL: " . $e->getMessage(), 3, "debug.log");
				return ['status' => 'error', 'message' => $e->getMessage()];
			}
		}		
		public function get_verify_BLL($token) {
			try {
				$email = middleware::decode_email_token($token);
				// error_log("get_verify_BLL called with token: $token, decoded email: " . json_encode($email), 3, "debug.log");

				if ($email['exp'] < time()) {
					echo json_encode("Invalid token email verification");
					exit();
				} else {
					$this->dao->select_verify_email($this->db, $email['email']);
					// error_log("Email verified", 3, "debug.log");
					return 'verify';
				}
			} catch (Exception $e) {
				// error_log("Error occurred: " . $e->getMessage(), 3, "debug.log");
				return 'error';
			}
		}
		public function get_recover_BLL($email) {
			try {
				// error_log("get_recover_BLL called with email: $email", 3, "debug.log");
				$user = $this -> dao -> select_recover_password($this->db, $email);
				// error_log("User selected: " . json_encode($user), 3, "debug.log");

				$token = middleware::create_email_token($email);
				// error_log("Token created: " . $token, 3, "debug.log");

				if (!empty($user)) {
					$this -> dao -> update_recover_password($this->db, $email, $token);
					// error_log("Password updated for user: " . $email, 3, "debug.log");

					$message = ['type' => 'recover', 
								'token' => $token, 
								'toEmail' => $email];
					$email = json_decode(mail::send_email($message), true);
					// error_log("Email sent: " . json_encode($email), 3, "debug.log");

					if (!empty($email)) {
						return;  
					}   
				} else {
					// error_log("No user found with email: " . $email, 3, "debug.log");
					return 'error';
				}
			} catch (Exception $e) {
				// error_log("Error occurred: " . $e->getMessage(), 3, "debug.log");
				return 'error';
			}
		}
		public function get_verify_token_BLL($token) {
			try {
				$email = middleware::decode_email_token($token);
				// error_log("get_verify_token_BLL called with token: $token, decoded email: " . json_encode($email), 3, "debug.log");

				if ($email['exp'] < time()) {
					echo json_encode("Invalid token email verification");
					exit();
				} else {
					$this->dao->select_verify_email($this->db, $email['email']);
					// error_log("Token verified", 3, "debug.log");
					return 'verify';
				}
			} catch (Exception $e) {
				// error_log("Error occurred: " . $e->getMessage(), 3, "debug.log");
				return 'error';
			}
		}
		public function get_new_password_BLL($args) {
			try {
				// error_log("get_new_password_BLL called with args: " . json_encode($args), 3, "debug.log");
				$password = $args[0];
				$token = $args[1];
				$email = middleware::decode_email_token($token);
				// error_log("Parameters extracted: password=$password, token=$token, decoded email: " . json_encode($email), 3, "debug.log");

				if ($email['exp'] < time()) {
					echo json_encode("Invalid token email verification");
					exit();
				} else {
					$hashed_pass = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
					// error_log("Password hashed: " . $hashed_pass, 3, "debug.log");

					$this->dao->update_new_password($this->db, $email['email'], $hashed_pass);
					// error_log("Password updated", 3, "debug.log");
					return 'success';
				}
			} catch (Exception $e) {
				// error_log("Error occurred: " . $e->getMessage(), 3, "debug.log");
				return 'error';
			}
		}
	}
?>