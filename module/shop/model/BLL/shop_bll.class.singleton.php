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
        return $this->dao->check_like($this->db,$arrArgument[0],$arrArgument[1]);
    }
    public function set_add_like_BLL($arrArgument){
        return $this->dao->like_property($this->db,$arrArgument[0],$arrArgument[1]);
    }
    public function set_add_dislike_BLL($arrArgument){
        return $this->dao->dislike_property($this->db,$arrArgument[0],$arrArgument[1]);
    }
}
?>