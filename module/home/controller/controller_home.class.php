<?php
	class controller_contact {
		
		function view(){
            // echo 'hola view';
            common::load_view('top_page_home.html', VIEW_PATH_HOME . 'home.html');
        }

    }