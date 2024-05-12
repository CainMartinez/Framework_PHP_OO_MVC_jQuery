<?php
class shop_model{
    private $bll;
    static $_instance;

    function __construct(){
        $this->bll = shop_bll::getInstance();
    }

    public static function getInstance(){
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function get_dynamic_city(){
        return $this->bll->get_dynamic_city_BLL();
    }
    public function get_dynamic_large_people(){
        return $this->bll->get_dynamic_large_people_BLL();
    }
    public function get_dynamic_type(){
        return $this->bll->get_dynamic_type_BLL();
    }
    public function get_dynamic_operation(){
        return $this->bll->get_dynamic_operation_BLL();
    }
    public function get_dynamic_extras(){
        return $this->bll->get_dynamic_extras_BLL();
    }
    public function get_dynamic_category(){
        return $this->bll->get_dynamic_category_BLL();
    }
    public function get_list_all($arrArgument){
        return $this->bll->get_list_all_BLL($arrArgument);
    }
    public function get_filters_shop($arrArgument){
        return $this->bll->get_filters_shop_BLL($arrArgument);
    }
    public function get_filters_search($arrArgument){
        return $this->bll->get_filters_search_BLL($arrArgument);
    }
    public function get_details($arrArgument){
        return $this->bll->get_details_property_BLL($arrArgument);
    }
    public function get_similar_properties($arrArgument){
        return $this->bll->get_similar_properties_BLL($arrArgument);
    }
    public function get_pagination(){
        return $this->bll->get_pagination_BLL();
    }
    public function get_pagination_filters($arrArgument){
        return $this->bll->get_pagination_filters_BLL($arrArgument);
    }

}
?>