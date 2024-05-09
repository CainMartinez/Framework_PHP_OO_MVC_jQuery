<?php
class controller_shop{
    static $_instance;

    public static function getInstance(){ 
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    function view(){
        common::load_view('top_page_shop.html', VIEW_PATH_SHOP . 'shop.html');
    }
    function dynamic_city(){
        echo json_encode(common::load_model('shop_model', 'get_dynamic_city'));
    }
    function dynamic_large_people(){
        echo json_encode(common::load_model('shop_model', 'get_dynamic_large_people'));
    }
    function dynamic_type(){
        echo json_encode(common::load_model('shop_model', 'get_dynamic_type'));
    }
    function dynamic_operation(){
        echo json_encode(common::load_model('shop_model', 'get_dynamic_operation'));
    }
    function dynamic_extras(){
        echo json_encode(common::load_model('shop_model', 'get_dynamic_extras'));
    }
    function dynamic_category(){
        echo json_encode(common::load_model('shop_model', 'get_dynamic_category'));
    }
}
?>