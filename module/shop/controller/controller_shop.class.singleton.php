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
    function list_all(){
        echo json_encode(common::load_model('shop_model', 'get_list_all',[$_POST['offset'],$_POST['order']]));
    }
    function filters_shop(){
        echo json_encode(common::load_model('shop_model','get_filters_shop',[$_POST['offset'],$_POST['order'],$_POST['filters_shop']]));
    }
    function filters_search(){
        echo json_encode(common::load_model('shop_model','get_filters_search',$_POST['offset'],[$_POST['order'],$_POST['filters_search']]));
    }
    function details_property(){
        echo json_encode(common::load_model('shop_model', 'get_details',[$_POST['id']]));
    }
    function similar_properties(){
        echo json_encode(common::load_model('shop_model', 'get_similar_properties',[$_POST['id']]));
    }
    function pagination(){
        echo json_encode(common::load_model('shop_model', 'get_pagination'));
    }
    function pagination_filters(){
        echo json_encode(common::load_model('shop_model', 'get_pagination_filters',[$_POST['filters_shop']]));
    }
}
?>