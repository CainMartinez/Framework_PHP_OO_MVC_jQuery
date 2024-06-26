<?php
class shop_bll{
    private $dao;
    private $db;
    static $_instance;

    function __construct(){
        $this->dao = shop_dao::getInstance();
        $this->db = db::getInstance();
    }

    public static function getInstance(){
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function get_dynamic_city_BLL(){
        return $this->dao->select_city($this->db);
    }
    public function get_dynamic_large_people_BLL(){
        return $this->dao->select_large_people($this->db);
    }
    public function get_dynamic_type_BLL(){
        return $this->dao->select_type($this->db);
    }
    public function get_dynamic_operation_BLL(){
        return $this->dao->select_operations($this->db);
    }
    public function get_dynamic_extras_BLL(){
        return $this->dao->select_extras($this->db);
    }
    public function get_dynamic_category_BLL(){
        return $this->dao->select_categories($this->db);
    }
    public function get_list_all_BLL($arrArgument){
        return $this->dao->select_all_properties($this->db,$arrArgument[0],$arrArgument[1]);
    }
    public function get_filters_shop_BLL($arrArgument){
        return $this->dao->filters_shop($this->db,$arrArgument[0],$arrArgument[1],$arrArgument[2]);
    }
    public function get_filters_search_BLL($arrArgument){
        return $this->dao->search_filter($this->db,$arrArgument[0],$arrArgument[1],$arrArgument[2]);
    }
    public function get_details_property_BLL($arrArgument){
        return $this->dao->select_details_property($this->db,$arrArgument[0]);
    }
    public function get_similar_properties_BLL($arrArgument){
        return $this->dao->select_similar_properties($this->db,$arrArgument[0]);
    }
    public function get_pagination_BLL(){
        return $this->dao->counting($this->db);
    }
    public function get_pagination_filters_BLL($arrArgument){
        return $this->dao->counting_filters($this->db,$arrArgument[0]);
    }
    public function get_check_like_BLL($arrArgument){
        // error_log("social: ".$arrArgument[2],3,'debug.log');
        if ($arrArgument[2] === '') {
            return $this->dao->check_like($this->db,$arrArgument[0],$arrArgument[1]);
        }else{
            return $this->dao->check_like_social($this->db,$arrArgument[0],$arrArgument[1],$arrArgument[2]);
        }
    }
    public function set_add_like_BLL($arrArgument){
        if ($arrArgument[2] === '') {
            return $this->dao->like_property($this->db,$arrArgument[0],$arrArgument[1]);
        }else{
            return $this->dao->like_property_social($this->db,$arrArgument[0],$arrArgument[1],$arrArgument[2]);
        }
    }
    public function set_add_dislike_BLL($arrArgument){
        // error_log("social: ".$arrArgument[2],3,'debug.log');
        if ($arrArgument[2] === '') {
            return $this->dao->dislike_property($this->db,$arrArgument[0],$arrArgument[1]);
        }else{
            return $this->dao->dislike_property_social($this->db,$arrArgument[0],$arrArgument[1],$arrArgument[2]);
        }
    }
    public function get_check_fav_BLL($args){
        try {
            $decode_token = middleware::decode_token($args[0]);
            if ($args[1] === ''){
                $id_user = $this -> dao -> select_id($this -> db,$decode_token['username']);
                $id_properties = $this -> dao -> check_fav($this -> db,$id_user[0]['id_user']);
            } else {
                $id_user = $this -> dao -> select_id_social($this -> db,$decode_token['username'],$args[1]);
                $id_properties = $this -> dao -> check_fav_social($this -> db,$id_user[0]['id_user'], $args[1]);
            }
            $wish_list = array();
            foreach ($id_properties as $id_property) {
                $wish = $this -> dao -> list_wish($this -> db, $id_property['id_property']);
                array_push($wish_list, $wish);
            }
            return $wish_list;
        } catch (Exception $e) {
            error_log("Error occurred: " . $e->getMessage(), 3, "debug.log");
        }
    }
}
?>