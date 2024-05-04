<?php
    
	class home_dao {
		static $_instance;

        private function __construct() {
        }

        public static function getInstance() {
            if(!(self::$_instance instanceof self)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }
		public function select_people($db) {
			$sql= "SELECT * FROM `large_people` ORDER BY id_large_people;";
			$stmt = $db -> ejecutar($sql);
            return $db -> listar($stmt);
		}
		public function select_type($db) {
			$sql= "SELECT * FROM `type` ORDER BY id_type ASC LIMIT 30;";

			$stmt = $db -> ejecutar($sql);
            return $db -> listar($stmt);

			
		}
		public function select_extras($db) {
			$sql= "SELECT * FROM `extras`;";

			$stmt = $db -> ejecutar($sql);
            return $db -> listar($stmt);
		}

		function select_categories($db) {
			$sql= "SELECT * FROM category";

			$stmt = $db -> ejecutar($sql);
            return $db -> listar($stmt);
		}
		function select_mostVisited($db){
			$sql= "SELECT * FROM property p, images i
			WHERE p.id_property = i.id_property
			AND i.path_images LIKE '%-1%'
			GROUP BY i.path_images
			ORDER BY p.visits DESC
			LIMIT 4";

			$stmt = $db -> ejecutar($sql);
			return $db -> listar($stmt);
		}
		function select_lastVisited($db){
			$sql= "SELECT * FROM property p, images i
			WHERE p.id_property = i.id_property
			AND i.path_images LIKE '%-1%'
			GROUP BY i.path_images
			ORDER BY p.currently_date DESC
			LIMIT 4";

			$stmt = $db -> ejecutar($sql);
			return $db -> listar($stmt);
		}
		function select_recomendation($db) {
			$sql= "SELECT * ,i.path_images
			FROM property p, images i
			WHERE p.id_property = i.id_property
            AND i.path_images LIKE '%-1%'
            GROUP BY i.path_images
			LIMIT 4";

			$stmt = $db -> ejecutar($sql);
			return $db -> listar($stmt);
		}
		function select_operation($db) {
			$sql= "SELECT * FROM operation ORDER BY id_operation DESC";

			$stmt = $db -> ejecutar($sql);
            return $db -> listar($stmt);
		}
		function select_city($db) {
			$sql= "SELECT * FROM city ORDER BY id_city DESC";

			$stmt = $db -> ejecutar($sql);
            return $db -> listar($stmt);
		}
	}