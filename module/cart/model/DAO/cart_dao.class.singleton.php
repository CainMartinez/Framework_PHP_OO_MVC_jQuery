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
        public function select_orders($db,$service,$id_user){
            $sql = "SELECT service FROM orders WHERE service = '$service' AND id_user = '$id_user'";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }
        public function select_stock($db,$service){
            $sql = "SELECT stock FROM services WHERE service = '$service'";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }
        public function cart_user_DAO($db,$id){
            $sql = "SELECT service,COUNT(*) quantity,price FROM orders WHERE id_user = '$id' GROUP BY service ORDER BY service ASC";
            $stmt = $db->ejecutar($sql);
            // error_log($sql,3,'debug.log');
            return $db->listar($stmt);
        }
        public function lines_invoices_DAO($db,$id,$id_purchase){
            $sql = "INSERT INTO lines_invoice (service, quantity, price, id_user, id_purchase) 
                    SELECT service, COUNT(*), price, id_user, '$id_purchase' 
                    FROM orders 
                    WHERE id_user = '$id' 
                    GROUP BY service 
                    ORDER BY service ASC";
            $stmt = $db->ejecutar($sql);
            // error_log($sql,3,'debug.log');
            return $stmt;
        }
        public function select_lines_invoice($db,$id_user,$id_purchase){
            $sql = "SELECT service, quantity, price FROM lines_invoice WHERE id_purchase = '$id_purchase' AND id_user = '$id_user'";
            $stmt = $db->ejecutar($sql);
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
            // error_log($sql,3,'debug.log');
            $stmt = $db->ejecutar($sql);
            return $stmt;
        }
        public function cart_delete_DAO($db,$service,$id_user){
            $sql = "DELETE FROM orders WHERE service = '$service' AND id_user = '$id_user'";
            // error_log($sql,3,'debug.log');
            $stmt = $db->ejecutar($sql);
            return $stmt;
        }
        public function cart_plus_DAO($db,$service,$id_user){
            $sql = "INSERT INTO orders (price,service,id_user) VALUES (50,'$service','$id_user')";
            // error_log($sql,3,'debug.log');
            $stmt = $db->ejecutar($sql);
            return $stmt;
        }
        public function cart_delete_one($db,$service,$id_user){
            $sql = "DELETE FROM orders WHERE service = '$service' AND id_user = '$id_user' LIMIT 1";
            // error_log($sql,3,'debug.log');
            $stmt = $db->ejecutar($sql);
            return $stmt;
        }
        public function insert_data($db, $name, $surname, $address, $city, $zip, $country, $pay_method, $id_user, $total) {
            $sql_insert = "INSERT INTO purchases (name, surname, address, city, zip, country, id_user, pay_method, time, total) VALUES ('$name', '$surname', '$address', '$city', '$zip', '$country', '$id_user', '$pay_method', NOW(), '$total')";
            $db->ejecutar($sql_insert);
            $result = $this->select_data($db, $id_user);
            return $result;
        }
        public function select_data($db, $id_user) {
            $sql_select_orders = "SELECT service, COUNT(*) quantity, price FROM orders WHERE id_user = '$id_user' GROUP BY service ORDER BY service ASC";
            $stmt_orders = $db->ejecutar($sql_select_orders);
            $orders = $db->listar($stmt_orders);

            $sql_select_purchases = "SELECT * FROM purchases WHERE id_user = '$id_user' ORDER BY time DESC LIMIT 1";
            $stmt_purchases = $db->ejecutar($sql_select_purchases);
            $purchases = $db->listar($stmt_purchases);

            $result = array('orders' => $orders, 'purchases' => $purchases);
            return $result;
        }
        public function delete_data($db, $id_user) {
            $sql_delete_orders = "DELETE FROM orders WHERE id_user = '$id_user'";
            $stmt = $db->ejecutar($sql_delete_orders);
            return $stmt;
        }
        public function select_id_purchase($db, $id_user) {
            $sql = "SELECT id FROM purchases WHERE id_user = '$id_user' ORDER BY time DESC LIMIT 1";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }
        public function update_stock($db, $service, $quantity) {
            $sql = "UPDATE services SET stock = stock - $quantity WHERE service = '$service'";
            $stmt = $db->ejecutar($sql);
            return $stmt;
        }
    }
?>