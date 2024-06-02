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
			try{
				$decode_token = middleware::decode_token($args[0]);
				if ($args[1] === ''){
					$id_user = $this -> dao -> select_id($this -> db,$decode_token['username']);
				}else {
					$id_user = $this -> dao -> select_id_social($this -> db,$decode_token['username'],$args[1]);
				}
				return $this -> dao -> cart_user_DAO($this -> db,$id_user[0]['id_user']);
			}catch (Exception $e){
				error_log("Error en cart_user_BLL ".$e,3,'debug.log');
			}
		}
		public function cart_add_BLL($args) {
			try{
				// error_log("Entro al BLL y el valor es ". $args[1],3,'debug.log');
				$decode_token = middleware::decode_token($args[1]);
				$service = $args[0];
				if ($args[2] === ''){
					$id_user = $this -> dao -> select_id($this -> db,$decode_token['username']);
				}else {
					$id_user = $this -> dao -> select_id_social($this -> db,$decode_token['username'],$args[2]);
				}
				// error_log("El id del usuario es ". $id_user[0]['id_user'],3,'debug.log');
				// si este servicio ya esta en el carrito, no permitir añadir otro
				$cart = $this -> dao -> select_orders($this -> db,$service,$id_user[0]['id_user']);
				if ($cart[0]['service'] == $service) {
					error_log("Servicio encontrado, no añadido " . $cart[0]['service'], 3, 'debug.log');
					return 'error';
				}else{
					// error_log("No matching service found, adding to cart: " . $service, 3, 'debug.log');
					return $this -> dao -> cart_add_DAO($this -> db,$service,$id_user[0]['id_user']);
				}
			}catch (Exception $e){
				error_log("Error en cart_add_BLL ".$e,3,'debug.log');
			}
		}
		public function cart_add_service_BLL($args) {
			try{
				// error_log("Entro al BLL y el valor es ". $args[1],3,'debug.log');
				$decode_token = middleware::decode_token($args[2]);
				$service = $args[0];
				$price = $args[1];
				if ($args[3] === ''){
					$id_user = $this -> dao -> select_id($this -> db,$decode_token['username']);
				}else {
					$id_user = $this -> dao -> select_id_social($this -> db,$decode_token['username'],$args[3]);
				}
				// error_log("El id del usuario es ". $id_user[0]['id_user'],3,'debug.log');
				$stock = $this -> dao -> select_stock($this -> db,$service);
				// error_log("El stock es ". $stock[0]['stock'],3,'debug.log');
				if ($stock[0]['stock'] == 0) {
					return 'error_stock';
				}else {
					return $this -> dao -> cart_add_service_DAO($this -> db,$service,$price,$id_user[0]['id_user']);
				}
			}catch (Exception $e){
				error_log("Error en cart_add_BLL ".$e,3,'debug.log');
			}
		}
		public function cart_delete_BLL($args) {
			try{
				$decode_token = middleware::decode_token($args[1]);
				if ($args[2] === ''){
					$id_user = $this -> dao -> select_id($this -> db,$decode_token['username']);
				}else {
					$id_user = $this -> dao -> select_id_social($this -> db,$decode_token['username'],$args[2]);
				}
				return $this -> dao -> cart_delete_DAO($this -> db,$args[0],$id_user[0]['id_user']);
			}catch (Exception $e){
				error_log("Error en cart_delete_BLL ".$e,3,'debug.log');
			}
		}
		public function cart_plus_BLL($args) {
			try{
				$decode_token = middleware::decode_token($args[1]);
				if ($args[2] === ''){
					$id_user = $this -> dao -> select_id($this -> db,$decode_token['username']);
				}else {
					$id_user = $this -> dao -> select_id_social($this -> db,$decode_token['username'],$args[2]);
				}
				if (strpos($args[0], 'Appointment') !== false) {
					return 'error';
				}else {
					return $this -> dao -> cart_add_service_DAO($this -> db,$args[0],$args[3],$id_user[0]['id_user']);
				}
			}catch (Exception $e){
				error_log("Error en cart_plus_BLL ".$e,3,'debug.log');
			}
		}
		public function cart_minus_BLL($args) {
			try{
				$decode_token = middleware::decode_token($args[1]);
				if ($args[2] === ''){
					$id_user = $this -> dao -> select_id($this -> db,$decode_token['username']);
				}else {
					$id_user = $this -> dao -> select_id_social($this -> db,$decode_token['username'],$args[2]);
				}
				return $this -> dao -> cart_delete_one($this -> db,$args[0],$id_user[0]['id_user']);
			}catch (Exception $e){
				error_log("Error en cart_minus_BLL ".$e,3,'debug.log');
			}
		}
    }
?>