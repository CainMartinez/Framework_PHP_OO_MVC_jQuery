<?php
	class search_bll {
		private $dao;
		private $db;
		static $_instance;

		function __construct() {
			$this -> dao = search_dao::getInstance();
			$this -> db = db::getInstance();
		}

		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

        public function get_dynamic_city_BLL() {
            return $this -> dao -> select_search_city($this -> db);
        }

        public function get_dynamic_type_BLL($param) {
            return $this -> dao -> select_search_type($this -> db, $param[0]);
        }

        public function get_type_BLL() {
            return $this -> dao -> search_type($this -> db);
        }

        public function get_autocomplete_BLL($param) {
            return $this -> dao -> select_auto($this -> db, $param[0]);
        }
    }
?>