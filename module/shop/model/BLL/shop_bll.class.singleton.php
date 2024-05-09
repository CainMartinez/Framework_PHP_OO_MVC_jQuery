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
}
?>