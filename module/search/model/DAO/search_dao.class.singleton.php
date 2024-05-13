<?php
    class search_dao{
        static $_instance;

        private function __construct() {
        }

        public static function getInstance() {
            if(!(self::$_instance instanceof self)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function select_auto($db, $param) {
            $complete = isset($param['complete']) ? $param['complete'] : null;
            $name_city = isset($param['id_city']) ? $param['id_city'] : null;
            $name_type = isset($param['id_type']) ? $param['id_type'] : null;

            if (!empty($name_city) && empty($name_type)) {
                return $this->select_only_one_city($db, $name_city, $complete);
            } else if (!empty($name_type) && !empty($name_city)) {
                return $this->select_auto_type_city($db, $name_city, $name_type, $complete);
            } else if (!empty($name_type) && empty($name_city)) {
                return $this->select_only_one_type($db, $name_type, $complete);
            } else {
                return $this->select_category($db, $complete);
            }
        }
        public function select_category($db,$complete){
            
            $sql = "SELECT DISTINCT c.name_category,c.id_category
            FROM property p,property_category pc,category c 
            WHERE p.id_property = pc.id_property
            AND pc.id_category = c.id_category
            AND c.name_category LIKE '$complete%'";
            $stmt = $db -> ejecutar($sql);
            return $db -> listar($stmt);

        }
        public function select_only_one_type($db,$type, $complete){
            $sql = "SELECT DISTINCT c.name_category 
            FROM property p,property_category pc,category c,type t,property_type pt
            WHERE p.id_property = pc.id_property
            AND pc.id_category = c.id_category
            AND pt.id_type = t.id_type
            AND pt.id_property = p.id_property
            AND t.id_type = '$type'
            AND c.name_category LIKE '$complete%'";
            $stmt = $db -> ejecutar($sql);
            return $db -> listar($stmt);
        }
        public function select_only_one_city($db,$city,$complete){
            $sql = "SELECT DISTINCT c.name_category 
            FROM property p,property_category pc,category c 
            WHERE p.id_property = pc.id_property
            AND pc.id_category = c.id_category
            AND p.id_city = '$city'
            AND c.name_category LIKE '$complete%'";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar($stmt);
        }
        public function select_auto_type_city($db,$city, $type, $complete){
            $sql = "SELECT DISTINCT c.name_category 
            FROM property p,property_category pc,category c,type t,property_type pt
            WHERE p.id_property = pc.id_property
            AND pc.id_category = c.id_category
            AND pt.id_type = t.id_type
            AND pt.id_property = p.id_property
            AND t.id_type = '$type'
            AND p.id_city = '$city'
            AND c.name_category LIKE '$complete%'";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar($stmt);
        }
        public function select_search_city($db){
            $sql = "SELECT * FROM city";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar($stmt);
        
        }
        public function select_search_type($db,$city){
            
            $sql = "SELECT DISTINCT *
            FROM type t,property_type pt,property p
            WHERE t.id_type = pt.id_type 
            AND pt.id_property = p.id_property
            AND p.id_city=$city";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar($stmt);
        }
        public function search_type($db){
            
            $sql = "SELECT DISTINCT * FROM type";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar($stmt);
        }

    }
?>