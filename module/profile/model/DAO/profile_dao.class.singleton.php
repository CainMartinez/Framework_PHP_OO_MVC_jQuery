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
        public function select_lines($db,$id_user,$id_purchase){
            $sql = "SELECT service, quantity, price FROM lines_invoice WHERE id_purchase = '$id_purchase' AND id_user = '$id_user'";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }
        public function like_DAO($db,$id_user,$id_property){
            $sql = "DELETE FROM likes WHERE id_user = '$id_user' AND id_property = $id_property";
            $stmt = $db->ejecutar($sql);

            $sql = "UPDATE property SET likes = likes - 1 WHERE id_property = $id_property";
            $stmt = $db->ejecutar($sql);

            return $stmt;
        }

        public function like_social_DAO($db,$id_user,$id_property,$social){
            $sql = "DELETE FROM likes_$social WHERE id = '$id_user' AND id_property = $id_property";
            $stmt = $db->ejecutar($sql);

            $sql = "UPDATE property SET likes = likes - 1 WHERE id_property = $id_property";
            $stmt = $db->ejecutar($sql);

            return $stmt;
        }
        public function change_pass_DAO($db,$username,$new_pass){
            $sql = "UPDATE users SET password = '$new_pass' WHERE username = '$username'";
            error_log($sql,3,'debug.log');
            $stmt = $db->ejecutar($sql);
            return $stmt;
        }
        public function update_avatar($db,$username,$imagePath){
            $sql = "UPDATE users SET avatar = '$imagePath' WHERE username = '$username'";
            $stmt = $db->ejecutar($sql);
            return $stmt;
        }
    }
?>