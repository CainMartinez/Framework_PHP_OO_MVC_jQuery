<?php
	class cart_bll {
		private $dao;
		private $db;
		static $_instance;

		function __construct() {
			$this -> dao = cart_dao::getInstance();
			$this -> db = db::getInstance();
		}
		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		public function services_BLL() {
			return $this -> dao -> services_DAO($this -> db);
		}
		public function cart_user_BLL($args) {
			$decode_token = middleware::decode_token($args[0]);
			if ($args[1] === ''){
				$id_user = $this -> dao -> select_id($this -> db,$decode_token['username']);
			}else {
				$id_user = $this -> dao -> select_id_social($this -> db,$decode_token['username'],$args[2]);
			}
			return $this -> dao -> cart_user_DAO($this -> db,$id_user[0]['id_user']);
		}
		public function cart_add_BLL($args) {
			// error_log("Entro al BLL y el valor es ". $args[1],3,'debug.log');
			$decode_token = middleware::decode_token($args[1]);
			$id_property = $args[0];
			if ($args[2] === ''){
				$id_user = $this -> dao -> select_id($this -> db,$decode_token['username']);
			}else {
				$id_user = $this -> dao -> select_id_social($this -> db,$decode_token['username'],$args[2]);
			}
			// error_log("El id del usuario es ". $id_user[0]['id_user'],3,'debug.log');
			return $this -> dao -> cart_add_DAO($this -> db,$id_property,$id_user[0]['id_user']);
		}
    }
?>