<?php
    class profile_dao{
        static $_instance;

        private function __construct() {
        }
        public static function getInstance() {
            if(!(self::$_instance instanceof self)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function select_id($db,$username){
            $sql = "SELECT id_user FROM users WHERE username = '$username'";
            // error_log($sql,3,'debug.log');
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }
        public function select_id_social($db,$username,$social){
            $sql = "SELECT id_user FROM users_$social WHERE username = '$username'";
            // error_log($sql,3,'debug.log');
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }
        public function profile_data_DAO($db,$id){
            $sql = "SELECT * FROM users WHERE id_user = '$id'";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }
        public function profile_data_social_DAO($db,$id,$social){
            $sql = "SELECT * FROM users_$social WHERE id_user = '$id'";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }
        public function profile_orders_DAO($db,$id){
            $sql = "SELECT * FROM purchases WHERE id_user = '$id'";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }
        public function order_detail_DAO($db,$id,$order){
            $sql = "SELECT * FROM purchases WHERE id_user = '$id' AND id = '$order'";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }
    }
?>