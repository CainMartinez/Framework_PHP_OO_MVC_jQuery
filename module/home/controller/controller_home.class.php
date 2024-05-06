<?php
	class controller_home {
		
		function view(){
            // echo 'hola view';
            common::load_view(VIEW_PATH_INC . 'top_page_home.html', VIEW_PATH_HOME . 'home.html');
        }

    }