<?php
	class profile_bll {
		private $dao;
		private $db;
		static $_instance;

		function __construct() {
			$this -> dao = profile_dao::getInstance();
			$this -> db = db::getInstance();
		}
		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		public function profile_data_BLL($args) {
			try {
				$decode_token = middleware::decode_token($args[0]);

				if ($args[1] === ''){
					$id_user = $this -> dao -> select_id($this -> db,$decode_token['username']);
					return $this -> dao -> profile_data_DAO($this -> db,$id_user[0]['id_user']);
				} else {
					$id_user = $this -> dao -> select_id_social($this -> db,$decode_token['username'],$args[1]);
					return $this -> dao -> profile_data_social_DAO($this -> db,$id_user[0]['id_user'], $args[1]);
				}
			} catch (Exception $e) {
				error_log("Error en profile_data_BLL ".$e,3,'debug.log');
			}
		}
    }
?>