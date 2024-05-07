<?php



	class controller_home {
        static $_instance;

        public static function getInstance() {
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
		function view(){
            common::load_view('top_page_home.html', VIEW_PATH_HOME . 'home.html');
        }
        function carrousel_people() {
            echo json_encode(common::load_model('home_model', 'get_carousel_people'));
        }
        function categories() {
            echo json_encode(common::load_model('home_model', 'get_categories'));
        }
        function type() {
            echo json_encode(common::load_model('home_model', 'get_type'));
        }
        function operation() {
            echo json_encode(common::load_model('home_model', 'get_operation'));
        }
        function city() {
            echo json_encode(common::load_model('home_model', 'get_city'));
        }
        function extras() {
            echo json_encode(common::load_model('home_model', 'get_extras'));
        }
        function recomendation() {
            echo json_encode(common::load_model('home_model', 'get_recomendation'));
        }
        function most_visited() {
            echo json_encode(common::load_model('home_model', 'get_mostVisited'));
        }
        function last_visited() {
            echo json_encode(common::load_model('home_model', 'get_lastVisited'));
        }
    }
?>