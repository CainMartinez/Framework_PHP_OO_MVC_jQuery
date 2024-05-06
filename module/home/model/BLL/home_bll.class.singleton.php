<?php
    // include_once("module/home/model/DAO/home_dao.class.singleton.php");

	class home_bll {
		private $dao;
		private $db;
		static $_instance;

		function __construct() {
			$this -> dao = home_dao::getInstance();
			$this -> db = db::getInstance();
		}

		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function get_carousel_people_BLL() {
			return $this -> dao -> select_people($this -> db);
		}

		public function get_categories_BLL() {
			return $this -> dao -> select_categories($this -> db);
		}
		
	}
?>