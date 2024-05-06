<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	class controller_home {
		
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
    }
?>