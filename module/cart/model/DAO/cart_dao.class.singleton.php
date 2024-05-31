<?php
    class cart_dao{
        static $_instance;

        private function __construct() {
        }
        public static function getInstance() {
            if(!(self::$_instance instanceof self)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function services_DAO($db){
            $sql = "SELECT * FROM services";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }
        public function cart_user_DAO($db,$id){
            $sql = "SELECT service,COUNT(*) quantity,price FROM orders WHERE id_user = '$id' GROUP BY service";
            $stmt = $db->ejecutar($sql);
            error_log($sql,3,'debug.log');
            return $db->listar($stmt);
        }
        public function cart_add_DAO($db,$service,$id_user){
            $sql = "INSERT INTO orders (price,service,id_user) VALUES (50,'$service','$id_user')";
            $stmt = $db->ejecutar($sql);
            return $stmt;
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
        public function cart_add_service_DAO($db,$service,$price,$id_user){
            $sql = "INSERT INTO orders (price,service,id_user) VALUES ($price,'$service','$id_user')";
            error_log($sql,3,'debug.log');
            $stmt = $db->ejecutar($sql);
            return $stmt;
        }
    }
?>